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

    public function create(Request $request)
    {
        $bill = new Bill();
        $config = $this->getConfig($request, false);
        $lastId = Bill::orderBy('id', 'desc')->first()->id;

        return view('bills.create', [
            'lang' => 'ca',
            'title' => 'Crear nova factura',
            'bill' => $bill,
            'billInfo' => json_encode($config),
            'last_id' => $lastId
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|unique:bills,id',
            'concept' => 'required',
            'creation_date' => 'required',
            'expiration_date' => 'required',
            'qty' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'price_per_unit' => 'required',

        ]);

        $inputs = Input::all();
        $bill = new Bill();
        $bill->fill($inputs);
        if ($inputs['client_id'] == "" && $inputs['client_name'] != "" && $inputs["patient_id"] == "") {
            $client = new Client();
            $client->name = $inputs['client_name'];
            $client->address = $inputs['client_address'];
            $client->city = $inputs['client_city'];
            $client->cif = $inputs['client_cif'];
            $client->save();
            $bill->client_id = $client->id;
        }
        $bill->save();

        return redirect()->route('mostrarBill', ['id' => $bill->id]);
    }

    public function index()
    {
        echo 'Hello, it\'s me';
    }

    public function show(Request $request, $id)
    {
        $bill = Bill::with(['patient', 'client'])->whereId($id)->firstOrFail();

        $config = $this->getConfig($request, false);
        return view('bills.create', [
            'lang' => 'ca',
            'title' => 'Crear nova factura',
            'bill' => $bill,
            'billInfo' => json_encode($config)
        ]);
    }

    public function getConfig(Request $request, $json = true)
    {
        $billInfo = [
            'name' => config('app.bill.name'),
            'address' => config('app.bill.address'),
            'city' => config('app.bill.city'),
            'dni' => config('app.bill.dni'),
            'account' => base64_encode(config('app.bill.account')),
        ];

        return $json ? json_encode($billInfo) : $billInfo;
    }

    public function getClientsAndPatients($term)
    {
        $patients = Patient::where('id', $term)->orWhere('name', 'like', '%' . $term . '%')->orWhere('surname', 'like', '%' . $term . '%')->orWhere('lastname', 'like', '%' . $term . '%')->limit(15)->get();
        $clients = Client::where('id', $term)->orWhere('name', 'like', '%' . $term . '%')->orWhere('address', 'like', '%' . $term . '%')->orWhere('city', 'like', '%' . $term . '%')->orWhere('cif', 'like', '%' . $term . '%')->limit(15)->get();
        $array = [
            'patients' => $patients,
            'clients' => $clients
        ];
        echo json_encode($array);
    }
}
