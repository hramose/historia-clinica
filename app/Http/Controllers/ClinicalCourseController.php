<?php

namespace App\Http\Controllers;

use App\ClinicalCourse;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Patient;
use Illuminate\Support\Facades\Session;

class ClinicalCourseController extends Controller
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
    public function index()
    {
        return view('courseclinic/index', [
            'lang' => 'ca',
            'title' => 'Curs clínic dels pacients'
        ]);
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
     * @param Request|\Illuminate\Http\Request $request
     * @param Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Patient $patient)
    {
        $clinicalCourse = new ClinicalCourse();
        $clinicalCourse->patient_id = $patient->id;
        $params = $request->get('cclinic');
        $clinicalCourse->content = $params['content'];
        $clinicalCourse->date = Carbon::createFromFormat('d/m/Y', $params['date']);

        $clinicalCourse->save();

        Session::flash('alert', trans('models.Cclinicmsgsavedcorrectly'));
        Session::flash('status', 'success');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $id_course = null)
    {
        $patient = Patient::where('id', $id)->first();
        $clinicalCourse = new ClinicalCourse();
        if (!is_null($id_course)) {
            $clinicalCourse = ClinicalCourse::where('id', $id_course)->first();
        }

        $allClinicalCourses = $patient->clinicalCourses;

        return view('courseclinic/show', [
            'lang' => 'ca',
            'pacient' => $patient,
            'clinicalCourses' => $allClinicalCourses,
            'clinicalCourse' => $clinicalCourse,
            'title' => 'Curs clínic del pacient: ' . $patient->name . ' ' . $patient->surname . ' ' . $patient->lastname
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
     * @param ClinicalCourse $clinicalCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient, ClinicalCourse $clinicalCourse)
    {
        $params = $request->get('cclinic');
        $clinicalCourse->content = $params['content'];

        $clinicalCourse->save();

        Session::flash('alert', trans('models.Cclinicmsgsavedcorrectly'));
        Session::flash('status', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Patient $patient
     * @param ClinicalCourse $clinicalCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Patient $patient, ClinicalCourse $clinicalCourse)
    {
        dd($clinicalCourse);
    }

    public function getPacients($term)
    {
        $patients = Patient::where('id', $term)->orWhere('name', 'like', '%' . $term . '%')->orWhere('surname', 'like', '%' . $term . '%')->orWhere('lastname', 'like', '%' . $term . '%')->with('clinicalCourses')->limit(15)->get();
        echo $patients->toJson();
    }
}
