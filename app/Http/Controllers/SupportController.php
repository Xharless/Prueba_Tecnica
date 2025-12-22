<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'supporter_name' => 'required|string|max:255', 
            'message' => 'nullable|string|max:500',        
            'amount' => 'required|numeric|min:100|max:500000', 
        ]);

        // 2. Creamos el apoyo vinculado a ESTE usuario
        // Laravel usa la relación 'supports()' que definimos en el Modelo User
        $user->supports()->create($validated);

        // 3. Regresamos con un mensaje de éxito
        return back()->with('success', '¡Muchas gracias por tu apoyo! ☕');
    }
}