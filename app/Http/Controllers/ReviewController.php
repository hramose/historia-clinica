<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Patient;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
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
        //
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
    public function store(Request $request, $id)
    {
        $review = Review::where('patient_id', $id)->first();
        if (is_null($review)) {
            $review = new Review();
        }
        $review->fill(Input::all());
        if ($review->patiend_id == '') $review->patient_id = $id;

        $review->save();

        Session::flash('alert', trans('models.Reviewmsgsavedcorrectly'));
        Session::flash('status', 'success');

        return Redirect::route('valoracions.pacient.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::where('patient_id', $id)->first();
        $patient = Patient::where('id', $id)->first();
        if (is_null($review)) {
            $review = new Review();
        }

        return view('review/show', [
            'lang' => 'ca',
            'review' => $review,
            'pacient' => $patient,
            'title' => 'Valoració del pacient: ' . $patient->name . ' ' . $patient->surname . ' ' . $patient->lastname
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
