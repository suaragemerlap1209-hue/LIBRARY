@props(['isEmpty' => false, 'emptyText' => 'Belum ada data.'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-black/5 p-6']) }}>

    @isset($filters)
        <div class="flex items-center justify-between gap-3 mb-5 flex-wrap">
            {{ $filters }}
        </div>
    @endisset

    @if($isEmpty)
        <div class="text-center py-14">
            <i class="fa-regular fa-folder-open text-3xl text-[#D1D5DB] mb-3 block"></i>
            <p class="text-sm text-[#9CA3AF]">{{ $emptyText }}</p>
        </div>
    @else
        <div class="overflow-x-auto">
            {{ $slot }}
        </div>
    @endif

    @isset($footer)
        <div class="mt-5">
            {{ $footer }}
        </div>
    @endisset
</div>