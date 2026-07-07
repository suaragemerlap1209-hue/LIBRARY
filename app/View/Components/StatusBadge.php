<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;

    public function __construct(string $status = 'active')
    {
        $this->status = $status;
    }

    public function badgeConfig(): array
    {
        return match ($this->status) {
            'active'    => ['label' => 'Aktif',        'bg' => '#EAF3DE', 'text' => '#3B6D11'],
            'suspended' => ['label' => 'Ditangguhkan', 'bg' => '#FDF3E3', 'text' => '#B9882F'],
            'blocked'   => ['label' => 'Diblokir',     'bg' => '#FCEBEB', 'text' => '#A32D2D'],
            default     => ['label' => ucfirst($this->status), 'bg' => '#F3F4F6', 'text' => '#6B7280'],
        };
    }

    public function render()
    {
        return view('components.status-badge', [
            'config' => $this->badgeConfig(),
        ]);
    }
}