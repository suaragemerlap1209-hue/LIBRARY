@props(['title' => null, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-black/5']) }}>
    @if($title)
        <div class="px-6 pt-5 pb-3 border-b border-black/5">
            <h3 class="font-semibold text-[#1F2937]">{{ $title }}</h3>
        </div>
    @endif

    <div @if(!$noPadding) class="p-6" @endif>
        {{ $slot }}
    </div>
</div>