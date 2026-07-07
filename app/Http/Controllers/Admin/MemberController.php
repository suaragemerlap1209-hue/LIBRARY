<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('member_id', 'like', '%' . $request->search . '%');
             });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(10);

        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')->where('status', 'active')->count();
        $suspendedMembers = User::where('role', 'member')->where('status', 'suspended')->count();
        $blockedMembers = User::where('role', 'member')->where('status', 'blocked')->count();

        return view('admin.member', compact(
            'members', 'totalMembers', 'activeMembers', 'suspendedMembers', 'blockedMembers'
        ));
    }

    public function show(User $member)
    {
        abort_if($member->role !== 'member', 404);

        return view('admin.member-detail', compact('member'));
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
}