@extends('layouts.app')

@section('content')
<div x-data="eligibilityChecker('{{ env('CONSULTANT_WHATSAPP') }}')" class="max-w-6xl mx-auto px-4 sm:px-6 animate-fade-in mobile-full-width">
    <!-- Header Hero Section -->
    <div class="text-center mb-8 md:mb-16 mt-8 md:mt-0" x-show="step === 0" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform -translate-y-8" x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 text-primary text-xs md:text-sm font-bold mb-4 md:mb-8">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            {{ __('AI-Powered Assessment') }}
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-7xl font-black mb-3 md:mb-6 tracking-tight">
            {{ __('Eligibility Checker') }}
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed px-6">
            {{ __('Discover your best immigration options in minutes.') }}
            {{ __('Our AI analyzes your profile to suggest the most suitable paths.') }}
        </p>
    </div>

    <!-- Main Container -->
    <div id="main-content-area" class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
        
        <!-- Left Side: Form / Status -->
        <div class="lg:col-span-7 space-y-4 md:space-y-8 order-1">
            <!-- Error Alert -->
            <div x-show="errorMessage" x-transition:enter="transition ease-out duration-300" 
                 class="p-5 md:p-6 bg-danger/5 border border-danger/10 rounded-2xl md:rounded-3xl flex items-start gap-3 md:gap-4 mx-4 md:mx-0 animate-fade-in">
                <i class="fa-solid fa-circle-exclamation text-danger text-lg md:text-xl mt-0.5"></i>
                <div class="flex-grow">
                    <h4 class="text-danger font-black text-xs md:text-sm mb-1">{{ __('Error Occurred') }}</h4>
                    <p class="text-[10px] md:text-xs text-danger/80 font-bold leading-relaxed" x-text="errorMessage"></p>
                </div>
                <button @click="errorMessage = ''" class="text-danger/50 hover:text-danger transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Step-by-Step Form -->
            <div class="glass-card !p-8 md:!p-12" x-show="step > 0 && step <= totalSteps" x-transition:enter="transition ease-out duration-300">
                <!-- Visual Progress Bar -->
                <div class="absolute top-0 left-0 w-full h-1 bg-slate-100 overflow-hidden" x-show="!isFinalStepSelected()">
                    <div class="h-full bg-primary transition-all duration-500 ease-out" 
                         :style="`width: ${(step / totalSteps) * 100}%`"
                         :class="step === totalSteps ? 'bg-success' : ''">
                    </div>
                </div>

                <!-- Progress Header -->
                <div class="flex items-center justify-between mb-8 md:mb-12 px-2 md:px-0" x-show="!isFinalStepSelected()">
                    <div class="flex-grow">
                        <h2 class="text-xl md:text-3xl font-black text-slate-900 leading-tight" x-text="currentStepTitle()"></h2>
                        <p class="text-[11px] md:text-sm text-slate-500 mt-2 font-medium">{{ __('Please select the option that best describes you.') }}</p>
                    </div>
                    <div class="text-right shrink-0 ml-4 rtl:mr-4 rtl:ml-0">
                        <span class="block text-2xl md:text-4xl font-black text-primary leading-none" x-text="step + '/' + totalSteps"></span>
                        <span class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2 block">{{ __('Step') }}</span>
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
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 2: Marital Status -->
                    <template x-if="step === 2">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in maritalOptions">
                                <button @click="formData.marital_status = option; nextStep()" 
                                        :class="formData.marital_status === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 3: Occupation -->
                    <template x-if="step === 3">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in occupationOptions">
                                <button @click="formData.occupation = option; nextStep()" 
                                        :class="formData.occupation === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 4: Education -->
                    <template x-if="step === 4">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in educationOptions">
                                <button @click="formData.education = option; nextStep()" 
                                        :class="formData.education === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 5: Work Experience -->
                    <template x-if="step === 5">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in workOptions">
                                <button @click="formData.work = option; nextStep()" 
                                        :class="formData.work === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 6: Language -->
                    <template x-if="step === 6">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in languageOptions">
                                <button @click="formData.language = option; nextStep()" 
                                        :class="formData.language === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 7: Language Score -->
                    <template x-if="step === 7">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in languageScoreOptions">
                                <button @click="formData.language_score = option; nextStep()" 
                                        :class="formData.language_score === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 8: Current Residence -->
                    <template x-if="step === 8">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in residenceOptions">
                                <button @click="formData.current_residence = option; nextStep()" 
                                        :class="formData.current_residence === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 9: Target Region -->
                    <template x-if="step === 9">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in targetOptions">
                                <button @click="formData.target_region = option; nextStep()" 
                                        :class="formData.target_region === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 10: Visa Refusal -->
                    <template x-if="step === 10">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in refusalOptions">
                                <button @click="formData.visa_refusal = option; nextStep()" 
                                        :class="formData.visa_refusal === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 11: Capital -->
                    <template x-if="step === 11">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in capitalOptions">
                                <button @click="formData.capital = option; nextStep()" 
                                        :class="formData.capital === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 12: Dependents -->
                    <template x-if="step === 12">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in dependentsOptions">
                                <button @click="formData.dependents = option; nextStep()" 
                                        :class="formData.dependents === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Step 13: Goal -->
                    <template x-if="step === 13">
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <template x-for="option in goalOptions">
                                <button @click="formData.goal = option; nextStep()" 
                                        :class="formData.goal === option ? 'option-button-active' : 'option-button-inactive'"
                                        class="option-button group !p-5 md:!p-6">
                                    <span class="truncate" x-text="__(option)"></span>
                                    <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all rtl:rotate-180"></i>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Final AI Call State -->
                <div x-show="isFinalStepSelected()" x-transition:enter="transition ease-out duration-500" class="text-center py-4 md:py-8">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-primary/10 text-primary rounded-2xl md:rounded-3xl flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <i class="fa-solid fa-wand-magic-sparkles text-2xl md:text-3xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black mb-2">{{ __('All set!') }}</h3>
                    <p class="text-slate-500 mb-4 md:mb-8 px-4">{{ __('We have all the information needed to generate your assessment.') }}</p>
                </div>

                <!-- Form Navigation -->
                <div class="mt-8 md:mt-12 flex items-center justify-between border-t border-slate-100 pt-6 md:pt-8 gap-4">
                    <button @click="prevStep()" class="flex items-center gap-2 text-slate-400 font-bold hover:text-primary transition-all text-sm md:text-base" x-show="step > 1 && !loading">
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
            <div class="glass-card text-center !p-10 md:!p-20" x-show="step === 0">
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
            <div class="glass-card text-center !p-8 md:!p-20" x-show="loading">
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
            <div class="space-y-6 md:space-y-8 pb-20" x-show="step > totalSteps && !loading" x-transition:enter="transition ease-out duration-500">
                <!-- Main Result Card -->
                <div class="glass-card !p-8 md:!p-16 relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-success/5 rounded-full blur-3xl"></div>
                    
                    <div class="flex flex-col md:flex-row md:items-center gap-6 mb-12">
                        <div class="w-20 h-20 bg-success/10 text-success rounded-[2rem] flex items-center justify-center shrink-0 shadow-inner">
                            <i class="fa-solid fa-check-double text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl md:text-4xl font-black text-slate-900 mb-2">{{ __('Your Assessment Result') }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-success"></span>
                                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">{{ __('Expert AI Analysis') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 leading-[1.8] text-xl font-medium italic" x-text="results.reason"></p>
                    </div>

                    <!-- Alternative Plan B -->
                    <div class="mt-8 p-6 md:p-8 bg-slate-900 rounded-[2rem] border border-slate-800 flex items-start gap-4 md:gap-6 relative overflow-hidden" x-show="results.alternative">
                        <div class="absolute right-0 top-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
                        <div class="w-12 h-12 rounded-2xl bg-primary/20 text-primary flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-lightbulb text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-2">{{ __('Alternative Plan B') }}</h5>
                            <p class="text-slate-300 font-medium leading-relaxed text-lg" x-text="results.alternative"></p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 md:p-6 bg-amber-50 rounded-3xl border border-amber-100 flex items-start gap-4">
                        <i class="fa-solid fa-circle-exclamation text-amber-500 text-xl mt-0.5"></i>
                        <p class="text-[11px] md:text-sm text-amber-700 font-bold leading-relaxed">
                            {{ __('Disclaimer') }}: {{ __('This is a preliminary assessment; the final decision rests with the specialist consultant.') }}
                        </p>
                    </div>
                </div>

                <!-- Detailed Breakdown Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div class="glass-card !p-6 md:!p-8 relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/5 rounded-full blur-2xl group-hover:bg-secondary/10 transition-all"></div>
                        <h4 class="text-lg font-black mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-earth-americas text-secondary animate-pulse"></i>
                            {{ __('Suitable Countries') }}
                        </h4>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="country in results.countries">
                                <div class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-50 border border-slate-100 text-slate-700 font-bold text-sm hover:border-secondary/30 hover:bg-white hover:shadow-lg hover:shadow-secondary/5 transition-all cursor-default">
                                    <i class="fa-solid fa-location-dot text-secondary/50 text-xs"></i>
                                    <span x-text="country"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="glass-card !p-6 md:!p-8 relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all"></div>
                        <h4 class="text-lg font-black mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-route text-primary"></i>
                            {{ __('Immigration Paths') }}
                        </h4>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="path in results.paths">
                                <div class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-primary text-white font-bold text-sm hover:scale-105 hover:shadow-lg hover:shadow-primary/20 transition-all cursor-default">
                                    <i class="fa-solid fa-passport text-white/50 text-xs"></i>
                                    <span x-text="__(path)"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Gaps & Requirements -->
                <div class="glass-card !p-6 md:!p-8 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-danger/5 rounded-full blur-2xl group-hover:bg-danger/10 transition-all"></div>
                    <h4 class="text-lg font-black mb-6 flex items-center gap-3 text-danger">
                        <i class="fa-solid fa-triangle-exclamation animate-bounce"></i>
                        {{ __('Gaps & Requirements') }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="gap in results.gaps">
                            <div class="flex items-start gap-3 p-5 bg-danger/[0.02] border border-danger/5 rounded-3xl hover:bg-danger/[0.05] hover:border-danger/20 transition-all group/item">
                                <div class="w-8 h-8 rounded-xl bg-danger/10 text-danger flex items-center justify-center shrink-0 group-hover/item:scale-110 transition-transform">
                                    <i class="fa-solid fa-arrow-trend-up text-xs"></i>
                                </div>
                                <p class="text-sm text-slate-700 font-medium leading-relaxed" x-text="gap"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Final CTAs -->
                <div class="flex flex-col md:flex-row gap-3 md:gap-4 px-6 md:px-0">
                    <button @click="bookConsultation()" class="btn-primary flex-1 py-5 text-lg shadow-xl shadow-primary/20">
                        <i class="fa-solid fa-calendar-check mr-2 rtl:ml-2"></i>
                        {{ __('Book Consultation') }}
                    </button>
                    <div class="flex flex-row gap-2 flex-1">
                        <button @click="shareOnWhatsApp()" class="flex-1 py-4 md:py-5 px-4 rounded-2xl font-black border-2 border-emerald-100 text-emerald-600 text-center hover:bg-emerald-50 hover:border-emerald-500 transition-all flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                            <span class="text-xs md:text-sm">{{ __('WhatsApp') }}</span>
                        </button>
                        <button @click="shareOnTelegram()" class="flex-1 py-4 md:py-5 px-4 rounded-2xl font-black border-2 border-sky-100 text-sky-600 text-center hover:bg-sky-50 hover:border-sky-500 transition-all flex items-center justify-center gap-2">
                            <i class="fa-brands fa-telegram text-xl"></i>
                            <span class="text-xs md:text-sm">{{ __('Telegram') }}</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 px-6 md:px-0">
                    <button @click="copyResultsToClipboard()" class="py-4 px-4 rounded-2xl font-bold border border-slate-200 text-slate-500 text-center hover:bg-white hover:border-primary hover:text-primary transition-all flex items-center justify-center gap-2 text-[10px] md:text-xs">
                        <i class="fa-regular fa-copy"></i>
                        {{ __('Copy Report') }}
                    </button>
                    <button @click="downloadResultsAsTxt()" class="py-4 px-4 rounded-2xl font-bold border border-slate-200 text-slate-500 text-center hover:bg-white hover:border-secondary hover:text-secondary transition-all flex items-center justify-center gap-2 text-[10px] md:text-xs">
                        <i class="fa-solid fa-download"></i>
                        {{ __('Download PDF/TXT') }}
                    </button>
                </div>

                <div class="text-center pt-6 pb-12 md:pb-0">
                    <button @click="resetForm()" class="text-slate-400 hover:text-primary font-bold transition-all flex items-center gap-2 mx-auto text-sm">
                        <i class="fa-solid fa-rotate-left"></i>
                        {{ __('Start Over') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Profile Summary / Info -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28 order-2 lg:order-2">
            <!-- Profile Summary Card -->
            <div class="glass-card !p-6 md:!p-8 overflow-hidden relative">
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
                
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 md:gap-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Age') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.age ? __(formData.age) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Marital Status') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.marital_status ? __(formData.marital_status) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Occupation') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.occupation ? __(formData.occupation) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Education') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.education ? __(formData.education) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Work') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.work ? __(formData.work) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Language') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.language ? __(formData.language) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('IELTS/Score') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.language_score ? __(formData.language_score) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Current Residence') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.current_residence ? __(formData.current_residence) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Target Region') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.target_region ? __(formData.target_region) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Visa Refusal History') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.visa_refusal ? __(formData.visa_refusal) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Capital') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.capital ? __(formData.capital) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Dependents') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.dependents ? __(formData.dependents) : '—'"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-3 md:p-4 bg-white/50 rounded-xl md:rounded-2xl border border-white/30 col-span-2 lg:col-span-1">
                        <span class="text-[9px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Goal') }}</span>
                        <span class="font-black text-slate-900 text-xs md:text-base" x-text="formData.goal ? __(formData.goal) : '—'"></span>
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
        </div>
    </div>

    <!-- Mobile Floating Share Button -->
    <div class="fixed bottom-6 right-6 z-40 md:hidden flex flex-col gap-3" x-show="step > totalSteps && !loading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-10">
        <button @click="shareOnWhatsApp()" class="w-14 h-14 rounded-full bg-emerald-500 text-white shadow-xl shadow-emerald-200 flex items-center justify-center active:scale-95 transition-transform">
            <i class="fa-brands fa-whatsapp text-2xl"></i>
        </button>
        <button @click="copyResultsToClipboard()" class="w-14 h-14 rounded-full bg-white text-slate-600 shadow-xl border border-slate-100 flex items-center justify-center active:scale-95 transition-transform">
            <i class="fa-regular fa-copy text-xl"></i>
        </button>
    </div>
</div>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
function eligibilityChecker(whatsappNumber) {
    return {
        whatsappNumber: whatsappNumber || '',
        step: 0,
        totalSteps: 13,
        loading: false,
        errorMessage: '',
        history: [],
        formData: {
            age: '',
            marital_status: '',
            occupation: '',
            education: '',
            work: '',
            language: '',
            language_score: '',
            current_residence: '',
            target_region: '',
            visa_refusal: '',
            capital: '',
            dependents: '',
            goal: ''
        },
        ageOptions: ['Under 18', '18-24', '25-35', '36-45', '45+'],
        maritalOptions: ['Single', 'Married', 'Married with Children'],
        occupationOptions: ['IT & Software', 'Healthcare & Medical', 'Engineering', 'Business & Finance', 'Education & Research', 'Art & Design', 'Skilled Trades', 'Other Professional'],
        educationOptions: ['High School', "Bachelor's", "Master's", 'PhD'],
        workOptions: ['Less than 1 year', '1-3 years', '3-5 years', '5-10 years', '10+ years'],
        languageOptions: ['Beginner', 'Intermediate', 'Advanced', 'Native'],
        languageScoreOptions: ['No Certificate', 'Low (e.g. IELTS 5)', 'Medium (e.g. IELTS 6-6.5)', 'High (e.g. IELTS 7+)'],
        residenceOptions: ['Middle East', 'Europe', 'Asia', 'North America', 'South America', 'Africa', 'Oceania'],
        targetOptions: ['North America', 'Europe', 'Oceania', 'Any / Best Fit'],
        refusalOptions: ['Yes', 'No'],
        capitalOptions: ['Less than $10k', '$10k-$50k', '$50k-$100k', '$100k-$500k', '$500k+'],
        dependentsOptions: ['None', '1 Dependent', '2 Dependents', '3+ Dependents'],
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
                2: this.__('Marital Status'),
                3: this.__('Occupation'),
                4: this.__('Education'),
                5: this.__('Work Experience'),
                6: this.__('Language Proficiency'),
                7: this.__('IELTS/Score'),
                8: this.__('Current Residence'),
                9: this.__('Target Region'),
                10: this.__('Visa Refusal History'),
                11: this.__('Capital'),
                12: this.__('Dependents'),
                13: this.__('Immigration Goal')
            };
            return titles[this.step] || '';
        },

        isStepComplete() {
            const fields = [
                'age', 
                'marital_status', 
                'occupation', 
                'education', 
                'work', 
                'language', 
                'language_score', 
                'current_residence',
                'target_region',
                'visa_refusal',
                'capital', 
                'dependents', 
                'goal'
            ];
            const currentField = fields[this.step - 1];
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
            this.formData = { 
                age: '', 
                marital_status: '',
                occupation: '',
                education: '', 
                work: '', 
                language: '', 
                language_score: '',
                current_residence: '',
                target_region: '',
                visa_refusal: '',
                capital: '', 
                dependents: '',
                goal: '' 
            };
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

        bookConsultation() {
            const intro = "Hello, I would like to book a consultation regarding my immigration assessment report.\n\n";
            const report = this.getFormattedResults();
            const text = encodeURIComponent(intro + report);
            const phone = this.whatsappNumber.replace(/\D/g, '');
            window.open(`https://wa.me/${phone}?text=${text}`, '_blank');
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
🚀 IMMIGRATION ASSESSMENT REPORT
--------------------------------
👤 PROFILE SUMMARY:
- Age: ${this.formData.age}
- Marital Status: ${this.__ (this.formData.marital_status)}
- Occupation: ${this.__ (this.formData.occupation)}
- Education: ${this.__ (this.formData.education)}
- Work Experience: ${this.__ (this.formData.work)}
- Language Level: ${this.__ (this.formData.language)}
- Certificate Score: ${this.__ (this.formData.language_score)}
- Current Residence: ${this.__ (this.formData.current_residence)}
- Target Region: ${this.__ (this.formData.target_region)}
- Visa Refusal History: ${this.__ (this.formData.visa_refusal)}
- Capital: ${this.__ (this.formData.capital)}
- Dependents: ${this.__ (this.formData.dependents)}
- Goal: ${this.__ (this.formData.goal)}

🌍 RECOMMENDED COUNTRIES:
${this.results.countries.join(', ')}

🛤️ IMMIGRATION PATHS:
${this.results.paths.map(p => this.__ (p)).join(', ')}

📝 ANALYSIS:
${this.results.reason}

💡 GAPS & ADVICE:
${this.results.gaps.map(g => `• ${g}`).join('\n')}

🎯 PLAN B:
${this.results.alternative}
--------------------------------
Disclaimer: This is a preliminary assessment. For official legal advice, please book a full consultation.
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