<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Llenamos los datos de texto (name, email, bio, username)
        $user->fill($request->validated());

        // 2. Verificamos si subió una nueva foto (avatar)
        if ($request->hasFile('avatar')) {
            // Borramos la foto anterior si existe (para no llenar el servidor de basura)
            if ($user->avatar) {
                // Esto asume que guardaste la ruta completa tipo "/storage/avatars/..."
                // Convertimos url a path relativo
                $oldPath = str_replace('/storage/', '', $user->avatar); 
                Storage::disk('public')->delete($oldPath);
            }

            // Guardamos la nueva foto en la carpeta 'avatars' dentro de 'public'
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Guardamos la URL pública en la base de datos
            $user->avatar = '/storage/' . $path;
        }

        // 3. Reseteamos verificación de email si lo cambió
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
