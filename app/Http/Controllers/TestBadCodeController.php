<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestBadCodeController extends Controller{
public function index(){
return "This   is   badly    formatted   code";
}
public function store(Request $request){
$data=$request->all();
return response()->json(['status'=>'ok']);
}
}