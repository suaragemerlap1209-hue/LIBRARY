@props([
    'href' => '#',
    'label' => '',
    'active' => false
])

<a href="{{ $href }}"
   {{ $attributes->merge([
        'class' => 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ' .
        ($active
            ? 'bg-[#1D3B2C] text-white'
            : 'text-gray-500 hover:bg-gray-50')
   ]) }}>
    {{ $slot }}
    <span>{{ $label }}</span>
</a>