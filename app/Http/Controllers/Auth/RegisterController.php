<?php

namespace App\Http\Controllers\Auth;

use App\CandidateInfo;
use App\User;
use App\RoleUser;
use App\CompanySetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\sendgridEmail;
use Auth;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showCandidateRegisterForm()
    {
        $setting = CompanySetting::first();
        return view('candidate.auth.registration')->with(
            ['setting' => $setting]
        );
    }

    public function createCandidate (Request $request, sendgridEmail $emailService)
    {
        $validator = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname'  => 'required',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6'
        ]);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput($request->all())->withErrors($validator);
        }else{

          $remember_token = str_random(60);

          $candidateUser =  User::create([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' =>  $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'remember_token' => $remember_token,
            ]);
 
            $roleUser = new RoleUser();
            $roleUser->user_id = $candidateUser->id;
            $roleUser->role_id = 2;
      
            if (Str::contains(URL::previous(), 'admin/candidate/')){
                $roleUser->save();
                $candidateUser->update(['verified' => 1]);
                $candidateUser->save();
                $c_info = CandidateInfo::create(['user_id' => $candidateUser->id,'candidate_id'=>Uuid::uuid4()->toString(), 'status' => 'enabled']);
                $message = 'Successfully created candidate manually';
                $instruction = 'Log in now to fill up the profile';
                $invalid = 0;
                return view('candidate.auth.verification-original')
                ->with(['email' => $request->input('email'), 
                'pgTitle' => 'Candidate Manually Created',
                'message' => $message, 
                'instruction' => $instruction, 
                'invalid' => $invalid,]);
        }
            $roleUser->save();
            try{
                $emailService->sendVerificationEmailDefault($candidateUser, null);
            }catch(Exception $e){
                return $e->getMessage();
            }
         
            if($candidateUser->remember_token){
                $resendToken = $candidateUser->remember_token;
            }else{
                $resendToken = 'resendToken';
            }

            $message = 'We sent a verification link to '.$request->input('email');
            $instruction =  'Click the link in your email to finish signing up';
            $invalid = 0;

            return view('candidate.auth.verification')
                ->with(['email' => $request->input('email'), 'message' => $message, 'pgTitle'  => 'Verify Your Account',
                 'instruction' => $instruction, 'invalid' => $invalid,  'resendtoken' => $remember_token ]);
        }
    }

    public function verification(Request $request){
      
        $email = trim($request->ref);
     
        $userExists = $this->getCandidate(User::where(['email'=>$email]))[0];
        if($userExists == NULL){
            $invalid = 0;
            $message = 'Your account doesn\'t exist on our systems';
            $instruction = '';
        }else{
            $invalid = 0;
            if($userExists->verified == 1){
                $invalid = 1;
                $message = 'Your account has already been verified';
                $instruction = '';
            }else{
                $message = 'We sent a verification link to your email.';
                $instruction =  'Click the link to finish signing up';
            }
        }
    
        return view('candidate.auth.verification-original')
            ->with(['email' => $email,
              'pgTitle' => 'Verify Your Account', 'message' => $message, 'instruction' => $instruction, 'invalid' => $invalid,]);
    }

    public function resendVerification(Request $request, sendgridEmail $emailService){
        $candidateUser = $this->getCandidate(User::where(['email'=>$request->ref]))[0];
        $resendToken = $request->resend ? $request->resend : 'resendToken';
        if($candidateUser->verified!=1){
           $emailService->sendVerificationEmailDefault($candidateUser, $resendToken);
        }

        $message = 'We have resent a verification link to your email.';
        $instruction =  'Click the link to finish signing up';
        $invalid = 0;

        return view('candidate.auth.verification')
            ->with(['email' => $candidateUser->email, 
            'pgTitle' => 'Verify Your Account',
            'message' => $message, 'pgTitle', 'Verify Your Account'
            ,'instruction' => $instruction, 'invalid' => $invalid,  'resendtoken' => $resendToken ]);
    }
    
    public function onClickVerificationLink($token){
        $token = trim($token);
        $candidateUser = $this->getCandidate(User::where(['remember_token'=>$token]))[0];
        if($candidateUser !== null && $candidateUser->verified != 1){
            $candidateUser->verified = 1;
            $candidateUser->update(['verified' => 1]);
            $c_info = CandidateInfo::create(['user_id' => $candidateUser->id,'candidate_id'=>Uuid::uuid4()->toString(), 'status' => 'enabled']);
            Auth::guard('candidate')->loginUsingId($c_info->id);
            return redirect('/');
        }
        if($candidateUser !== null){
            return redirect()->route('candidate.candidateverification',['ref'=>$candidateUser->email]);
        }
        return redirect('/candidate/signup');
    }
    public function getCandidate($candidate){
        return $candidate->join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', 2)
        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.verified')
        ->distinct('users.id')
        ->get();
        }

}
