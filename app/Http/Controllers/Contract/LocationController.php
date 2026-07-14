<?php

namespace App\Http\Controllers;

use App\Models\Department; // Nombre del modelo cambiado
use App\Models\District; // Nombre del modelo cambiado
use Illuminate\Http\Request;

class LocationController extends Controller // Nombre del controlador cambiado
{
    public function index()
    {
        $departments = Department::all(); // Nombre del modelo cambiado
        return view('location', compact('departments')); // Nombre de la vista cambiado
    }

    public function getDistricts(Department $department) // Nombre del modelo cambiado
    {
        $districts = $department->districts; // Nombre del modelo cambiado
        return response()->json($districts);
    }
}
