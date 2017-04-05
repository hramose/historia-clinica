<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Session;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getResetPassword()
    {
        $data = [
            'lang' => 'ca',
            'title' => trans('messages.title_reset')
        ];
        if (isset(Auth::user()->email)) {
            $data['email'] = Auth::user()->email;
        }
        return view('auth.reset_password', $data);
    }

    public function postResetPassword(Request $request)
    {
        if (isset($request->user()->email)) {
            $this->validate($request, [
                'password' => 'min:6|required',
                'password_confirmation' => 'same:password|required'
            ]);
        } else {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'min:6|required',
                'password_confirmation' => 'same:password|required'
            ]);
        }

        if (isset($request->user()->email)) {
            $user = $request->user();
            $user->setPassword($request->input('password'));
            $user->password_reset = Carbon::now();
            $user->save();

            Auth::logout();
            Session::flash('alert', trans('messages.already_changed_pass'));
            Session::flash('status', 'success');
        } else {
            $user = User::whereEmail($request->input('email'))->first();
            $user->setPassword($request->input('password'));
            $user->password_reset = Carbon::now();
            $user->save();

            Session::flash('alert', trans('messages.already_changed_pass'));
            Session::flash('status', 'success');
        }

        Session::forget('password_expired');

        return redirect()->route('getLogin');
    }

    public static function checkForPasswordExpiration()
    {
        if (Session::has('password_expired')) {
            Session::flash('alert', trans('messages.password_reset', ['mesos' => 6]));
            Session::flash('status', 'warning');
            return true;
        }

        return false;
    }
}