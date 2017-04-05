<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Lang;
use Mail;

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
    protected $redirectTo = '/';

    protected $maxLoginAttempts = 3;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getRegister', 'postRegister', 'logout']]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('auth.login', [
            'lang' => 'ca',
            'title' => Lang::get('messages.title_inicia')
        ]);
    }

    /**
     * @param Request $request
     * @return LoginController|\Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 'password' => 'required|min:6|',
        ]);

        $userEmail = $request->input('email');
        $user = User::whereEmail($userEmail)->first();
        if ($user != null) {
            if ($user->blocked == 1) {
                return $this->showBlockedResponse($request);
            }
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if ($this->hasTooManyLoginAttempts($request)) {
            $user = User::whereEmail($userEmail)->firstOrFail();
            $user->blocked = 1;
            $user->save();
            /*return $this->sendLockoutResponse($request);*/
            return $this->showBlockedResponse($request);
        }

        $credentials = $this->credentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $this->clearLoginAttempts($request);
            $createdDate = new Carbon(Auth::user()->created_at);
            $passwordResetDate = new Carbon(Auth::user()->password_reset);
            $nowDate = Carbon::now();
            if ($createdDate->diff($nowDate)->m >= 6 && is_null(Auth::user()->password_reset)) {
                Session::flash('alert-warning', trans('messages.password_reset', ['mesos' => 6]));
                Session::put('password_expired', true);
                return redirect()->route('reset_password');//redirect to password reset page
            } else if ($passwordResetDate->diff($nowDate)->m >= 6) {
                Session::flash('alert-warning', trans('messages.password_reset', ['mesos' => 6]));
                Session::put('password_expired', true);
                return redirect()->route('reset_password');//redirect to password reset page
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            /*'id_rol' => 'required|not_in:0'*/
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {
        /*$allRols = Rol::all();
        $rols = [0 => ''];

        foreach ($allRols as $rol) {
            $rols[$rol['id']] = $rol['title'];
        }*/
        return view('auth.register', [
            'lang' => 'ca',
            /*'rols' => $rols,*/
            'title' => Lang::get('messages.title_register')]);

    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        //Auth::login($this->create($request->all()));
        $user = $this->create($request->all());
        $confirmation_code = $user->token;
        Mail::send('emails.verify', ['email' => true, 'lang' => 'ca', 'title' => trans('messages.title_verify_email_doc'), 'confirmation_code' => $confirmation_code], function ($message) {
            $message->from('rcamara9@gmail.com', 'Administració');
            $message->to(Input::get('email'), Input::get('name'))
                ->subject(trans('messages.title_verify_email'));
        });

        Session::flash('alert', trans('messages.register_successful'));
        Session::flash('status', 'success');
        return redirect()->route('getLogin');
    }

    public function getConfirmation($confirmation_code)
    {
        if (!$confirmation_code) {
            throw new InvalidTokenException;
        }

        $user = User::whereToken($confirmation_code)->first();

        if (!$user) {
            throw new InvalidTokenException;
        }

        $user->verified = 1;
        $user->token = null;
        $user->save();

        Session::flash('alert', trans('messages.succesful_verified'));
        Session::flash('status', 'success');

        return redirect()->route('getLogin');
    }

    public function getSendVerificationMail(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = User::whereEmail($request->input('email'))->firstOrFail();
            $confirmation_code = $user->token;
            if ($confirmation_code != '') {
                Mail::send('emails.verify', ['lang' => 'ca', 'title' => trans('messages.title_verify_email_doc'), 'confirmation_code' => $confirmation_code], function ($message) {
                    $message->from('fisioterapia@hcabosantos.cat', 'Administració');
                    $message->to(Input::get('email'), Input::get('name'))
                        ->subject(trans('messages.title_verify_email'));
                });

                Session::flash('alert', trans('messages.sendver_correctly'));
                Session::flash('status', 'success');
            } else {
                Session::flash('alert', trans('messages.already_verified'));
                Session::flash('status', 'success');
            }

            return redirect()->route('getLogin');
        } else {
            return view('auth.sendverificationmail', [
                'lang' => 'ca',
                'title' => Lang::get('messages.title_sendmailver')
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showBlockedResponse(Request $request)
    {
        return redirect()
            ->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('messages.user_blocked'),
            ]);
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        $lockoutTime = 1; // In minutes

        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxLoginAttempts, $lockoutTime
        );
    }

    private function getFailedLoginMessage()
    {
        return trans('auth.failed');
    }
}