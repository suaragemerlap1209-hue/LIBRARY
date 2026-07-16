<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
        public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'member'); // default tampilkan member

        $query = User::where('role', $roleFilter);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('member_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')->where('status', 'active')->count();
        $suspendedMembers = User::where('role', 'member')->where('status', 'suspended')->count();
        $blockedMembers = User::where('role', 'member')->where('status', 'blocked')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.member', compact(
            'members', 'totalMembers', 'activeMembers', 'suspendedMembers', 'blockedMembers', 'totalAdmins', 'roleFilter'
        ));
    }

    public function show(User $member)
    {
        $loans = $member->loans()
            ->with('book', 'fine')
            ->latest('created_at')
            ->get();

        $fines = \App\Models\Fine::whereHas('loan', fn ($q) => $q->where('user_id', $member->id))
            ->with('loan.book')
            ->latest()
            ->get();

        return view('admin.member-detail', compact('member', 'loans', 'fines'));
    }

   public function updateStatus(Request $request, User $member)
{
    $validated = $request->validate([
        'status' => 'required|in:active,suspended,blocked',
    ]);

    $member->update(['status' => $validated['status']]);
    
        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', 'Status anggota berhasil diperbarui.');
    }

    public function updateRole(Request $request, User $member)
    {
        if ($member->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri.');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $member->update(['role' => $validated['role']]);

        return redirect()
            ->route('admin.members.index')
            ->with('success', "Role {$member->name} berhasil diubah menjadi {$validated['role']}.");
    }

    public function edit(User $member)
{
    return view('admin.member-edit', compact('member'));
}

public function update(Request $request, User $member)
{
    $validated = $request->validate([
        'name'       => ['required', 'string', 'max:255'],
        'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $member->id],
        'birth_date' => ['required', 'date', 'before:today'],
        'address'    => ['nullable', 'string', 'max:255'],
        'status'     => ['required', 'in:active,suspended,blocked'],
        'max_loans'  => ['required', 'integer', 'min:1', 'max:20'],
        'expired_at' => ['nullable', 'date'],
    ]);

    $member->update($validated);

    return redirect()
        ->route('admin.members.show', $member)
        ->with('success', "Data {$member->name} berhasil diperbarui.");
}

}
