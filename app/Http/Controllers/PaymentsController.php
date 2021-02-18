<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Officer;

use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    public function pay_month($month){

    }
    public function show(){
        $available_money= DB::table('donations')->sum('amount');
        $balance = $this->make_payments($available_money);
        //then pay bonuses
        $this->pay_bonuses($balance);
    
        //$this->make_payments($available_money);
        //$users = DB::table('users')->get();
        $officers = Officer::all();
        
        return view('tables.payments',compact('officers',$officers));
        //$balance = $this->make_payments(200000000);
        //then pay bonuses
        //$this->pay_bonuses($balance);
    }
    public function pay_category($all_people, $amount_per_person, $db_table_name){
        foreach($all_people as $person){
            //$category = $person->job_role;
            //dd($category);
            //dd($person);
            DB::table($db_table_name)
            ->where('job_role','=',$person->job_role)
            ->update(['salary'=>$amount_per_person]);
        }
    }

    public function bonus_category($all_people, $amount_per_person, $db_table_name){
        foreach($all_people as $person){
            //$category = $person->job_role;
            //dd($category);
            //dd($person);
            DB::table($db_table_name)
            ->where('job_role','=',$person->job_role)
            ->increment('salary',$amount_per_person);
        }
    }

    public function pay_bonuses($money){

        $director_bonus = 0.05*$money;
        $superintendent_bonus = 0.5*$director_bonus;
        $admin_bonus = (3/4)*$superintendent_bonus;
        $officer_bonus = (8/5)*$admin_bonus;
        $senior_bonus = $officer_bonus + (0.06)*$officer_bonus;
        $head_bonus = $officer_bonus + (0.035)*$officer_bonus;

       //get all officers at general officers
        $all_general_officers = DB::table('officers')->where('job_role','general')->get();
        $total = count($all_general_officers);
        $this->bonus_category($all_general_officers,$officer_bonus,'officers');
        $money = $money - ($total*$officer_bonus);

        //get all senior officers and pay them the basic salary.
        $all_senior_officers = DB::table('officers')->where('job_role','senior')->get();
        $total = count($all_senior_officers);
        $this->bonus_category($all_senior_officers,$senior_bonus,'officers');
        $money = $money - ($total*$senior_bonus);

        //for the heads at general hospitals
        $all_head_officers = DB::table('officers')->where('job_role','head')->get();
        $total = count($all_head_officers);
        $this->bonus_category($all_head_officers,$head_bonus,'officers');
        $money = $money - ($total*$head_bonus);

        //for superintendent_basic_salary
        $all_superintendents = DB::table('officers')->where('job_role','superintendent')->get();
        $total = count($all_superintendents);
        $this->bonus_category($all_superintendents,$superintendent_bonus,'officers');
        $money = $money - ($total*$superintendent_bonus);

        //For the admin
        $all_admins = DB::table('users')->where('job_role','admin')->get();
        $total = count($all_admins);
        $this->bonus_category($all_admins,$admin_bonus,'users');
        $money = $money - ($total*$admin_bonus);

        //For the directors
        $all_directors = DB::table('users')->where('job_role','director')->get();
        $total = count($all_directors);
        $this->bonus_category($all_directors,$director_bonus,'users');
        $money = $money - ($total*$director_bonus);

        $all_users = DB::table('users')->get();
        $all_officers = DB::table('officers')->get();
        //dd([$all_users,$all_officers]);
        //$all_officers = DB::table('officers')->get();
        return $money;
        
    }


    public function make_payments($money){
        $director_basic_salary = 5000000;
        $superintendent_basic_salary = 0.5*$director_basic_salary;
        $admin_basic_salary = (3/4)*$superintendent_basic_salary;
        $officer_basic_salary = (8/5)*$admin_basic_salary;
        $senior_basic_salary = $officer_basic_salary + (0.06)*$officer_basic_salary;
        $head_basic_salary = $officer_basic_salary + (0.035)*$officer_basic_salary;

        if($money<=100000000){
            //No payments to be made
        }
        else{

            //get all officers at general officers
            $all_general_officers = DB::table('officers')->where('job_role','general')->get();
            $total = count($all_general_officers);
            $this->pay_category($all_general_officers,$officer_basic_salary,'officers');
            $money = $money - ($total*$officer_basic_salary);

            //get all senior officers and pay them the basic salary.
            $all_senior_officers = DB::table('officers')->where('job_role','senior')->get();
            $total = count($all_senior_officers);
            $this->pay_category($all_senior_officers,$senior_basic_salary,'officers');
            $money = $money - ($total*$senior_basic_salary);

            //for the heads at general hospitals
            $all_head_officers = DB::table('officers')->where('job_role','head')->get();
            $total = count($all_head_officers);
            $this->pay_category($all_head_officers,$head_basic_salary,'officers');
            $money = $money - ($total*$head_basic_salary);

            //for superintendent_basic_salary
            $all_superintendents = DB::table('officers')->where('job_role','superintendent')->get();
            $total = count($all_superintendents);
            $this->pay_category($all_superintendents,$superintendent_basic_salary,'officers');
            $money = $money - ($total*$superintendent_basic_salary);

            //For the admin
            $all_admins = DB::table('users')->where('job_role','admin')->get();
            $total = count($all_admins);
            $this->pay_category($all_admins,$admin_basic_salary,'users');
            $money = $money - ($total*$admin_basic_salary);

            //For the directors
            $all_directors = DB::table('users')->where('job_role','director')->get();
            $total = count($all_directors);
            $this->pay_category($all_directors,$director_basic_salary,'users');
            $money = $money - ($total*$director_basic_salary);

            $all_users = DB::table('users')->get();
            $all_officers = DB::table('officers')->get();
            //dd([$all_users,$all_officers]);
            //$all_officers = DB::table('officers')->get();
            return $money;
           

        }
    }
}
