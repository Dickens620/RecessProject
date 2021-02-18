<?php

namespace App\Http\Controllers;

use App\Models\Donation;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DonationsController extends Controller
{
    public $months = array(
        1=>"January",
        2=>"February",
        3=>"March",
        4=>"April",
        5=>"May",
        6=>"June",
        7=>"July",
        8=>"August",
        9=>"September",
        10=>"October",
        11=>"November",
        12=>"December"
    );
    //
    public function index(){
        return view('register.donation');
    }
    public function extract_month($string_date){
        $d = date_parse_from_format("Y-m-d", $string_date);
        return $this->months[$d['month']];
    }
    public function store(Request $request){

        
        //dd($request);
        DB::table('donations')->insert([
            'donor_name' =>$request->donor_name,
            'date' => $request->date,
            'amount' => $request->amount,
            'month'=>$this->extract_month($request->date)
        ]);
        return back()->withStatus(__('Donation successfully registered'));
    }
    public function chartsMonth(Request $request){
        dd("Here we go...");
        if(isset($request->month)){
            $month = $request->month;
        }
        else {
            $month = 'january';
        }
    }
    public function chart(Request $request){
        //dd($request);
        //$hh = explode('=',$request->requestUri);
        //dd($hh);
        if(isset($request->month)){
            $month = $request->month;
        }
        else {
            $month = 'january';
        }

   // dd($request);
       // dd($this->months);
       //$donations = Donation::where('month','=',$month)->get();
       $donations = Donation::where('month','=',$month)->get();
       //$months = D
       //dd($donations);
        return view('charts.donations_chart',compact('donations',$donations));
    }
    public function charts_months(Request $request){
        return view('charts.donations_months_chart');
    }
    public function show(Request $request){
        $donations = Donation::get();
        return view('tables.donations', compact('donations',$donations));
    }
}
