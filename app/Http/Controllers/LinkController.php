<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LinkController extends Controller
{

    public function index()
    {
        return [1,2,3];
    }
}
