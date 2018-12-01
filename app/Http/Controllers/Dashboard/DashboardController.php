<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.01.
 * Time: 8:54
 */

namespace App\Http\Controllers\Dashboard;


use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('layouts.app');
    }

}