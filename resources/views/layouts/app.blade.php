<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Immigration Consultant') }} | AI-Powered Assessment</title>
    
    <!-- Meta Tags -->
    <meta name="description" content="Discover your best immigration options in minutes. AI-powered assessment for global immigration paths.">
    <meta property="og:title" content="AI Immigration Consultant">
    <meta property="og:description" content="Personalized immigration paths analyzed by AI. Fast, accurate, and free.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Vazirmatn:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d47a1',
                        secondary: '#00bcd4',
                        background: '#f8fafc',
                        success: '#10b981',
                        danger: '#f43f5e',
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        vazir: ['Vazirmatn', 'sans-serif'],
                    },
                    borderRadius: {
                        '4xl': '2.5rem',
                        '2xl': '1.5rem',
                    },
                    boxShadow: {
                        'soft': '0 10px 30px -10px rgba(13, 71, 161, 0.1)',
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-background text-slate-500 font-inter antialiased selection:bg-primary selection:text-white;
            }
            [dir="rtl"] body {
                @apply font-vazir;
            }
            h1, h2, h3, h4, h5, h6 {
                @apply text-slate-900 font-extrabold tracking-tight;
            }
        }
        @layer components {
            .glass {
                @apply bg-white/70 backdrop-blur-xl border border-white/30 shadow-soft;
            }
            .glass-card {
                @apply bg-white/80 backdrop-blur-2xl border border-white/40 shadow-soft rounded-2xl md:rounded-3xl p-4 md:p-12 relative overflow-hidden;
            }
            .glass-card::before {
                content: '';
                @apply absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none;
            }
            .btn-primary {
                @apply bg-primary text-white px-6 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm md:text-base;
            }
            .option-button {
                @apply p-4 md:p-6 rounded-xl md:rounded-2xl border-2 text-left transition-all duration-300 font-bold flex items-center justify-between group relative overflow-hidden text-sm md:text-base;
            }
            .option-button-active {
                @apply border-primary bg-primary/5 text-primary shadow-lg shadow-primary/5;
            }
            .option-button-inactive {
                @apply border-slate-100 bg-white/50 hover:border-primary/30 hover:bg-white hover:shadow-md;
            }
            .step-indicator {
                @apply w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl flex items-center justify-center font-bold transition-all duration-500 text-sm md:text-base;
            }
        }
        @media (max-width: 640px) {
            .glass-card {
                @apply rounded-none !p-6 border-x-0;
            }
            .btn-primary {
                @apply w-full py-4 text-base rounded-xl;
            }
            .option-button {
                @apply w-full py-5 text-base rounded-xl;
            }
            body {
                @apply text-sm bg-white;
            }
            main {
                @apply !px-0 !py-0;
            }
            h1 {
                @apply text-3xl px-4;
            }
            h2 {
                @apply text-2xl px-4;
            }
            h3 {
                @apply text-xl px-4;
            }
            .mobile-full-width {
                @apply !max-w-none !w-full !px-0;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 20s linear infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden">
    <!-- Decorative Background Elements -->
    <div class="fixed -top-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl -z-10"></div>
    <div class="fixed -bottom-24 -right-24 w-96 h-96 bg-secondary/5 rounded-full blur-3xl -z-10"></div>
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/2 rounded-full blur-[120px] -z-10"></div>

    <!-- Header -->
    <header class="sticky top-0 z-50 glass border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-16 md:h-24 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 md:gap-4 group">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 group-hover:rotate-12 transition-transform duration-500">
                    <i class="fa-solid fa-passport text-white text-xl md:text-2xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl md:text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ __('IMMIGRATION') }}</span>
                    <span class="text-[10px] md:text-[11px] font-bold text-secondary uppercase tracking-[0.3em] leading-none mt-1.5">{{ __('Consultant') }}</span>
                </div>
            </a>

            <div class="flex items-center gap-2 md:gap-4">
                <!-- Language Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" 
                            class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-2 rounded-xl bg-white/80 hover:bg-white transition-all border border-slate-200 shadow-sm active:scale-95">
                        <i class="fa-solid fa-globe text-primary text-sm"></i>    
                        <span class="uppercase font-black text-[10px] md:text-xs text-slate-700 tracking-wider">{{ app()->getLocale() }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 mt-3 w-40 glass rounded-2xl overflow-hidden border border-white/40 z-50 shadow-xl py-1">
                        <a href="?lang=en" class="flex items-center justify-between px-4 py-3 text-xs font-bold text-slate-700 hover:bg-primary/5 transition-colors {{ app()->getLocale() == 'en' ? 'text-primary bg-primary/5' : '' }}">
                            <span>English</span>
                            <template x-if="'{{ app()->getLocale() }}' == 'en'"><i class="fa-solid fa-check text-[10px]"></i></template>
                        </a>
                        <div class="h-px bg-slate-100/50 mx-2"></div>
                        <a href="?lang=fa" class="flex items-center justify-between px-4 py-3 text-xs font-bold text-slate-700 hover:bg-primary/5 transition-colors {{ app()->getLocale() == 'fa' ? 'text-primary bg-primary/5' : '' }}">
                            <span>فارسی</span>
                            <template x-if="'{{ app()->getLocale() }}' == 'fa'"><i class="fa-solid fa-check text-[10px]"></i></template>
                        </a>
                        <div class="h-px bg-slate-100/50 mx-2"></div>
                        <a href="?lang=ar" class="flex items-center justify-between px-4 py-3 text-xs font-bold text-slate-700 hover:bg-primary/5 transition-colors {{ app()->getLocale() == 'ar' ? 'text-primary bg-primary/5' : '' }}">
                            <span>العربية</span>
                            <template x-if="'{{ app()->getLocale() }}' == 'ar'"><i class="fa-solid fa-check text-[10px]"></i></template>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-12 md:py-20 text-center relative">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex flex-col items-center gap-6">
                <div class="w-12 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
                <div class="flex flex-col gap-2">
                    <p class="text-slate-400 text-sm font-medium tracking-wide">
                        {{ __('© 2024 Herd AI. All rights reserved.') }}
                    </p>
                    <p class="text-slate-300 text-[11px] font-bold uppercase tracking-[0.3em]">
                        Made with ❤️ by Zarwan
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>