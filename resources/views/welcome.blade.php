@extends('layouts.app')

@section('content')
<div x-data="eligibilityChecker()" class="max-w-6xl mx-auto px-4 sm:px-6 animate-fade-in">
    <!-- Header Hero Section -->
    <div class="text-center mb-8 md:mb-16" x-show="step === 0" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform -translate-y-8" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 text-primary text-xs md:text-sm font-bold mb-4 md:mb-8">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            {{ __('AI-Powered Assessment') }}
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-7xl font-black mb-3 md:mb-6 tracking-tight">
            {{ __('Eligibility Checker') }}
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed px-2">
            {{ __('Discover your best immigration options in minutes.') }}
            {{ __('Our AI analyzes your profile to suggest the most suitable paths.') }}
        </p>
    </div>

    <!-- Main Container -->
    <div id="main-content-area" class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-8 items-start">
        
        <!-- Left Side: Form / Status -->
        <div class="lg:col-span-7 space-y-4 md:space-y-8">
            <!-- Error Alert -->
            <div x-show="errorMessage" x-transition:enter="transition ease-out duration-300" 
                 class="p-4 md:p-6 bg-danger/10 border border-danger/20 rounded-2xl md:rounded-3xl flex items-start gap-3 md:gap-4 animate-fade-in">
                <i class="fa-solid fa-circle-exclamation text-danger text-lg md:text-xl mt-1"></i>
                <div class="flex-grow">
                    <h4 class="text-danger font-black text-sm mb-1">{{ __('Error Occurred') }}</h4>
                    <p class="text-xs text-danger/80 font-medium" x-text="errorMessage"></p>
                </div>
                <button @click="errorMessage = ''" class="text-danger hover:scale-110 transition-transform">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Step-by-Step Form -->
            <div class="glass-card !p-6 md:!p-12" x-show="step > 0 && step <= totalSteps" x-transition:enter="transition ease-out duration-300">
                <!-- Progress Header -->
                <div class="flex items-center justify-between mb-6 md:mb-12" x-show="!isFinalStepSelected()">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-slate-900" x-text="currentStepTitle()"></h2>
                        <p class="text-xs md:text-sm text-slate-500 mt-1">{{ __('Please select the option that best describes you.') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xl md:text-2xl font-black text-primary" x-text="step + '/' + totalSteps"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Step') }}</span>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4" x-show="!isFinalStepSelected()">
                    <!-- Step 1: Age -->
                    <template x-if="step === 1">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in ageOptions">
                                <button @click="formData.age = option; nextStep()" 
                                        :class="formData.age === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-4 md:!p-6">
                                    <span x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 2: Education -->
                    <template x-if="step === 2">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in educationOptions">
                                <button @click="formData.education = option; nextStep()" 
                                        :class="formData.education === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-4 md:!p-6">
                                    <span x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 3: Work Experience -->
                    <template x-if="step === 3">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in workOptions">
                                <button @click="formData.work = option; nextStep()" 
                                        :class="formData.work === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-4 md:!p-6">
                                    <span x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 4: Language -->
                    <template x-if="step === 4">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in languageOptions">
                                <button @click="formData.language = option; nextStep()" 
                                        :class="formData.language === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-4 md:!p-6">
                                    <span x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 5: Capital -->
                    <template x-if="step === 5">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in capitalOptions">
                                <button @click="formData.capital = option; nextStep()" 
                                        :class="formData.capital === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-4 md:!p-6">
                                    <span x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 6: Goal -->
                    <template x-if="step === 6">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in goalOptions">
                                <button @click="formData.goal = option; nextStep()" 
                                        :class="formData.goal === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-4 md:!p-6">
                                    <span x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Final AI Call State -->
                <div x-show="isFinalStepSelected()" x-transition:enter="transition ease-out duration-500" class="text-center py-6 md:py-8">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-primary/10 text-primary rounded-2xl md:rounded-3xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <i class="fa-solid fa-wand-magic-sparkles text-2xl md:text-3xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black mb-2">{{ __('All set!') }}</h3>
                    <p class="text-slate-500 mb-6 md:mb-8">{{ __('We have all the information needed to generate your assessment.') }}</p>
                </div>

                <!-- Form Navigation -->
                <div class="mt-8 md:mt-12 flex items-center justify-between border-t border-slate-100 pt-6 md:pt-8">
                    <button @click="prevStep()" class="flex items-center gap-2 text-slate-400 font-bold hover:text-primary transition-all" x-show="step > 1 && !loading">
                        <i class="fa-solid fa-arrow-left rtl:rotate-180"></i>
                        {{ __('Previous') }}
                    </button>
                    <div class="flex-grow"></div>
                    <button @click="calculateResults()" class="btn-primary" x-show="isFinalStepSelected()" :disabled="loading">
                        <span x-show="!loading">{{ __('Get AI Assessment') }}</span>
                        <i x-show="loading" class="fa-solid fa-circle-notch animate-spin"></i>
                    </button>
                </div>
            </div>

            <!-- Start State (Hero CTA) -->
            <div class="glass-card text-center !p-8 md:!p-16" x-show="step === 0">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-primary/10 text-primary rounded-3xl md:rounded-4xl flex items-center justify-center mx-auto mb-6 md:mb-8">
                    <i class="fa-solid fa-rocket text-3xl md:text-4xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-black mb-3 md:mb-4">{{ __('Ready to explore?') }}</h2>
                <p class="text-slate-500 mb-6 md:mb-10">{{ __('Answer a few questions and get your personalized plan.') }}</p>
                <button @click="step = 1" class="btn-primary text-lg md:text-xl px-8 md:px-12">
                    {{ __('Start Assessment') }}
                </button>
            </div>

            <!-- Loading State -->
            <div class="glass-card text-center !p-12 md:!p-20" x-show="loading">
                <div class="relative w-24 h-24 md:w-32 md:h-32 mx-auto mb-6 md:mb-8">
                    <div class="absolute inset-0 border-4 border-primary/10 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-t-primary rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fa-solid fa-brain text-3xl md:text-4xl text-primary animate-pulse"></i>
                    </div>
                </div>
                <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-2">{{ __('Analyzing your profile...') }}</h3>
                <p class="text-slate-500">{{ __('Our AI is evaluating the best immigration paths for you.') }}</p>
            </div>

            <!-- Results Section -->
            <div class="space-y-8" x-show="step > totalSteps && !loading" x-transition:enter="transition ease-out duration-500">
                <!-- Main Result Card -->
                <div class="glass-card">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-success/10 text-success rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-check-double text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">{{ __('Your Assessment Result') }}</h3>
                            <p class="text-sm text-slate-500">{{ __('Based on AI analysis of your profile.') }}</p>
                        </div>
                    </div>
                    
                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 leading-relaxed text-lg" x-text="results.reason"></p>
                    </div>

                    <!-- Alternative Plan B -->
                    <div class="mt-8 p-6 bg-primary/5 rounded-3xl border border-primary/10 flex items-start gap-4" x-show="results.alternative">
                        <i class="fa-solid fa-lightbulb text-primary text-xl mt-1"></i>
                        <div>
                            <h5 class="text-sm font-black text-primary uppercase tracking-widest mb-1">{{ __('Alternative Plan B') }}</h5>
                            <p class="text-slate-700 font-medium leading-relaxed" x-text="results.alternative"></p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-amber-50 rounded-3xl border border-amber-100 flex items-start gap-4">
                        <i class="fa-solid fa-circle-exclamation text-amber-500 text-xl mt-1"></i>
                        <p class="text-sm text-amber-700 font-medium leading-relaxed">
                            {{ __('Disclaimer') }}: {{ __('This is a preliminary assessment; the final decision rests with the specialist consultant.') }}
                        </p>
                    </div>
                </div>

                <!-- Detailed Breakdown Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass-card !p-8">
                        <h4 class="text-lg font-black mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-earth-americas text-secondary"></i>
                            {{ __('Suitable Countries') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="country in results.countries">
                                <span class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 font-bold text-sm" x-text="country"></span>
                            </template>
                        </div>
                    </div>

                    <div class="glass-card !p-8">
                        <h4 class="text-lg font-black mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-route text-primary"></i>
                            {{ __('Immigration Paths') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="path in results.paths">
                                <span class="px-4 py-2 rounded-xl bg-primary text-white font-bold text-sm" x-text="__(path)"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Gaps & Improvements -->
                <div class="glass-card !p-8">
                    <h4 class="text-lg font-black mb-6 flex items-center gap-3 text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{ __('Gaps & Requirements') }}
                    </h4>
                    <div class="space-y-4">
                        <template x-for="gap in results.gaps">
                            <div class="flex items-start gap-3 p-4 bg-danger/5 border border-danger/10 rounded-2xl">
                                <i class="fa-solid fa-circle-arrow-up text-danger mt-1"></i>
                                <p class="text-sm text-slate-700 font-medium" x-text="gap"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Final CTAs -->
                <div class="flex flex-col md:flex-row gap-4">
                    <button class="btn-primary flex-1 py-5 text-lg shadow-xl shadow-primary/20">
                        <i class="fa-solid fa-calendar-check mr-2 rtl:ml-2"></i>
                        {{ __('Book Consultation') }}
                    </button>
                    <div class="flex-1 flex gap-2">
                        <button @click="shareOnWhatsApp()" class="flex-1 py-5 px-4 rounded-2xl font-black border-2 border-emerald-100 text-emerald-600 text-center hover:bg-emerald-50 hover:border-emerald-500 transition-all flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                            <span class="text-sm">{{ __('WhatsApp') }}</span>
                        </button>
                        <button @click="shareOnTelegram()" class="flex-1 py-5 px-4 rounded-2xl font-black border-2 border-sky-100 text-sky-600 text-center hover:bg-sky-50 hover:border-sky-500 transition-all flex items-center justify-center gap-2">
                            <i class="fa-brands fa-telegram text-xl"></i>
                            <span class="text-sm">{{ __('Telegram') }}</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button @click="copyResultsToClipboard()" class="py-4 px-4 rounded-2xl font-bold border border-slate-200 text-slate-500 text-center hover:bg-white hover:border-primary hover:text-primary transition-all flex items-center justify-center gap-2 text-xs">
                        <i class="fa-regular fa-copy"></i>
                        {{ __('Copy Report') }}
                    </button>
                    <button @click="downloadResultsAsTxt()" class="py-4 px-4 rounded-2xl font-bold border border-slate-200 text-slate-500 text-center hover:bg-white hover:border-secondary hover:text-secondary transition-all flex items-center justify-center gap-2 text-xs">
                        <i class="fa-solid fa-download"></i>
                        {{ __('Download PDF/TXT') }}
                    </button>
                </div>

                <div class="text-center pt-4">
                    <button @click="resetForm()" class="text-slate-400 hover:text-primary font-bold transition-all flex items-center gap-2 mx-auto">
                        <i class="fa-solid fa-rotate-left"></i>
                        {{ __('Start Over') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Profile Summary / Info -->
        <div class="lg:col-span-5 space-y-6 sticky top-28">
            <!-- Profile Summary Card -->
            <div class="glass-card !p-8 overflow-hidden relative">
                <div class="absolute -right-12 -top-12 w-32 h-32 bg-primary/5 rounded-full blur-2xl"></div>
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black flex items-center gap-3">
                        <i class="fa-solid fa-user-gear text-slate-400"></i>
                        {{ __('Profile Summary') }}
                    </h3>
                    <button @click="resetForm()" class="text-[10px] font-bold text-primary hover:bg-primary/5 px-3 py-1.5 rounded-lg border border-primary/20 transition-all flex items-center gap-1">
                        <i class="fa-solid fa-pen-to-square"></i>
                        {{ __('Edit Profile') }}
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/30">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Age') }}</span>
                        <span class="font-black text-slate-900" x-text="formData.age ? __(formData.age) : '—'"></span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/30">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Education') }}</span>
                        <span class="font-black text-slate-900" x-text="formData.education ? __(formData.education) : '—'"></span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/30">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Work') }}</span>
                        <span class="font-black text-slate-900" x-text="formData.work ? __(formData.work) : '—'"></span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/30">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Language') }}</span>
                        <span class="font-black text-slate-900" x-text="formData.language ? __(formData.language) : '—'"></span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/30">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Capital') }}</span>
                        <span class="font-black text-slate-900" x-text="formData.capital ? __(formData.capital) : '—'"></span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/50 rounded-2xl border border-white/30">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Goal') }}</span>
                        <span class="font-black text-slate-900" x-text="formData.goal ? __(formData.goal) : '—'"></span>
                    </div>
                </div>
            </div>

            <!-- Global Status Badge -->
            <div class="glass-card !p-6 bg-slate-900 !text-white relative overflow-hidden">
                <i class="fa-solid fa-globe absolute -right-4 -bottom-4 text-8xl opacity-10 animate-spin-slow"></i>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-bold uppercase tracking-widest opacity-70">{{ __('AI Consultant Online') }}</span>
                </div>
                <p class="text-sm mt-3 font-medium leading-relaxed">
                    {{ __('Our AI model is trained on latest global immigration policies to give you the most accurate starting point.') }}
                </p>
            </div>

            <!-- Recent Assessments History -->
            <div class="glass-card !p-8" x-init="fetchHistory()">
                <h3 class="text-xl font-black mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                    {{ __('Recent Assessments') }}
                </h3>
                <div class="space-y-3">
                    <template x-for="item in history">
                        <div class="p-4 bg-white/50 rounded-2xl border border-white/30 hover:border-primary/20 transition-all cursor-default">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] font-black text-primary uppercase tracking-tighter" x-text="item.formData.goal"></span>
                                <span class="text-[10px] text-slate-400" x-text="formatDate(item.timestamp)"></span>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <template x-for="country in item.results.countries.slice(0, 2)">
                                    <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded-md font-bold text-slate-600" x-text="country"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="history.length === 0">
                        <p class="text-xs text-slate-400 text-center py-4">{{ __('No history yet.') }}</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
function eligibilityChecker() {
    return {
        step: 0,
        totalSteps: 6,
        loading: false,
        errorMessage: '',
        history: [],
        formData: {
            age: '',
            education: '',
            work: '',
            language: '',
            capital: '',
            goal: ''
        },
        ageOptions: ['Under 18', '18-24', '25-35', '36-45', '45+'],
        educationOptions: ['High School', "Bachelor's", "Master's", 'PhD'],
        workOptions: ['Less than 1 year', '1-3 years', '3-5 years', '5-10 years', '10+ years'],
        languageOptions: ['Beginner', 'Intermediate', 'Advanced', 'Native'],
        capitalOptions: ['Less than $10k', '$10k-$50k', '$50k-$100k', '$100k-$500k', '$500k+'],
        goalOptions: ['Work Opportunity', 'Higher Education', 'Investment', 'Quality of Life'],
        results: {
            countries: [],
            paths: [],
            reason: '',
            gaps: []
        },
        translations: @json(json_decode(file_get_contents(base_path('lang/'.app()->getLocale().'.json')), true)),
        
        __(key) {
            return this.translations[key] || key;
        },

        isFinalStepSelected() {
            return this.step === this.totalSteps && this.formData.goal !== '';
        },

        async fetchHistory() {
            try {
                const response = await fetch('{{ route("get.eligibility.history") }}');
                if (response.ok) {
                    this.history = await response.json();
                }
            } catch (e) { console.error(e); }
        },

        formatDate(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
        },

        currentStepTitle() {
            const titles = {
                1: this.__('Age'),
                2: this.__('Education'),
                3: this.__('Work Experience'),
                4: this.__('Language Proficiency'),
                5: this.__('Capital'),
                6: this.__('Immigration Goal')
            };
            return titles[this.step] || '';
        },

        isStepComplete() {
            const currentField = ['age', 'education', 'work', 'language', 'capital', 'goal'][this.step - 1];
            return this.formData[currentField] !== '';
        },

        nextStep() {
            if (this.step < this.totalSteps) {
                if (!this.isStepComplete()) {
                    this.errorMessage = this.__('Please select an option before proceeding.');
                    return;
                }
                this.errorMessage = '';
                this.step++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.step > 1) {
                this.errorMessage = '';
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        resetForm() {
            this.step = 0;
            this.formData = { age: '', education: '', work: '', language: '', capital: '', goal: '' };
            this.results = { countries: [], paths: [], reason: '', gaps: [] };
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async calculateResults() {
            if (!this.isStepComplete()) {
                this.errorMessage = this.__('Please select an option before proceeding.');
                return;
            }
            this.errorMessage = '';
            this.loading = true;
            
            // Smooth scroll to results/loading section
            setTimeout(() => {
                const resultsSection = document.getElementById('main-content-area');
                if (resultsSection) {
                    window.scrollTo({
                        top: resultsSection.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            }, 100);

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 60000); // 60 seconds timeout

            try {
                const response = await fetch('{{ route("get.ai.suggestion") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        formData: this.formData
                    }),
                    signal: controller.signal // Attach the abort signal
                });

                clearTimeout(timeoutId); // Clear the timeout if the fetch completes in time

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(errorText || 'AI Service Unavailable');
                }

                this.results = await response.json();
                
                // Save history in background
                await fetch('{{ route("save.eligibility") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        formData: this.formData,
                        results: this.results
                    })
                });

                // Refresh history list
                await this.fetchHistory();

                // Trigger Confetti
                this.triggerConfetti();

                this.step = this.totalSteps + 1;
            } catch (error) {
                console.error(error);
                if (error.name === 'AbortError') {
                    this.errorMessage = this.__('The AI service took too long to respond. Please try again.');
                } else if (error.message.includes('Too Many Requests')) {
                    this.errorMessage = this.__('Too many requests. Please wait a minute.');
                } else {
                    try {
                        const errorResponse = JSON.parse(error.message);
                        if (errorResponse.raw_ai_content) {
                            this.errorMessage = this.__('AI returned malformed JSON. Raw content:') + ' ' + errorResponse.raw_ai_content;
                        } else if (errorResponse.raw_ai_response) {
                            this.errorMessage = this.__('Invalid AI Response. Raw response:') + ' ' + JSON.stringify(errorResponse.raw_ai_response);
                        } else {
                            this.errorMessage = this.__('Something went wrong with the AI service. Please try again.');
                        }
                    } catch (e) {
                        this.errorMessage = this.__('Something went wrong with the AI service. Please try again.');
                    }
                }
            } finally {
                this.loading = false;
            }
        },

        triggerConfetti() {
            const count = 200;
            const defaults = {
                origin: { y: 0.7 },
                colors: ['#0d47a1', '#00bcd4', '#10b981']
            };

            function fire(particleRatio, opts) {
                confetti({
                    ...defaults,
                    ...opts,
                    particleCount: Math.floor(count * particleRatio)
                });
            }

            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2, { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            fire(0.1, { spread: 120, startVelocity: 45 });
        },

        shareOnWhatsApp() {
            const text = encodeURIComponent(this.getFormattedResults());
            window.open(`https://wa.me/?text=${text}`, '_blank');
        },

        shareOnTelegram() {
            const text = encodeURIComponent(this.getFormattedResults());
            window.open(`https://t.me/share/url?url=${window.location.href}&text=${text}`, '_blank');
        },

        copyResultsToClipboard() {
            const text = this.getFormattedResults();
            navigator.clipboard.writeText(text).then(() => {
                alert(this.__('Results copied to clipboard!'));
            });
        },

        downloadResultsAsTxt() {
            const text = this.getFormattedResults();
            const blob = new Blob([text], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `immigration-assessment-${new Date().getTime()}.txt`;
            a.click();
            window.URL.revokeObjectURL(url);
        },

        getFormattedResults() {
            return `
IMMIGRATION ASSESSMENT REPORT
-----------------------------
Profile:
- Age: ${this.formData.age}
- Education: ${this.formData.education}
- Work Experience: ${this.formData.work}
- Language: ${this.formData.language}
- Capital: ${this.formData.capital}
- Goal: ${this.formData.goal}

Recommended Countries:
${this.results.countries.join(', ')}

Immigration Paths:
${this.results.paths.join(', ')}

Analysis:
${this.results.reason}

Gaps & Advice:
${this.results.gaps.map(g => `- ${g}`).join('\n')}

Plan B:
${this.results.alternative}
-----------------------------
Disclaimer: This is a preliminary assessment.
            `.trim();
        }
    }
}
</script>

<style>
    /* Custom styles to match the sample look */
    .prose p {
        @apply mb-4;
    }
    
    [dir="rtl"] .option-button i {
        @apply rotate-180;
    }
</style>
@endsection