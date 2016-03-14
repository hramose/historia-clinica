<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Client;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        $this->validate($request, [
            'id' => 'required:unique:bills',
            'concept' => 'required'
        ]);

        $bill = new Bill();
        $bill->fill(Input::all());
        $bill->save();

        return redirect()->route('mostrarBill', ['id' => $bill->id]);
    }

    public function index()
    {
        echo 'Hello, it\'s me';
    }

    public function show(Request $request, $id)
    {
        $bill = Bill::firstOrFail($id);

        return view('bills.create', [
            'lang' => 'ca',
            'title' => 'Editar factura',
            'bill' => $bill
        ]);
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

    public function getClientsAndPatients($term)
    {
        $patients = Patient::where('id', $term)->orWhere('name', 'like', '%'.$term.'%')->orWhere('surname', 'like', '%'.$term.'%')->orWhere('lastname', 'like', '%'.$term.'%')->limit(15)->get();
        $clients = Client::where('id', $term)->orWhere('name', 'like', '%'.$term.'%')->orWhere('address', 'like', '%'.$term.'%')->orWhere('city', 'like', '%'.$term.'%')->orWhere('cif', 'like', '%'.$term.'%')->limit(15)->get();
        $array = [
            'patients' => $patients,
            'clients' => $clients
        ];
        echo json_encode($array);
    }
}
