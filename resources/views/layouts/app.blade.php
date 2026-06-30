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
                @apply bg-white/80 backdrop-blur-2xl border border-white/40 shadow-soft rounded-4xl p-8 md:p-12 relative overflow-hidden;
            }
            .glass-card::before {
                content: '';
                @apply absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none;
            }
            .btn-primary {
                @apply bg-primary text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2;
            }
            .option-button {
                @apply p-6 rounded-2xl border-2 text-left transition-all duration-300 font-bold flex items-center justify-between group relative overflow-hidden;
            }
            .option-button-active {
                @apply border-primary bg-primary/5 text-primary shadow-lg shadow-primary/5;
            }
            .option-button-inactive {
                @apply border-slate-100 bg-white/50 hover:border-primary/30 hover:bg-white hover:shadow-md;
            }
            .step-indicator {
                @apply w-10 h-10 rounded-xl flex items-center justify-center font-black transition-all duration-500;
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
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-passport text-white text-xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black text-slate-900 tracking-tighter leading-none">{{ __('IMMIGRATION') }}</span>
                    <span class="text-[10px] font-bold text-secondary uppercase tracking-[0.2em] leading-none mt-1">{{ __('Consultant') }}</span>
                </div>
            </a>

            <div class="flex items-center gap-4">
                <!-- Language Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/50 hover:bg-white transition-colors border border-slate-200">
                        <span class="uppercase font-bold text-sm text-slate-700">{{ app()->getLocale() }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-32 glass rounded-2xl overflow-hidden border border-white/30 z-50">
                        <a href="?lang=en" class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-primary/5 transition-colors">English</a>
                        <a href="?lang=fa" class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-primary/5 transition-colors">فارسی</a>
                        <a href="?lang=ar" class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-primary/5 transition-colors">العربية</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-12 text-center text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-6">
            <p>Made with ❤️ by HERD AI</p>
            <div class="mt-4 flex justify-center gap-6">
                <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>
</html>