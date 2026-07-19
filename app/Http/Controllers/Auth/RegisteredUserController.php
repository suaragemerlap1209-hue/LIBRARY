<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'birth_date' => ['required', 'date', 'before:today'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $birthDate = Carbon::parse($request->birth_date);
        $age = $birthDate->age;

        if ($age < 13) {
            throw ValidationException::withMessages([
                'birth_date' => 'Pendaftaran anggota minimal berusia 13 tahun.',
            ]);
        }

        $maxLoans = $age <= 16 ? 3 : 6;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $birthDate,
            'role' => 'member',
            'max_loans' => $maxLoans,
            'status' => 'active',
            'expired_at' => now()->addYears(3),
        ]);
                event(new Registered($user));
                Auth::login($user);

                // UBAH INI:
                return redirect()->route('member.dashboard');

                // JADI INI:
                return redirect()->route('verification.notice');
    }
}