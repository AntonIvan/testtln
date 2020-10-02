<?php

namespace App\Http\Controllers;

use App\HandlerInn\HandlerInn;
use Illuminate\Http\Request;

class HandlerInnController extends Controller
{
    function check(Request $request) {
        return resolve(HandlerInn::class)->checkInn($request->inn);
    }
}
