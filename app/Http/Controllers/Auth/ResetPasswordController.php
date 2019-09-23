<?php

namespace App\Http\Controllers\Auth;

use App\CompanySetting;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Mail\sendgridEmail;
use App\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $setting = CompanySetting::first();
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'setting' => $setting]
        );
    }

    /**
     * Show the application's forgot password form.
     */
    public function beginResetPass(Request $request, sendgridEmail $emailService) {
        return view('candidate.auth.forgot-password');
    }

    public function resendResetPass(Request $request, sendgridEmail $emailService){
        $resendResetEmail = $request->ref;
        $userWithoutTokenExists = $this->getCandidate(User::where(['email'=> $resendResetEmail]))[0];
        if($userWithoutTokenExists != NULL and $resendResetEmail != NULL ){
            $encodedToken = Crypt::encryptString($resendResetEmail );
            $encodedToken = $encodedToken . "_" . (time() + 60 * 5);
            $emailService->sendResetPassword($encodedToken, $userWithoutTokenExists, 1);
            return redirect('candidate/forgot-password')
            ->with(['candidateUser' => $userWithoutTokenExists]);
        }
    }

    /**
     * Send a reset link to the given user.
     */
    public function getResetToken(Request $request, sendgridEmail $emailService) {
        $this->validate($request, ['email' => 'required|email']);
        if ($request) {
            $query = User::where(['email'=>$request->input('email')]);
            $candidateUser = $query->first(); 
             
            if (empty($candidateUser)) {
                    return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['User with email not found']);
                }

            $encodedToken = Crypt::encryptString($candidateUser->email );
            $encodedToken = $encodedToken . "_" . (time() + 60 * 5);
            $invalid = 0;
            $message = 'Click the link. We sent a reset link to your email.';
            $instruction =  'Click the link to finish signing up';

            try{
               $emailService->sendResetPassword($encodedToken, $candidateUser, null);
            }catch(Exception $e){
                return $e->getMessage();
            }

            return view('candidate.auth.sent-pass-reset')
             ->with(['email' =>  $candidateUser->email, 'message' => $message, 'instruction' => $instruction, 'invalid' => $invalid]);
        }
    }

    public function getChangePass($token){
        if ($token){
        return view('candidate.auth.change-pass')
        ->with(['token' => $token, 'invalid' => 0]);
        }
    }

    public function onClickPassResetLink(Request $request, $token){
        $encodedToken = trim($token);
        $this->validate($request, [
            'password' => 'min:6',
        ]);
        $tokenArray = explode('_', $encodedToken);
        $decryptedEmail = Crypt::decryptString($tokenArray[0]);

         if (time() > $tokenArray[1]){
            return redirect()
            ->back()
            ->withInput($request->only('email'))
            ->withErrors(['Reset Token expired']);
         }

        $query = User::where(['email'=>$decryptedEmail]);
        $candidateUser = $query->first();

        if (!$candidateUser) {
            return redirect()
            ->back()
            ->withInput($request->only('email'))
            ->withErrors(['User with email not found']);
        }

        if($candidateUser !== NULL){
            $updateData=array(
                'password' => Hash::make($request->input('password')),
                );
                $query->update($updateData);
                return redirect('candidate/reset-pass-success');              
        }
        return redirect('login');
    }
    
    public function  showSuccessPage(){
    return view('candidate.auth.reset-pass-success');
    }

    protected function redirectTo()
    {
        $user = auth()->user();
        if($user->hasRole('admin')){
            return 'admin/dashboard';
        }
        elseif($user->hasRole('employee')){
            return 'member/dashboard';
        }
    }
    public function getCandidate($candidate){
        return $candidate->join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', 2)
        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.verified')
        ->distinct('users.id')
        ->get();
        }

}

