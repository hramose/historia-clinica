<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests;
use App\Menu;
use App\Patient;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->action('Auth\AuthController@getLogin');
        }

        if (AuthController::checkForPasswordExpiration()) return redirect('auth/reset_password');

        return view('front/index', [
            'lang' => 'ca',
            'title' => 'Pàgina principal'
        ]);
    }

    public function getFormMenu()
    {
        $allMenu = Menu::all();
        $menus = [0 => ''];

        foreach ($allMenu as $menu) {
            $menus[$menu['id']] = $menu['title'];
        }
        return view('front/form_menu', [
            'lang' => 'ca',
            'menus' => $menus,
            'title' => 'Creació de les opcions de menú'
        ]);
    }

    public function postCreateMenu(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'url' => 'required'
        ]);

        Menu::create([
            'title' => $request->input('title'),
            'father_id' => $request->input('father_id')
        ]);

        Session::flash('alert-success', 'Menú creado correctamente');

        return redirect('crear_menus');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.show', [
            'lang' => 'ca',
            'title' => 'Crea nou usuari',
            'user' => new User()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ]);

        $inputs = $request->all();

        $user = User::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => bcrypt($inputs['password']),
        ]);

        if ($user->exists) {
            Session::flash('alert', 'test');
            Session::flash('status', 'success');
        }

        return redirect()->route('llistaUsers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $inputs = Input::except('password');
        $user->fill($inputs);

        if ($user->save()) {
            Session::flash('alert', trans('messages.user_update_correctly'));
            Session::flash('status', 'success');

            return view('auth/show', [
                'lang' => 'ca',
                'title' => 'Dades del usuari: ' . $user->name,
                'user' => User::findOrFail($id)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function userList(Request $request)
    {
        $users = User::paginate(15);
        $users->setPath('llistaUsers');
        return view('auth.list', [
            'lang' => 'ca',
            'title' => 'Llista d\'usuaris',
            'users' => $users
        ]);
    }

    public function showUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('auth/show', [
            'lang' => 'ca',
            'title' => 'Dades del usuari: ' . $user->name,
            'user' => $user
        ]);
    }

    public function destroyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $users = User::paginate(15);
        $users->setPath('llistaUsers');

        Session::flash('alert', trans('messages.user_deleted_correctly'));
        Session::flash('status', 'success');
        return view('auth.list', [
            'lang' => 'ca',
            'title' => 'Llista d\'usuaris',
            'users' => $users
        ]);
    }
}
