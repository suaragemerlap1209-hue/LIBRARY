@props(['title' => 'Lumina Library'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/leaf.png') }}">
    <style>
        :root {
            --green-deep:  #16331F;
            --green-mid:   #1F4429;
            --green-soft:  #EAF3DE;
            --cream:       #FAF7F0;
            --cream-dark:  #F0EBE0;
            --gold:        #C9973A;
            --gold-light:  #E8B36A;
            --ink:         #1A1A18;
            --muted:       #6B7564;
        }
        body { background: var(--cream); color: var(--ink); font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        #lp-nav { transition: background .3s, box-shadow .3s; }
        #lp-nav.scrolled { background: rgba(250,247,240,.96); backdrop-filter: blur(12px); box-shadow: 0 1px 0 rgba(0,0,0,.06); }
        .nav-link::after { content:''; display:block; height:1px; background:var(--green-deep); transform:scaleX(0); transition:transform .2s; transform-origin:left; }
        .nav-link:hover::after { transform:scaleX(1); }
        @stack('styles')
    </style>
</head>
<body class="overflow-x-hidden">

    <x-landing.navbar-landing />
    
    {{ $slot }}

    <x-landing.footer />

    <script>
        const lpNav = document.getElementById('lp-nav');
        if (lpNav) {
            window.addEventListener('scroll', () => {
                lpNav.classList.toggle('scrolled', window.scrollY > 30);
            }, { passive: true });
        }
    </script>

    @stack('scripts')
</body>
</html>