<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class PublicProfileController extends Controller
{
    public function show(string $username): Response
    {
        $user = User::where('username', $username)
                    ->with(['links' => function ($query) {
                        $query->latest(); 
                    }])
                    ->firstOrFail();

        return Inertia::render('Public/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'bio' => $user->bio,
                'avatar' => $user->avatar,
                'links' => $user->links,
            ]
        ]);
    }
}