<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Admin_Controller extends Controller
{
    public function admin() {
        return view('admin/product/list');
    }
    public function add_product() {
        return view('admin/product/add');
    }
    public function account() {
        return view('admin/account');
    }
}
