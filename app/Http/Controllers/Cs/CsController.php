<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CsController extends Controller
{
    public function consult()
    {
        return view ('Servicechat.consult');
    }
    public function CS()
    {
        return view ('Servicechat.CSchat');
    }
}
