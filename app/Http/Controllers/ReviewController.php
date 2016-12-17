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
        /* $review = Review::where('patient_id', $id)->first();
         if (is_null($review)) {
             $review = new Review();
         }*/
        $inputs = Input::all();
        $this->clean_dates($inputs);
        if (Input::get('id') == '') {
            $review = new Review();
        } else {
            $review = Review::find(Input::get('id'));
        }
        $review->fill($inputs);
        if ($review->patient_id == '') $review->patient_id = $id;

        $review->save();

        $review->createOrStoreClinicalCourse();

        Session::flash('alert', trans('models.Reviewmsgsavedcorrectly'));
        Session::flash('status', 'success');

        return Redirect::route('valoracions.pacient.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param Patient $patient
     * @param null $id_review
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Patient $patient, $id_review = null)
    {
        $review = new Review();
        if (!is_null($id_review)) {
            $review = Review::where('id', $id_review)->first();
        }

        $allReviews = $patient->reviews;

        return view('review/show', [
            'lang' => 'ca',
            'review' => $review,
            'pacient' => $patient,
            'reviews' => $allReviews,
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
    public function destroy($id, $id_review)
    {
        /**
         * @var $review Review
         */
        $review = Review::whereId($id_review)->first();
        $review->delete();

        Session::flash('alert', trans('models.Reviewmsgdeletedcorrectly'));
        Session::flash('status', 'success');

        return Redirect::route('valoracions.pacient.show', $id);
    }


    function clean_dates(&$input)
    {
        $date_names = array('date');

        foreach ($input as $key => $value):
            if (in_array($key, $date_names)):
                $input[$key] = date('d-m-Y', strtotime(str_replace('/', '-', $input[$key])));
            endif;
        endforeach;

    }
}
