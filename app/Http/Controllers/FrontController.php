<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Menu;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
}
