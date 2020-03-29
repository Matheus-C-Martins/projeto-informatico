<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VueController extends Controller {
    public function welcome() {
        return view('vue.welcome');
    }
}
