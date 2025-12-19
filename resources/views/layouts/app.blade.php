<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MonetX - NetFlow Analyzer') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // New MonetX Color Palette
                        monetx: {
                            // Backgrounds (darkest to lightest)
                            bg: {
                                page: '#0f0f1a',
                                card: '#1a1a2e',
                                input: '#242438',
                                hover: '#2d2d44',
                                active: '#363650'
                            },
                            // Primary Accent - Cyan
                            cyan: {
                                DEFAULT: '#22d3ee',
                                hover: '#06b6d4',
                                muted: 'rgba(34, 211, 238, 0.1)',
                                subtle: 'rgba(34, 211, 238, 0.05)'
                            },
                            // Secondary Accent - Light Indigo
                            indigo: {
                                DEFAULT: '#818cf8',
                                hover: '#a5b4fc',
                                muted: 'rgba(129, 140, 248, 0.1)'
                            },
                            // Semantic Colors
                            success: '#34d399',
                            warning: '#fbbf24',
                            error: '#f97316',
                            info: '#818cf8'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ============================================
           MONETX COLOR SYSTEM - CSS VARIABLES
           ============================================ */
        :root {
            /* Backgrounds */
            --bg-page: #0f0f1a;
            --bg-card: #1a1a2e;
            --bg-input: #242438;
            --bg-hover: #2d2d44;
            --bg-active: #363650;

            /* Primary Accent - Cyan */
            --accent-cyan: #22d3ee;
            --accent-cyan-hover: #06b6d4;
            --accent-cyan-muted: rgba(34, 211, 238, 0.1);
            --accent-cyan-subtle: rgba(34, 211, 238, 0.05);

            /* Secondary Accent - Light Indigo */
            --accent-indigo: #818cf8;
            --accent-indigo-hover: #a5b4fc;
            --accent-indigo-muted: rgba(129, 140, 248, 0.1);

            /* Semantic Colors */
            --color-success: #34d399;
            --color-success-muted: rgba(52, 211, 153, 0.1);
            --color-warning: #fbbf24;
            --color-warning-muted: rgba(251, 191, 36, 0.1);
            --color-error: #f97316;
            --color-error-muted: rgba(249, 115, 22, 0.1);
            --color-info: #818cf8;

            /* Text Colors */
            --text-primary: #f3f4f6;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --text-disabled: #6b7280;
            --text-placeholder: #4b5563;

            /* Borders */
            --border-default: #374151;
            --border-light: #2d2d44;
            --border-focus: #22d3ee;
        }

        /* ============================================
           BASE STYLES
           ============================================ */
        body {
            background: var(--bg-page);
            color: var(--text-secondary);
        }

        /* Space background */
        .space-bg {
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 30%, #0f0f1a 70%, #0a0a12 100%);
            min-height: 100vh;
            position: relative;
        }

        .space-bg::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/space-bg.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: 0;
            pointer-events: none;
        }

        /* Stars */
        .stars-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
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
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.8; }
        }

        /* ============================================
           GLASS MORPHISM CARDS
           ============================================ */
        .glass-card {
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-light);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
        }

        .glass-card-light {
            background: rgba(36, 36, 56, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-light);
        }

        /* Card with colored left border accent */
        .glass-card-accent {
            background: var(--bg-input);
            border-left: 3px solid var(--accent-cyan);
            border-radius: 0 8px 8px 0;
        }

        /* Card hover effect */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            border-color: var(--accent-cyan);
            box-shadow: 0 12px 40px rgba(34, 211, 238, 0.1);
        }

        /* ============================================
           NAVIGATION
           ============================================ */
        .nav-glass {
            background: rgba(15, 15, 26, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
        }

        /* ============================================
           FORM INPUTS
           ============================================ */
        .glass-input {
            background: var(--bg-input);
            border: 1px solid var(--border-default);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .glass-input::placeholder {
            color: var(--text-placeholder);
        }

        .glass-input:hover:not(:focus) {
            background: var(--bg-hover);
            border-color: #4b5563;
        }

        .glass-input:focus {
            outline: none;
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px var(--accent-cyan-muted);
        }

        .glass-input:disabled {
            background: var(--bg-page);
            color: var(--text-disabled);
            cursor: not-allowed;
        }

        .glass-input.input-error {
            border-color: var(--color-error);
            box-shadow: 0 0 0 3px var(--color-error-muted);
        }

        /* Select dropdown styling */
        select.glass-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        select.glass-input option {
            background: var(--bg-card);
            color: var(--text-primary);
            padding: 10px;
        }

        /* Checkbox styling */
        input[type="checkbox"].glass-checkbox {
            appearance: none;
            width: 18px;
            height: 18px;
            background: var(--bg-input);
            border: 1px solid var(--border-default);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        input[type="checkbox"].glass-checkbox:checked {
            background: var(--accent-cyan);
            border-color: var(--accent-cyan);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23000'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M5 13l4 4L19 7'/%3E%3C/svg%3E");
            background-size: 12px;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* ============================================
           BUTTONS
           ============================================ */
        /* Primary Button - Cyan */
        .btn-primary {
            background: var(--accent-cyan);
            color: #0f0f1a;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--accent-cyan-hover);
            box-shadow: 0 4px 20px rgba(34, 211, 238, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Secondary Button */
        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-default);
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            border-color: var(--accent-cyan);
            color: var(--accent-cyan);
            background: var(--accent-cyan-subtle);
        }

        /* Ghost Button */
        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-ghost:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        /* Danger Button */
        .btn-danger {
            background: var(--color-error);
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #ea580c;
            box-shadow: 0 4px 20px rgba(249, 115, 22, 0.3);
        }

        /* Legacy gradient button (for backwards compatibility) */
        .btn-gradient, .btn-monetx {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-indigo));
            color: #0f0f1a;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover, .btn-monetx:hover {
            box-shadow: 0 8px 25px rgba(34, 211, 238, 0.3);
            transform: translateY(-2px);
        }

        /* ============================================
           BADGES & PILLS
           ============================================ */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-cyan { background: var(--accent-cyan-muted); color: var(--accent-cyan); }
        .badge-indigo { background: var(--accent-indigo-muted); color: var(--accent-indigo); }
        .badge-success { background: var(--color-success-muted); color: var(--color-success); }
        .badge-warning { background: var(--color-warning-muted); color: var(--color-warning); }
        .badge-error { background: var(--color-error-muted); color: var(--color-error); }

        .badge-success-solid { background: var(--color-success); color: #0f0f1a; }
        .badge-error-solid { background: var(--color-error); color: white; }

        /* Protocol badges */
        .badge-tcp { background: var(--accent-cyan-muted); color: var(--accent-cyan); font-family: monospace; }
        .badge-udp { background: var(--accent-indigo-muted); color: var(--accent-indigo); font-family: monospace; }
        .badge-icmp { background: var(--color-success-muted); color: var(--color-success); font-family: monospace; }

        /* ============================================
           PROGRESS BARS
           ============================================ */
        .progress-track {
            background: var(--border-default);
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-indigo));
            transition: width 0.3s ease;
        }

        .progress-fill-success { background: var(--color-success); }
        .progress-fill-warning { background: var(--color-warning); }
        .progress-fill-error { background: var(--color-error); }

        /* ============================================
           TABLES
           ============================================ */
        .table-header {
            background: var(--bg-input);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-row {
            border-bottom: 1px solid var(--border-light);
            transition: background 0.15s ease;
        }

        .table-row:hover {
            background: var(--accent-cyan-subtle);
        }

        .table-row-alt:nth-child(even) {
            background: rgba(36, 36, 56, 0.3);
        }

        /* IP Address styling */
        .ip-address {
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
            color: var(--text-primary);
        }

        .ip-port {
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
            color: var(--text-muted);
            font-size: 0.875em;
        }

        /* ============================================
           SECTION HEADERS (No Gradients)
           ============================================ */
        .section-header {
            background: var(--bg-input);
            border-left: 3px solid var(--accent-cyan);
            padding: 16px 20px;
            border-radius: 0 8px 0 0;
        }

        .section-header-indigo {
            background: var(--bg-input);
            border-left: 3px solid var(--accent-indigo);
            padding: 16px 20px;
            border-radius: 0 8px 0 0;
        }

        .section-header-warning {
            background: var(--bg-input);
            border-left: 3px solid var(--color-warning);
            padding: 16px 20px;
            border-radius: 0 8px 0 0;
        }

        /* ============================================
           STICKY SAVE BUTTON
           ============================================ */
        .sticky-footer {
            position: sticky;
            bottom: 0;
            background: linear-gradient(to top, var(--bg-page) 0%, var(--bg-page) 60%, transparent 100%);
            padding: 20px 0;
            z-index: 10;
        }

        /* ============================================
           COLLAPSIBLE SECTIONS
           ============================================ */
        .collapsible-header {
            cursor: pointer;
            user-select: none;
            transition: background 0.2s ease;
        }

        .collapsible-header:hover {
            background: var(--bg-hover);
        }

        .collapsible-icon {
            transition: transform 0.3s ease;
        }

        .collapsible-open .collapsible-icon {
            transform: rotate(180deg);
        }

        /* ============================================
           STATUS INDICATORS
           ============================================ */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-dot-online {
            background: var(--color-success);
            animation: pulse-status 2s infinite;
        }

        .status-dot-offline {
            background: var(--color-error);
        }

        .status-dot-warning {
            background: var(--color-warning);
        }

        @keyframes pulse-status {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.4); }
            50% { opacity: 0.8; box-shadow: 0 0 0 4px rgba(52, 211, 153, 0); }
        }

        /* ============================================
           APEXCHARTS DARK THEME OVERRIDES
           ============================================ */
        .apexcharts-tooltip {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-default) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;
        }

        .apexcharts-tooltip-title {
            background: var(--bg-input) !important;
            border-bottom: 1px solid var(--border-light) !important;
            color: var(--text-muted) !important;
        }

        .apexcharts-xaxistooltip, .apexcharts-yaxistooltip {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-default) !important;
            color: var(--text-primary) !important;
        }

        .apexcharts-legend-text {
            color: var(--text-muted) !important;
        }

        .apexcharts-text tspan {
            fill: var(--text-muted);
        }

        .apexcharts-gridline {
            stroke: var(--bg-hover);
        }

        .apexcharts-datalabels-group text {
            fill: var(--text-primary) !important;
        }

        /* ============================================
           LEAFLET MAP DARK THEME
           ============================================ */
        .traffic-map {
            height: 350px;
            min-height: 300px;
            border-radius: 8px;
            z-index: 1;
        }

        .leaflet-container {
            background: var(--bg-card);
            border-radius: 8px;
        }

        /* ============================================
           SCROLLBAR
           ============================================ */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-page);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bg-hover);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--border-default);
        }

        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.4s ease-out forwards;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }

        @keyframes dataUpdate {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        .data-updating {
            animation: dataUpdate 0.5s ease-in-out;
        }

        /* Pulse glow for alerts */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
            50% { box-shadow: 0 0 20px 5px rgba(249, 115, 22, 0.15); }
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        /* ============================================
           INTERACTIVE ELEMENTS
           ============================================ */
        .interactive-row {
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .interactive-row:hover {
            background: var(--accent-cyan-subtle);
            transform: translateX(2px);
        }

        .link-underline {
            position: relative;
        }

        .link-underline::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: currentColor;
            transition: width 0.2s ease;
        }

        .link-underline:hover::after {
            width: 100%;
        }

        /* ============================================
           ACCESSIBILITY - FOCUS STATES
           ============================================ */
        *:focus-visible {
            outline: 2px solid var(--accent-cyan);
            outline-offset: 2px;
        }

        button:focus-visible, a:focus-visible {
            outline: 2px solid var(--accent-cyan);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px var(--accent-cyan-muted);
        }

        /* Skip link for keyboard navigation */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--accent-cyan);
            color: var(--bg-page);
            padding: 8px 16px;
            z-index: 100;
            transition: top 0.3s;
        }

        .skip-link:focus {
            top: 0;
        }

        /* ============================================
           GRADIENT TEXT (Legacy)
           ============================================ */
        .gradient-text {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-indigo));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-primary {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-indigo));
        }

        .gradient-secondary {
            background: linear-gradient(135deg, var(--color-success), #059669);
        }

        /* ============================================
           METRIC CARDS
           ============================================ */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 30px rgba(34, 211, 238, 0.1);
            transform: translateY(-2px);
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================
           EMPTY STATES
           ============================================ */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--accent-cyan-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .empty-state-success .empty-state-icon {
            background: var(--color-success-muted);
        }
    </style>
</head>

<body class="space-bg font-sans antialiased">
    <!-- Skip link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Stars background -->
    <div class="stars-container" id="stars"></div>

    <div class="min-h-screen relative z-10">
        <!-- Navigation -->
        <nav class="nav-glass sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-10 w-auto" style="filter: brightness(0) invert(1);">
                            </a>
                        </div>
                        <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-cyan-400 text-cyan-400' : 'border-transparent text-gray-400 hover:text-cyan-400 hover:border-cyan-400/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('devices.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('devices.*') ? 'border-cyan-400 text-cyan-400' : 'border-transparent text-gray-400 hover:text-cyan-400 hover:border-cyan-400/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                                Inventory
                            </a>
                            <a href="{{ route('alarms.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('alarms.*') ? 'border-cyan-400 text-cyan-400' : 'border-transparent text-gray-400 hover:text-cyan-400 hover:border-cyan-400/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                Alarms
                            </a>
                            <a href="{{ route('reports.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('reports.*') ? 'border-cyan-400 text-cyan-400' : 'border-transparent text-gray-400 hover:text-cyan-400 hover:border-cyan-400/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Reports
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('settings.*') ? 'border-cyan-400 text-cyan-400' : 'border-transparent text-gray-400 hover:text-cyan-400 hover:border-cyan-400/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Settings
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden lg:flex items-center gap-2 text-xs text-gray-400 whitespace-nowrap">
                            <span class="status-dot status-dot-online"></span>
                            <span id="currentTime">{{ now()->format('M d H:i') }}</span>
                        </div>

                        <!-- User Dropdown -->
                        @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/5 transition">
                                <div class="w-8 h-8 gradient-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-200">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 glass-card rounded-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-white/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                <hr class="my-1 border-white/10">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main id="main-content" class="py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="nav-glass border-t border-white/5 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-6 w-auto" style="filter: brightness(0) invert(1); opacity: 0.5;">
                        <span class="text-gray-600">|</span>
                        <span>NetFlow Traffic Analyzer v2.0</span>
                    </div>
                    @php
                        $collectorIp = \App\Models\Setting::get('collector_ip');
                        $netflowPort = \App\Models\Setting::get('netflow_port');
                    @endphp
                    <div class="flex items-center gap-4">
                        @if($collectorIp && $netflowPort)
                        <span class="flex items-center gap-2">
                            <span class="status-dot status-dot-online"></span>
                            <span>Collector: {{ $collectorIp }}:{{ $netflowPort }}</span>
                        </span>
                        @else
                        <span class="flex items-center gap-2 text-amber-400">
                            <span class="status-dot status-dot-warning"></span>
                            Collector: <a href="{{ route('settings.index') }}" class="underline hover:text-amber-300">Configure in Settings</a>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>

    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- MonetX Chart Colors and Helpers -->
    <script>
        // MonetX Chart Color Palette - Cyan-focused
        window.monetxColors = {
            primary: '#22d3ee',      // Cyan
            secondary: '#818cf8',    // Light Indigo
            tertiary: '#34d399',     // Emerald
            quaternary: '#fbbf24',   // Amber
            fifth: '#f97316',        // Orange
            sixth: '#a78bfa',        // Light Purple
            seventh: '#2dd4bf',      // Teal
            eighth: '#fb7185',       // Rose
            success: '#34d399',
            warning: '#fbbf24',
            danger: '#f97316',
            info: '#818cf8'
        };

        // Chart color array for sequential use
        window.chartColors = [
            '#22d3ee',  // Cyan
            '#818cf8',  // Light Indigo
            '#34d399',  // Emerald
            '#fbbf24',  // Amber
            '#f97316',  // Orange
            '#a78bfa',  // Light Purple
            '#2dd4bf',  // Teal
            '#fb7185',  // Rose
            '#60a5fa',  // Blue
            '#9ca3af'   // Gray (for Unknown/Other)
        ];

        // Default ApexCharts options with dark theme
        window.apexDefaultOptions = {
            chart: {
                fontFamily: 'Inter, ui-sans-serif, system-ui, sans-serif',
                background: 'transparent',
                foreColor: '#9ca3af',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: window.chartColors,
            theme: {
                mode: 'dark'
            },
            tooltip: {
                theme: 'dark',
                style: { fontSize: '12px' }
            },
            grid: {
                borderColor: '#2d2d44',
                strokeDashArray: 4
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px',
                labels: {
                    colors: '#9ca3af'
                }
            },
            dataLabels: {
                enabled: false  // Disable overlapping data labels by default
            }
        };

        // Helper function to format bytes - always shows MB or GB, never KB
        window.formatBytes = function(bytes) {
            if (bytes === null || bytes === undefined || bytes === 0) return '0 MB';
            bytes = parseInt(bytes);
            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        };

        // Helper function to format bandwidth
        window.formatBandwidth = function(bps) {
            if (bps === null || bps === undefined) return '0 bps';
            bps = parseInt(bps);
            if (bps >= 1000000000) return (bps / 1000000000).toFixed(2) + ' Gbps';
            if (bps >= 1000000) return (bps / 1000000).toFixed(2) + ' Mbps';
            if (bps >= 1000) return (bps / 1000).toFixed(2) + ' Kbps';
            return bps + ' bps';
        };

        // Generate stars
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.getElementById('stars');
            if (starsContainer) {
                for (let i = 0; i < 80; i++) {
                    const star = document.createElement('div');
                    star.className = 'star';
                    star.style.left = Math.random() * 100 + '%';
                    star.style.top = Math.random() * 100 + '%';
                    star.style.animationDelay = Math.random() * 3 + 's';
                    star.style.width = (Math.random() * 2 + 1) + 'px';
                    star.style.height = star.style.width;
                    starsContainer.appendChild(star);
                }
            }
        });
    </script>

    <script>
        // Update time every second
        setInterval(() => {
            const now = new Date();
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                const hours = now.getHours().toString().padStart(2, '0');
                const mins = now.getMinutes().toString().padStart(2, '0');
                const secs = now.getSeconds().toString().padStart(2, '0');
                timeElement.textContent = `${hours}:${mins}:${secs}`;
            }
        }, 1000);
    </script>

    @stack('scripts')
</body>

</html>
