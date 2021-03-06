<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class PatientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['findPacientByDni', 'requestNewPatient']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pacients = Patient::orderBy('surname', 'ASC')->paginate(15);
        $pacients->setPath(URL::route('pacientsLlista'));
        return view('pacients/index', [
            'lang' => 'ca',
            'title' => 'Crea un nou pacient',
            'pacients' => /*Patient::all()->take($number)*/
                $pacients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pacient = new Patient();
        if ($request->session()->has('errors')) {
            $pacient->name = Input::old('name');
            $pacient->surname = Input::old('surname');
            $pacient->lastname = Input::old('lastname');
            $pacient->email = Input::old('email');
            $pacient->phone = Input::old('phone');
            $pacient->nif = Input::old('nif');
            $pacient->gender = Input::old('gender');
            $pacient->birth_date = Input::old('birth_date');
            $pacient->age = Input::old('age');
            $pacient->profession = Input::old('profession');
            $pacient->hobbies = Input::old('hobbies');
            $pacient->address = Input::old('address');
            $pacient->city = Input::old('city');
            $pacient->postal_code = Input::old('postal_code');
        }

        return view('pacients/form', [
            'lang' => 'ca',
            'title' => 'Crea un nou pacient',
            'pacient' => $pacient
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
            'nif' => 'required|unique:patients,nif',
        ]);

        $patient = new Patient();
        $patient->fill(Input::all());
        if ($patient->save()) {
            Session::flash('alert', 'Pacient creat correctament');
            Session::flash('status', 'success');
            return redirect('pacients/llista');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('pacients/form_dades', [
            'lang' => 'ca',
            'title' => 'Actualitza dades d\'un pacient',
            'patient' => Patient::findOrFail($id)
        ]);
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
        $inputs = Input::all();

        $patient = Patient::findOrFail($id);
        $patient->fill($inputs);

        if ($patient->save()) {
            Session::flash('alert', trans('messages.patient_update_correctly'));
            Session::flash('status', 'success');

            return view('pacients/form_dades', [
                'lang' => 'ca',
                'title' => 'Actualitza dades d\'un pacient',
                'patient' => $patient
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
        $patient = Patient::findOrFail($id);
        $patient->delete();

        Session::flash('alert', 'Pacient eliminat correctament');
        Session::flash('status', 'success');
        return redirect('pacients/llista');
    }

    public function listaFront($term)
    {
        $patients = Patient::where('id', $term)
            ->orWhere('name', 'like', '%' . $term . '%')
            ->orWhere('surname', 'like', '%' . $term . '%')
            ->orWhere('lastname', 'like', '%' . $term . '%')
            ->orWhere(DB::raw('CONCAT(name, " ", surname, " ", lastname)'), 'like', '%' . $term . '%')
            ->get();
        echo json_encode($patients);
    }

    public function findPacientByDni(Request $request)
    {
        $patient = Patient::whereNif(Input::get('dni'))->first();
        $data = [];
        $data['dni'] = Input::get('dni');

        if ($patient != null) {
            $data['title'] = 'Pacient ' . $patient->full_name;
            $data['foundPacient'] = true;
            $data['patient'] = $patient;
            $data['mailSend'] = false;
        } else {
            $data['title'] = 'Pacient no trobat';
            $data['foundPacient'] = false;
            $data['mailSend'] = false;
        }

        return view('front.home_guest', $data);
    }

    public function requestNewPatient(Request $request)
    {
        $inputs = Input::all();

        Mail::send('emails.nou_pacient', $inputs, function (Message $message) {
            $message->from('fisioterapia@hcabosantos.cat', 'Peticions de sessions');
            $message->to(env('MAIL_PRINCIPAL', 'suport@hcabosantos.cat'), 'Fisioteràpia HCaboSantos.cat')
                ->subject(trans('messages.new_pacient_request'));
        });

        $data['title'] = 'Pacient no trobat';
        $data['foundPacient'] = false;
        $data['mailSend'] = true;

        return view('front.home_guest', $data);
    }

    public function getMatchPatients($term)
    {
        $patients = Patient::where('id', $term)->orWhere('name', 'like', '%' . $term . '%')->orWhere('surname', 'like', '%' . $term . '%')->orWhere('lastname', 'like', '%' . $term . '%')->limit(15)->get();
        echo json_encode($patients);
    }
}
