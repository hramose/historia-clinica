<?php

namespace App\Http\Controllers;

use App\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $bill = new Bill();

        return view('bills.create', [
            'lang' => 'ca',
            'title' => 'Crear nova factura',
            'bill' => $bill
        ]);
    }

    public function store(Request $request)
    {

    }

    public function index()
    {
        echo 'Hello, it\'s me';
    }

    public function show(Request $request, $id)
    {

    }

    public function getConfig(Request $request)
    {
        $billInfo = [
            'name' => config('app.bill.name'),
            'address' => config('app.bill.address'),
            'city' => config('app.bill.city'),
            'dni' => config('app.bill.dni'),
            'account' => base64_encode(config('app.bill.account')),
        ];

        return json_encode($billInfo);
    }
}
