<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class backendController extends Controller
{
    function dashboard(){
        return view('backend.dashboard');
    }
}
