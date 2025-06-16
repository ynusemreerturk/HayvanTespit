<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Admin\Controller;
use App\Models\Product;

class HomepageController extends Controller
{
    public function __construct() {
        view()->share('products');
    }

    public function index() {
        return view('front.homepage');
    }

    public function about() {
        return view('front.about');
    }


}
