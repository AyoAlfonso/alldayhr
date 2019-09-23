<?php

namespace App\Http\Controllers\Auth;

use App\CandidateInfo;
use App\CompanySetting;
use App\Http\Controllers\Controller;
use App\ThemeSetting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $setting = CompanySetting::first();
        $frontTheme = ThemeSetting::first();
        return view('auth.login', compact('setting', 'frontTheme'));
    }

    /**
     * Show the candidate login form.
     * @return \Illuminate\Http\Response
     */
    public function showCandidateLoginForm()
    {
        return view('candidate.auth.login');
    }

    protected function redirectTo()
    {
        return 'admin/dashboard';
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }

    public function getCandidate($candidate)
    {
        return $candidate->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', 2)
            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.verified')
            ->distinct('users.id')
            ->get();
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function attemptCandidateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->withErrors($validator);
        } else {
            $credentials = $request->only('email', 'password');
            if ($user = User::getUser($credentials)) {
                if ($user->verified==null) {
                    return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                        ->withErrors(['Your account is not verified']);
                }

                $candidateUser = CandidateInfo::getByUserID($user->id);
                if ($candidateUser) {
                   if ($user->verified == 1) {
                       if (Auth::guard('candidate')->loginUsingId($candidateUser->id)) {
                           return redirect('/candidate/dashboard');

                       }
                   }
               }
               redirect('/candidate/login');
            }
            // protected function attemptCandidateLogin(Request $request)
            // {
            //     $validator = Validator::make($request->all(), [
            //         'email' => 'required',
            //         'password' => 'required'
            //     ]);
        
            //     if ($validator->fails()) {
            //         return redirect()
            //             ->back()
            //             ->withInput($request->only('email'))
            //             ->withErrors($validator);
            //     } else {
                    
            //         $credentials = $request->only('email', 'password');
            //         if ($user = User::getUser($credentials)) {
            //                 if ($user->verified==null) {
            //                     // dd('luda@mailinator.net');
            //                     return redirect()
            //                     ->back()
            //                     ->withInput($request->only('email'))
            //                         ->withErrors(['Your account is not verified']);
            //                 }
        
            //                 $candidateUser = CandidateInfo::getByUserID($user->id);
            //                 if ($candidateUser) {
            //                    if ($user->verified == 1) {
            //                        if (Auth::guard('candidate')->loginUsingId($candidateUser->id)) {
            //                            return redirect('/candidate/dashboard');
           
            //                        }
            //                    }
            //                }
            //             }
            //             redirect('/candidate/login');
            //         }
            //     }
        
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::guard('api')->user()->candidate->getAllProfile(),
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendCandidateLoginResponse(Request $request)
    {

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        $user = auth()->user();
        $candidate = auth()->guard('candidate')->user();
        if (!$user && !$candidate) {
            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->withErrors(['Invalid login credentials']);
        }
        if ($user && $user->hasRole('candidate')) {
            if ($user->verified == 0) {
                return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['Your account is not verified']);
            }
            if ($user->verified == 1) {
                if(!empty($request->input('intended'))){
                    return redirect()->intended($request->input('intended'));
                }
                return redirect('/candidate/dashboard');
            }
        }

        if ($candidate) {
            if(!empty($request->input('intended'))){
                return redirect()->intended($request->input('intended'));
            }
            return redirect('/candidate/dashboard');
        }

//        return $this->authenticated($request, $this->guard()->user())
        //           ?: redirect()->intended($this->redirectPath());
    }

    /**
     *  Authenticate and Log candidate in
     * @return \Illuminate\Http\Response
     */
    public function candidateLogin(Request $request)
    {
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptCandidateLogin($request)) {
             return $this->sendCandidateLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function candidateLoginAPI(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);
        } else {
            $credentials = request(['email', 'password']);
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
            }
            if (Auth::guard('api')->user()->verified !== 1) {
                return response()->json(['error' => true, 'message' => 'Your account is not verified.'], 401);
            }
            return $this->respondWithToken($token);
        }

    }
}

