<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Patient;


use App\Models\Donation;

use Illuminate\Http\Request;

class PatientsController extends Controller
{
    //
    public function index(){

    }
    public function variation(Request $request){
        $general_officers = Donation::where('month','=','january')->get();
        //dd($general_officers);

        return view('charts.enrollment',compact('general_officers',$general_officers));
    }
    public function show(Request $request){
        //dd($request->toArray());
        $patients = Patient::get();
        return view('tables.patients', compact('patients',$patients));
    }
}
