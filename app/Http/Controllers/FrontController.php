<?php

namespace App\Http\Controllers;

use App\BirthdaysNotification;
use App\ClinicalCourse;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests;
use App\Menu;
use App\Patient;
use App\Review;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    protected $catalanMonths = [
        "gen.",
        "febr.",
        "mar.",
        "abr.",
        "mai.",
        "jun.",
        "jul.",
        "ag.",
        "set.",
        "oct.",
        "nov.",
        "des."
    ];

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showGuestHome']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        setlocale(LC_TIME, 'ca_ES.utf8');

        if (ResetPasswordController::checkForPasswordExpiration()) return redirect('auth/reset_password');

        $birthdays = Patient::checkBirthdaysNotNotified();
        $birthdays_wo_check = Patient::checkBirthdays();

        $reviews = Review::select('patient_id')->groupBy('patient_id')->get();

        return view('front/index', [
            'lang' => 'ca',
            'title' => 'Pàgina principal',
            'birthdays' => $birthdays,
            'birthdays_wo_check' => $birthdays_wo_check,
            'stats_reviews' => $reviews
        ]);
    }

    public function getFormMenu()
    {
        $allMenu = Menu::all();
        $menus = [0 => ''];

        foreach ($allMenu as $menu) {
            $menus[$menu['id']] = $menu['title'];
        }
        return view('front/form_menu', [
            'lang' => 'ca',
            'menus' => $menus,
            'title' => 'Creació de les opcions de menú'
        ]);
    }

    public function postCreateMenu(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'url' => 'required'
        ]);

        Menu::create([
            'title' => $request->input('title'),
            'father_id' => $request->input('father_id')
        ]);

        Session::flash('alert-success', 'Menú creado correctamente');

        return redirect('crear_menus');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.show', [
            'lang' => 'ca',
            'title' => 'Crea nou usuari',
            'user' => new User()
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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ]);

        $inputs = $request->all();

        $user = User::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => bcrypt($inputs['password']),
        ]);

        if ($user->exists) {
            Session::flash('alert', 'Usuari creat correctament');
            Session::flash('status', 'success');
        }

        return redirect()->route('llistaUsers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $user = User::findOrFail($id);
        $inputs = Input::except('password');
        $user->fill($inputs);

        if ($user->save()) {
            Session::flash('alert', trans('messages.user_update_correctly'));
            Session::flash('status', 'success');

            return view('auth/show', [
                'lang' => 'ca',
                'title' => 'Dades del usuari: ' . $user->name,
                'user' => User::findOrFail($id)
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
        //
    }

    public function userList(Request $request)
    {
        $users = User::paginate(15);
        $users->setPath('llistaUsers');
        return view('auth.list', [
            'lang' => 'ca',
            'title' => 'Llista d\'usuaris',
            'users' => $users
        ]);
    }

    public function showUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('auth/show', [
            'lang' => 'ca',
            'title' => 'Dades del usuari: ' . $user->name,
            'user' => $user
        ]);
    }

    public function destroyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $users = User::paginate(15);
        $users->setPath('llistaUsers');

        Session::flash('alert', trans('messages.user_deleted_correctly'));
        Session::flash('status', 'success');

        return redirect()->route('llistaUsers');
    }

    public function showNextBirthdaysWoNotificationCheck()
    {
        $pacients = Patient::whereRaw("DATE_ADD(birth_date,
                INTERVAL YEAR(CURDATE())-YEAR(birth_date)
                         + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(birth_date),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->get();
        $pacientsBirthday = [];
        foreach ($pacients as $pacient) {
            $birthDate = explode('-', $pacient->birth_date);
            if ($birthDate[0] != '') {
                $pacientsBirthday[] = $pacient;
            }
        }
        setlocale(LC_TIME, 'ca_ES.utf8');
        return view('front.birthdays', [
            'title' => 'Llistat d\'aniversaris',
            'birthdays' => $pacientsBirthday,
            'no_check' => true
        ]);
    }

    public function showNextBirthdays()
    {
        $pacients = Patient::whereRaw("DATE_ADD(birth_date,
                INTERVAL YEAR(CURDATE())-YEAR(birth_date)
                         + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(birth_date),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)")
            ->whereNotIn('id', function ($query) {
                $query->select('patient_id')
                    ->from(with(new BirthdaysNotification())->getTable())
                    ->where('year', '=', date('Y'));
            })->get();
        $pacientsBirthday = [];
        foreach ($pacients as $pacient) {
            $birthDate = explode('-', $pacient->birth_date);
            if ($birthDate[0] != '') {
                $pacientsBirthday[] = $pacient;
            }
        }

        setlocale(LC_TIME, 'ca_ES.utf8');
        return view('front.birthdays', [
            'title' => 'Llistat d\'aniversaris',
            'birthdays' => $pacientsBirthday
        ]);
    }

    public function notifyBirthdays(Request $request)
    {
        $patient_ids = Input::get('patient_id');
        if (!is_null($patient_ids)) {
            foreach ($patient_ids as $id) {
                $bn = new BirthdaysNotification();
                $bn->patient_id = $id;
                $bn->year = date('Y');
                $bn->save();
            }
        }

        Session::flash('alert', trans('messages.birthdays_updated'));
        Session::flash('status', 'success');

        return redirect()->route('birthdaysList');
    }

    public function showGuestHome(Request $request)
    {
        return view('front.home_guest', ['foundPacient' => false, 'check' => true, 'mailSend' => false]);
    }

    public function convertReviewIntoClinicalCourse(Request $request)
    {
        DB::transaction(function () {
            $reviews = Review::all();

            $reviews->each(function (Review $review) {
                if (is_array($review->review)) {
                    $reviewContent = $review->review;
                } else {
                    $reviewContent = json_decode($review->review, true);
                }
                foreach ($reviewContent as $reviewJson) {
                    $clinicalCourse = new ClinicalCourse();
                    $clinicalCourse->content = $reviewJson['text'];
                    if ($this->strpos_array($reviewJson['date'], $this->catalanMonths)) {
                        $month = explode(' ', $reviewJson['date'])[1];
                        $date = str_replace($month, ($this->catalanMonthsEquivalence($month)), $reviewJson['date']);
                        $clinicalCourse->date = Carbon::createFromFormat('d M Y H:i', $date);
                    } else {
                        $clinicalCourse->date = Carbon::createFromFormat('d M Y H:i', $reviewJson['date']);
                    }

                    $clinicalCourse->patient_id = $review->patient->id;
                    $clinicalCourse->save();
                }
                $review->delete();
            });
        });

        return 'All working';
    }

    protected function catalanMonthsEquivalence($month)
    {
        $months = [
            "gen." => 'Jan',
            "febr." => 'Feb',
            "mar." => 'Mar',
            "abr." => 'Apr',
            "mai." => 'May',
            "jun." => 'Jun',
            "jul." => 'Jul',
            "ag." => 'Aug',
            "set." => 'Sep',
            "oct." => 'Oct',
            "nov." => 'Nov',
            "des." => 'Dec'
        ];

        return !is_null($months[$month]) ? $months[$month] : null;
    }

    protected function strpos_array($haystack, $needles, $offset = 0)
    {
        if (is_array($needles)) {
            foreach ($needles as $needle) {
                $pos = $this->strpos_array($haystack, $needle);
                if ($pos !== false) {
                    return $pos;
                }
            }
            return false;
        } else {
            return strpos($haystack, $needles, $offset);
        }
    }
}
