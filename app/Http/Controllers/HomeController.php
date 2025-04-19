<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{
    $userData = [
        'nombre' => 'Jair',
        'edad' => 21,
        'peso' => 84.7,
        'objetivo' => 'Peso',
        'workouts' => [
            'last_workout' => 'Construcción Muscular',
            'date' => '5 mar. 2025',
            'duration' => '27 seg',
            'volume' => '30 kg',
            'calories' => '15 Kcal'
        ]
    ];

    return view('home', compact('userData'));
}
}
