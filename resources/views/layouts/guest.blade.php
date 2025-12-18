<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NetFlow Analyzer') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-purple: #8B5CF6;
                --secondary-purple: #A78BFA;
                --accent-pink: #EC4899;
                --dark-bg: #0f0a1f;
                --darker-bg: #080510;
            }

            body {
                font-family: 'Inter', sans-serif;
            }

            .space-bg {
                background: linear-gradient(135deg, #0f0a1f 0%, #1a1035 30%, #0f0a1f 70%, #080510 100%);
                position: relative;
                overflow: hidden;
                min-height: 100vh;
            }

            .space-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset("images/space-bg.png") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                z-index: 0;
            }

            /* Stars animation */
            .stars {
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            .star {
                position: absolute;
                width: 2px;
                height: 2px;
                background: white;
                border-radius: 50%;
                animation: twinkle 3s infinite ease-in-out;
            }

            @keyframes twinkle {
                0%, 100% { opacity: 0.3; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.2); }
            }

            /* Planet glow effect */
            .planet {
                position: absolute;
                width: 350px;
                height: 350px;
                border-radius: 50%;
                background: radial-gradient(circle at 30% 30%, #3d2a5c 0%, #1a1035 50%, #0f0a1f 100%);
                left: -100px;
                top: 50%;
                transform: translateY(-50%);
                box-shadow:
                    inset -20px -20px 40px rgba(0,0,0,0.5),
                    0 0 60px rgba(139, 92, 246, 0.15);
                z-index: 1;
            }

            .glass-card {
                background: rgba(15, 10, 31, 0.7);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(139, 92, 246, 0.2);
                box-shadow:
                    0 25px 50px -12px rgba(0, 0, 0, 0.5),
                    0 0 0 1px rgba(139, 92, 246, 0.1) inset,
                    0 0 40px rgba(139, 92, 246, 0.05);
            }

            .input-group {
                position: relative;
            }

            .input-group input {
                width: 100%;
                padding: 16px 16px 16px 50px;
                border: 1px solid rgba(139, 92, 246, 0.3);
                border-radius: 12px;
                font-size: 16px;
                transition: all 0.3s ease;
                background: rgba(15, 10, 31, 0.6);
                color: #e5e7eb;
            }

            .input-group input::placeholder {
                color: #9ca3af;
            }

            .input-group input:focus {
                outline: none;
                border-color: var(--primary-purple);
                background: rgba(15, 10, 31, 0.8);
                box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
            }

            .input-group .icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                transition: color 0.3s ease;
            }

            .input-group input:focus + .icon,
            .input-group input:not(:placeholder-shown) + .icon {
                color: var(--primary-purple);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-purple), var(--accent-pink));
                color: white;
                padding: 16px 32px;
                border-radius: 12px;
                font-weight: 600;
                font-size: 16px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s ease;
            }

            .btn-primary:hover::before {
                left: 100%;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 30px -10px rgba(139, 92, 246, 0.5);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .checkbox-custom {
                appearance: none;
                width: 20px;
                height: 20px;
                border: 2px solid rgba(139, 92, 246, 0.4);
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                background: rgba(15, 10, 31, 0.6);
            }

            .checkbox-custom:checked {
                background: linear-gradient(135deg, var(--primary-purple), var(--accent-pink));
                border-color: var(--primary-purple);
            }

            .checkbox-custom:checked::after {
                content: '';
                position: absolute;
                left: 6px;
                top: 2px;
                width: 5px;
                height: 10px;
                border: solid white;
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }

            .link-purple {
                color: var(--secondary-purple);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }

            .link-purple:hover {
                color: var(--accent-pink);
            }

            .logo-container {
                animation: fadeInDown 0.8s ease-out;
            }

            .form-container {
                animation: fadeInUp 0.8s ease-out 0.2s both;
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Shooting star */
            .shooting-star {
                position: absolute;
                width: 100px;
                height: 2px;
                background: linear-gradient(90deg, white, transparent);
                right: 20%;
                bottom: 30%;
                transform: rotate(-45deg);
                animation: shoot 4s infinite ease-out;
                opacity: 0;
                z-index: 1;
            }

            @keyframes shoot {
                0% { opacity: 0; transform: translateX(0) translateY(0) rotate(-45deg); }
                10% { opacity: 1; }
                30% { opacity: 0; transform: translateX(-200px) translateY(200px) rotate(-45deg); }
                100% { opacity: 0; }
            }

            /* Constellation lines */
            .constellation {
                position: absolute;
                right: 10%;
                top: 15%;
                width: 150px;
                height: 150px;
                z-index: 1;
            }

            .constellation svg {
                stroke: rgba(255, 255, 255, 0.3);
                stroke-width: 1;
                fill: none;
            }
        </style>
    </head>
    <body class="space-bg">
        <!-- Decorative elements -->
        <div class="planet"></div>
        <div class="shooting-star"></div>

        <!-- Constellation -->
        <div class="constellation">
            <svg viewBox="0 0 100 100">
                <path d="M10 40 L40 20 L70 35 L60 70 L30 80 L10 40" />
                <circle cx="10" cy="40" r="2" fill="white" opacity="0.8"/>
                <circle cx="40" cy="20" r="2" fill="white" opacity="0.8"/>
                <circle cx="70" cy="35" r="2" fill="white" opacity="0.8"/>
                <circle cx="60" cy="70" r="2" fill="white" opacity="0.8"/>
                <circle cx="30" cy="80" r="2" fill="white" opacity="0.8"/>
            </svg>
        </div>

        <!-- Stars -->
        <div class="stars" id="stars"></div>

        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 relative z-10">
            <!-- Logo -->
            <div class="logo-container mb-8">
                <a href="/" class="block text-center">
                    <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-16 md:h-20 w-auto mx-auto" style="filter: brightness(0) invert(1);">
                </a>
                <p class="text-center text-purple-300 mt-2 text-sm tracking-wider uppercase">Network Traffic Analyzer</p>
            </div>

            <!-- Login Card -->
            <div class="glass-card w-full max-w-md rounded-2xl p-8 form-container">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-purple-300/60 text-sm">
                <p>&copy; {{ date('Y') }} MonetX. All rights reserved.</p>
            </div>
        </div>

        <script>
            // Generate random stars
            const starsContainer = document.getElementById('stars');
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 3 + 's';
                star.style.width = (Math.random() * 2 + 1) + 'px';
                star.style.height = star.style.width;
                starsContainer.appendChild(star);
            }
        </script>
    </body>
</html>
