<?php

use App\Livewire\Front\Home;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', Home::class)->name('home');
