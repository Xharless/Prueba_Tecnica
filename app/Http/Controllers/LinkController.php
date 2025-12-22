<?php

namespace App\Http\Controllers; // 1. Define la carpeta donde vive

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
// La clase Controller base se importa automáticamente si estás en el mismo namespace,
// pero si te da problemas, puedes descomentar la siguiente línea:
// use App\Http\Controllers\Controller; 

class LinkController extends Controller // 2. Solo el nombre corto de la clase
{
    public function index(): Response
    {
        return Inertia::render('Profile/Links', [
            'links' => auth()->user()->links()->latest()->get(),
            'status' => session('status'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url',
        ]);

        $request->user()->links()->create($validated);

        return back()->with('status', '¡Enlace agregado con éxito!');
    }
}