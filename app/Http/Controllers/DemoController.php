<?php

namespace App\Http\Controllers;

class DemoController extends Controller
{
    public function test()
    {
        return 'Hello Laravel Pint';
    }

    //  Bad formatted code (for testing Pint)
    public function badCode()
    {
        return 'this   is    badly formatted code';
    }

    //  Clean code
    public function cleanCode()
    {
        return 'This is clean code using Pint';
    }
}
