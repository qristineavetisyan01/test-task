<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/leads');

Route::resource('leads', LeadController::class)->except(['show']);
