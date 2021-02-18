<?php

namespace App\Http\Controllers;

use App\Models\Patient;

use App\Models\Donation;

use App\Models\HealthOfficer;

use App\Models\Hospital;

use App\Models\Officer;

use App\Models\Payment;

use App\Models\Promotion;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //dd();
        $total  = array();
        $total['patients'] = Patient::count();
        $total['officers'] = Officer::count();
        $total['hospitals'] = Hospital::count();
        $total['promotions'] = Promotion::count();
        $total['donations'] = DB::select('SELECT sum(amount) as total from donations')[0];
        $total['payments'] = Payment::count();
       //dd($total);
        
        return view('dashboard',compact(['total',$total]));
    }
}
