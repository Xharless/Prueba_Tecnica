<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Link; 
use Illuminate\Support\Facades\Auth;
class LinkController extends Controller
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

    public function edit(Link $link): Response
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Links/Edit', [
            'link' => $link,
        ]);
    }

    public function update(Request $request, Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $link->update($validated);

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