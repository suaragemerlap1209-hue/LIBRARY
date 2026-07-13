<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberCardController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $initials = collect(explode(' ', $user->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');

        return view('member.card', [
            'user' => $user,
            'initials' => $initials,
        ]);
    }
}