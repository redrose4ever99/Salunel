<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mainController extends Controller
{
    public function index(){

        $categories = DB::table('category_trans')->orderBy('id', 'DESC')->get();
        return  $categories;
        $staffs=DB::table('units')->get();
        return view('welcome', compact('categories','staffs'));


    }
    
}
