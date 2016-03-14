<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\Request;

class BillController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('bills.create', [
            'lang' => 'ca',
            'title' => 'Crear nova factura'
        ]);
    }

    public function index()
    {
        echo 'Hello, it\'s me';
    }

    public function show(Request $request, $id) {

    }
}
