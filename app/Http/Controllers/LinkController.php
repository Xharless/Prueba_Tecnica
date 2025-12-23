<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Link; // <--- FALTABA ESTO (Para que funcione "Link $link")
use Illuminate\Support\Facades\Auth; // <--- FALTABA ESTO (Para usar "Auth::id()")

class LinkController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Profile/Links', [ // Asegúrate que tu vista Vue esté aquí
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

    public function edit(Link $link): Response
    {
        // Seguridad: Verificar que el link es del usuario
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Links/Edit', [
            'link' => $link,
        ]);
    }

    public function update(Request $request, Link $link)
    {
        // Seguridad: Verificar que el link es del usuario
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $link->update($validated);

        // Ojo: Usaste 'message' aquí, pero 'status' en store.
        // Te recomiendo usar siempre el mismo nombre para que el frontend lo detecte fácil.
        return back()->with('status', 'Link actualizado correctamente'); 
    }

    public function destroy(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        $link->delete();

        return back()->with('status', 'Link eliminado');
    }
}