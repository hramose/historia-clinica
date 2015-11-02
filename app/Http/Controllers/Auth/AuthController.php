<?php

namespace App\Http\Controllers\Auth;

use App;
use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\Controller;
use App\Rol;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Lang;
use Validator;

class AuthController extends Controller
{

    protected $redirectTo = '/';
    protected $maxLoginAttempts = 5;

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout', 'getRegister', 'postRegister']]);
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
            'rol' => 'required|not_in:0',
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
        $allRols = Rol::all();
        $rols = [0 => ''];

        foreach ($allRols as $rol) {
            $rols[$rol['id']] = $rol['title'];
        }
        return view('auth.register', [
            'lang' => 'ca',
            'rols' => $rols,
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

        Session::flash('alert-success', trans('messages.register_successful'));
        return redirect($this->loginPath());
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
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the login credentials and requirements.
     *
     * @param  Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'verified' => true
        ];
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

        Session::flash('alert-success', trans('messages.succesful_verified'));

        return redirect($this->loginPath());
    }

    public function getSendVerificationMail(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = User::whereEmail($request->input('email'))->firstOrFail();
            $confirmation_code = $user->token;
            if ($confirmation_code != '') {
                Mail::send('emails.verify', ['lang' => 'ca', 'title' => trans('messages.title_verify_email_doc'), 'confirmation_code' => $confirmation_code], function ($message) {
                    $message->from('rcamara9@gmail.com', 'Administració');
                    $message->to(Input::get('email'), Input::get('name'))
                        ->subject(trans('messages.title_verify_email'));
                });

                Session::flash('alert-success', trans('messages.sendver_correctly'));
            } else {
                Session::flash('alert-success', trans('messages.already_verified'));
            }

            return redirect($this->loginPath());
        } else {
            return view('auth.sendverificationmail', [
                'lang' => 'ca',
                'title' => Lang::get('messages.title_sendmailver')
            ]);
        }
    }

    public function getResetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'min:6|required',
                'password_confirmation' => 'same:password|required'
            ]);

            if (isset($request->user()->email)) {
                $user = $request->user();
                $user->setPassword($request->input('password'));
                $user->save();

                Auth::logout();
                Session::flash('alert-success', trans('messages.already_changed_pass'));
            } else {
                $user = User::whereEmail($request->input('email'))->first();
                $user->setPassword($request->input('password'));
                $user->save();

                Session::flash('alert-success', trans('messages.already_changed_pass'));
            }

            return redirect($this->loginPath());
        } else {
            $data = [
                'lang' => 'ca',
                'title' => trans('title_reset')
            ];
            if (isset(Auth::user()->email)) {
                $data['email'] = Auth::user()->email;
            }
            return view('auth.reset_password', $data);
        }

    }
}
