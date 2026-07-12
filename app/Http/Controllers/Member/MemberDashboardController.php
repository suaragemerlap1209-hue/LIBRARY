<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        // NOTE: Data di bawah masih statis (dummy).
        // Ganti dengan query Eloquent (Member, Loan, Fine, Book) sesuai skema database Anda.

        $member = [
            'name'      => 'Julian Aethel',
            'role'      => 'Sastra Digital',
            'member_id' => 'AG-9921',
            'avatar'    => 'https://i.pravatar.cc/150?img=13',
        ];

        $today = 'Senin, 24 Mei 2024';

        $stats = [
            'borrowed' => [
                'total' => 4,
                'note'  => '2 buku baru minggu ini',
            ],
            'late' => [
                'total' => 1,
                'note'  => 'Segera kembalikan',
            ],
            'fine_total' => 15000,
        ];

        $loans = [
            [
                'title'       => 'Digital Renaissance',
                'author'      => 'Marcus Vane',
                'cover'       => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=200&q=80',
                'status'      => 'aktif',
                'borrowed_at' => '12 Mei',
                'due_at'      => '26 Mei',
            ],
            [
                'title'       => 'Ethical Algorithms',
                'author'      => 'Dr. Aris Thorne',
                'cover'       => 'https://images.unsplash.com/photo-1614332287897-cdc485fa562d?w=200&q=80',
                'status'      => 'terlambat',
                'borrowed_at' => '02 Mei',
                'due_at'      => '16 Mei',
            ],
            [
                'title'       => 'The Glass Library',
                'author'      => 'Elena Glass',
                'cover'       => 'https://images.unsplash.com/photo-1481487196290-c152efe083f5?w=200&q=80',
                'status'      => 'selesai',
                'returned_at' => '20 Mei 2024',
            ],
        ];

        $fine = [
            'total'     => 15000,
            'late_fee'  => 10000,
            'admin_fee' => 5000,
        ];

        $recommendations = [
            [
                'title'    => 'Cybernetic Dreams',
                'category' => 'Sastra Digital',
                'cover'    => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=200&q=80',
            ],
            [
                'title'    => 'The Last Archivist',
                'category' => 'Fiksi Ilmiah',
                'cover'    => 'https://images.unsplash.com/photo-1516339901601-2e1b62dc0c45?w=200&q=80',
            ],
        ];

        return view('member.dashboard', compact(
            'member', 'today', 'stats', 'loans', 'fine', 'recommendations'
        ));
    }
}