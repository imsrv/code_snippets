<?php
/* 
How to Generate Random Unique String in Laravel?
#ref https://www.itsolutionstuff.com/post/how-to-generate-random-unique-string-in-laravel-5example.html 
*/

// Syntax:
Str::random(number);
// OR
str_random(number);

// Example
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Str;
  
class GoogleController extends Controller
{
    public function index()
    {
        $randomString = Str::random(30);
        dd($randomString);
    }
}

/*  Output:
    RAXY4XmITwkoEfNnZcwBggjbeKfzwD
*/

