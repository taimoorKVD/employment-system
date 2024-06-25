<?php

use App\Components\TextInput;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('concept-behind-filament/text-input', function () {
    $input = TextInput::make('first_name')
        ->label('Enter User First Name');

    return view('welcome', [
        'input' => $input
    ]);
});


Route::get('/', function () {
    //return view('welcome');
    return redirect('admin/login');
});
