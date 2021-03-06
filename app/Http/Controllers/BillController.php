<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Client;
use App\Patient;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use NumberFormatter;

class BillController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['generatePdf']]);
    }

    public function index()
    {
        $bills = Bill::orderBy('id', 'desc')->paginate(10);
        $bills->setPath('factures/llista');
        $formatter = new NumberFormatter('es_ES', NumberFormatter::CURRENCY);

        return view('bills.index', [
            'lang' => 'ca',
            'title' => 'Llista de factures',
            'bills' => $bills,
            'formatter' => $formatter,
        ]);

    }

    public function create(Request $request)
    {
        $bill = new Bill();
        $config = $this->getConfig($request, false);
        $tempBill = Bill::orderBy('id', 'desc')->first();
        $lastId = $tempBill != null ? $tempBill->id : 99;

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
            $client->postal_code = $inputs['client_postal_code'];
            $client->cif = $inputs['client_cif'];
            $client->save();
            $bill->client_id = $client->id;
        }
        $bill->save();

        Session::flash('alert', 'Factura creada correctament');
        Session::flash('status', 'success');

        return redirect()->route('mostrarBill', ['id' => $bill->id]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'concept' => 'required',
            'creation_date' => 'required',
            'expiration_date' => 'required',
            'qty' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'price_per_unit' => 'required',

        ]);

        $inputs = Input::all();
        $bill = Bill::whereId($id)->firstOrFail();
        $bill->fill($inputs);
        if ($inputs['client_id'] == "" && $inputs['client_name'] != "" && $inputs["patient_id"] == "") {
            $client = new Client();
            $client->name = $inputs['client_name'];
            $client->address = $inputs['client_address'];
            $client->city = $inputs['client_city'];
            $client->postal_code = $inputs['client_postal_code'];
            $client->cif = $inputs['client_cif'];
            $client->save();
            $bill->client_id = $client->id;
        } else {
            if ($bill->client != null) {
                $bill->client->name = $inputs['client_name'];
                $bill->client->address = $inputs['client_address'];
                $bill->client->city = $inputs['client_city'];
                $bill->client->postal_code = $inputs['client_postal_code'];
                $bill->client->cif = $inputs['client_cif'];
                $bill->client->save();
            }
        }
        $bill->save();

        Session::flash('alert', 'Factura actualitzada correctament');
        Session::flash('status', 'success');

        return redirect()->route('mostrarBill', ['id' => $bill->id]);
    }

    public function show(Request $request, $id)
    {
        $bill = Bill::with(['patient', 'client'])->whereId($id)->firstOrFail();
        $lastId = Bill::orderBy('id', 'desc')->first()->id;

        $config = $this->getConfig($request, false);
        return view('bills.create', [
            'lang' => 'ca',
            'title' => 'Crear nova factura',
            'bill' => $bill,
            'billInfo' => json_encode($config),
            'last_id' => $lastId
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $bill = Bill::with(['patient', 'client'])->whereId($id)->firstOrFail();
        $bill->delete();

        Session::flash('alert', 'Factura eliminada correctament');
        Session::flash('status', 'success');

        return redirect()->route('veureBills');
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

    public function generatePdf($id)
    {
        $bill = Bill::with(['patient', 'client'])->whereId($id)->firstOrFail();
        $config = $this->getConfig(new Request(), false);

        /* return view('bills.pdf', [
             'bill' => $bill,
             'billInfo' => $config
         ]);*/

        $formatter = new NumberFormatter('es_ES', NumberFormatter::CURRENCY);

        $pdf = PDF::loadView('bills.pdf', [
            'bill' => $bill,
            'billInfo' => $config,
            'formatter' => $formatter
        ]);

        $pdf->setOption('footer-html', utf8_decode(view('bills.footer')));

        return $pdf->stream();
    }
}
