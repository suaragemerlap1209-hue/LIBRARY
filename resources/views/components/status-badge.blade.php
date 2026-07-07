@switch($status)
    @case('active')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#EAF3DE] text-[#3B6D11]">
            {{ $config['label'] }}
        </span>
        @break

    @case('suspended')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FDF3E3] text-[#B9882F]">
            {{ $config['label'] }}
        </span>
        @break

    @case('blocked')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FCEBEB] text-[#A32D2D]">
            {{ $config['label'] }}
        </span>
        @break

    @default
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#F3F4F6] text-[#6B7280]">
            {{ $config['label'] }}
        </span>
@endswitch