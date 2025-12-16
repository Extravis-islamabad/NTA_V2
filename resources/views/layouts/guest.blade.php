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
                --primary-purple: #5548F5;
                --secondary-purple: #C843F3;
                --light-purple: #E4F2FF;
                --pink-accent: #F2C7FF;
                --dark-purple: #9619B5;
            }

            body {
                font-family: 'Inter', sans-serif;
            }

            .gradient-bg {
                background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
                position: relative;
                overflow: hidden;
            }

            .gradient-bg::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(85, 72, 245, 0.1) 0%, transparent 50%),
                            radial-gradient(circle at 80% 20%, rgba(200, 67, 243, 0.15) 0%, transparent 40%),
                            radial-gradient(circle at 20% 80%, rgba(150, 25, 181, 0.1) 0%, transparent 40%);
                animation: rotate 30s linear infinite;
            }

            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25),
                            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            }

            .floating-shapes {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 0;
            }

            .shape {
                position: absolute;
                border-radius: 50%;
                animation: float 20s infinite ease-in-out;
            }

            .shape-1 {
                width: 300px;
                height: 300px;
                background: linear-gradient(135deg, rgba(85, 72, 245, 0.3), rgba(200, 67, 243, 0.2));
                top: -100px;
                right: -100px;
                animation-delay: 0s;
            }

            .shape-2 {
                width: 200px;
                height: 200px;
                background: linear-gradient(135deg, rgba(200, 67, 243, 0.2), rgba(150, 25, 181, 0.3));
                bottom: -50px;
                left: -50px;
                animation-delay: -5s;
            }

            .shape-3 {
                width: 150px;
                height: 150px;
                background: linear-gradient(135deg, rgba(85, 72, 245, 0.2), rgba(228, 242, 255, 0.3));
                top: 50%;
                left: 10%;
                animation-delay: -10s;
            }

            .shape-4 {
                width: 100px;
                height: 100px;
                background: linear-gradient(135deg, rgba(242, 199, 255, 0.3), rgba(200, 67, 243, 0.2));
                bottom: 20%;
                right: 15%;
                animation-delay: -15s;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
                25% { transform: translateY(-30px) rotate(5deg) scale(1.05); }
                50% { transform: translateY(-15px) rotate(-5deg) scale(1); }
                75% { transform: translateY(-40px) rotate(3deg) scale(1.02); }
            }

            .input-group {
                position: relative;
            }

            .input-group input {
                width: 100%;
                padding: 16px 16px 16px 50px;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                font-size: 16px;
                transition: all 0.3s ease;
                background: #f9fafb;
            }

            .input-group input:focus {
                outline: none;
                border-color: var(--primary-purple);
                background: white;
                box-shadow: 0 0 0 4px rgba(85, 72, 245, 0.1);
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
                background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
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
                box-shadow: 0 10px 30px -10px rgba(85, 72, 245, 0.5);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .checkbox-custom {
                appearance: none;
                width: 20px;
                height: 20px;
                border: 2px solid #d1d5db;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            }

            .checkbox-custom:checked {
                background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
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
                color: var(--primary-purple);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }

            .link-purple:hover {
                color: var(--secondary-purple);
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

            .network-lines {
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 0;
                opacity: 0.1;
            }

            .network-lines svg {
                width: 100%;
                height: 100%;
            }
        </style>
    </head>
    <body class="gradient-bg min-h-screen">
        <!-- Floating shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <!-- Network lines background -->
        <div class="network-lines">
            <svg xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse">
                        <path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 relative z-10">
            <!-- Logo -->
            <div class="logo-container mb-8">
                <a href="/" class="block">
                    <img src="{{ asset('images/monetx-logo.png') }}" alt="MonetX" class="h-16 md:h-20 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden items-center justify-center text-white text-3xl font-bold">
                        <span class="text-purple-400">Monet</span><span class="text-purple-300">X</span>
                    </div>
                </a>
                <p class="text-center text-purple-200 mt-2 text-sm tracking-wider uppercase">Network Traffic Analyzer</p>
            </div>

            <!-- Login Card -->
            <div class="glass-card w-full max-w-md rounded-2xl p-8 form-container">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-purple-200 text-sm">
                <p>&copy; {{ date('Y') }} MonetX. All rights reserved.</p>
            </div>
        </div>

        <script>
            // Add interactive particle effect on mouse move
            document.addEventListener('mousemove', (e) => {
                const shapes = document.querySelectorAll('.shape');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;

                shapes.forEach((shape, index) => {
                    const speed = (index + 1) * 0.5;
                    const xOffset = (x - 0.5) * 20 * speed;
                    const yOffset = (y - 0.5) * 20 * speed;
                    shape.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });
            });
        </script>
    </body>
</html>
