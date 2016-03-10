<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pacients = Patient::paginate(15);
        $pacients->setPath('llista');
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
        return view('pacients/form', [
            'lang' => 'ca',
            'title' => 'Crea un nou pacient'
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

        if (!is_null(Patient::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'lastname' => $request->input('lastname'),
            'nif' => $request->input('nif'),
            'gender' => $request->input('gender'),
            'birth_date' => $request->input('birth_date'),
            'age' => $request->input('age'),
            'profession' => $request->input('profession'),
            'hobbies' => $request->input('hobbies'),
            'address' => $request->input('address'),
        ]))
        ) {
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

    public function getMatchPatients($term) {
        $patients = Patient::where('id', $term)->orWhere('name', $term)->all();
    }
}
