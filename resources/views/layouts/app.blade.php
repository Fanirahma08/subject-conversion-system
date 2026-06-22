<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Informasi Konversi Mata Kuliah') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        /* Tom Select Theme Override - Matches Tailwind Inputs */
        .ts-wrapper.searchable-select { background-color: white !important; border-radius: 0.5rem !important; width: 100% !important; }
        .ts-control { 
            background-color: white !important; 
            border: 1px solid #cbd5e1 !important; 
            border-radius: 0.5rem !important; 
            padding: 0.75rem 1rem !important; 
            font-size: 0.875rem !important; 
            transition: all 0.2s; 
            cursor: pointer !important; 
            min-height: 46px !important; 
            display: flex !important; 
            align-items: center !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
        }
        .ts-control input { cursor: pointer !important; font-size: 0.875rem !important; }
        .ts-wrapper.focus .ts-control { border-color: #3b82f6 !important; ring: 2px #3b82f633 !important; outline: none !important; }
        .ts-dropdown { 
            background-color: white !important; 
            border: 1px solid #e2e8f0 !important; 
            border-radius: 0.5rem !important; 
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important; 
            padding: 0.25rem !important; 
            z-index: 1000 !important; 
            margin-top: 4px !important;
        }
        .ts-dropdown .active { background-color: #eff6ff !important; color: #1e40af !important; border-radius: 0.375rem; }
        .ts-dropdown-content { padding: 4px !important; }
        .ts-dropdown .option { padding: 0.5rem 0.75rem !important; border-radius: 0.375rem; transition: background-color 0.1s; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="h-full text-slate-900 antialiased overflow-x-hidden">
    <div x-data="{ sidebarOpen: {{ auth()->check() ? 'true' : 'false' }}, mobileSidebarOpen: false }" class="min-h-full flex flex-col {{ auth()->check() ? 'lg:flex-row' : '' }}">
        @auth
            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileSidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden" aria-hidden="true"></div>

            <!-- Sidebar -->
            <aside :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen, '-translate-x-full': !mobileSidebarOpen, 'translate-x-0': mobileSidebarOpen }" 
                   class="fixed inset-y-0 left-0 z-50 flex flex-col h-dvh bg-white border-r border-slate-200 sidebar-transition transform lg:translate-x-0 transition-all group">
                
                <!-- Collapse Toggle Button (Floating) -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="hidden lg:flex absolute top-1/2 -translate-y-1/2 -right-3 z-60 items-center justify-center w-8 h-8 bg-white border border-slate-200 rounded-full text-slate-400 hover:cursor-pointer hover:text-blue-600 hover:border-blue-600 shadow-sm opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <svg :class="{'rotate-180': !sidebarOpen}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>

                <!-- Logo area -->
                <div class="flex items-center h-16 px-6 border-b border-slate-100 shrink-0 overflow-hidden">
                    <div class="shrink-0 w-8 h-8 rounded-lg bg-linear-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                          class="ml-3 text-xl font-bold tracking-tight bg-linear-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent truncate">
                       SIKMK
                    </span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 min-h-0 py-4 overflow-y-auto overflow-x-hidden px-3 space-y-1 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                    <div class="px-3 mb-2">
                         <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Utama</span>
                    </div>
                    
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            \App\Enums\UserRole::PMB => route('pmb.dashboard'),
                            \App\Enums\UserRole::Kaprodi => route('kaprodi.dashboard'),
                            \App\Enums\UserRole::Dekan => route('dekan.dashboard'),
                            \App\Enums\UserRole::Rektor => route('rektor.dashboard'),
                            \App\Enums\UserRole::Mahasiswa => route('mahasiswa.dashboard'),
                            default => '#',
                        };
                    @endphp

                    <a href="{{ $dashboardRoute }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('*.dashboard') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('*.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <span x-show="sidebarOpen" class="ml-3 truncate">Dashboard</span>
                    </a>



                    @if(auth()->user()->isPMB())
                        <div class="px-3 pt-4 mb-2">
                             <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Kontrol PMB</span>
                        </div>
                        <a href="{{ route('pmb.students.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pmb.students.*') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('pmb.students.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Manajemen Mahasiswa</span>
                        </a>
                        <a href="{{ route('pmb.universities.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pmb.universities.*') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('pmb.universities.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Manajemen Universitas</span>
                        </a>
                        <a href="{{ route('pmb.conversions.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pmb.conversions.*') ? 'bg-amber-50 text-amber-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('pmb.conversions.*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Tinjauan Transkrip</span>
                        </a>
                    @endif

                    @if(auth()->user()->isKaprodi())
                        <div class="px-3 pt-4 mb-2">
                             <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Pusat Kaprodi</span>
                        </div>
                        
                        <a href="{{ route('kaprodi.users.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('kaprodi.users.*') ? 'bg-purple-50 text-purple-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('kaprodi.users.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Manajemen User</span>
                        </a>
                        <a href="{{ route('kaprodi.subjects.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('kaprodi.subjects.*') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('kaprodi.subjects.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Repositori Mata Kuliah</span>
                        </a>
                        <a href="{{ route('kaprodi.mappings.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('kaprodi.mappings.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('kaprodi.mappings.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Pemetaan Mata Kuliah</span>
                        </a>
                        <a href="{{ route('kaprodi.grade-conversions.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('kaprodi.grade-conversions.*') ? 'bg-emerald-50 text-emerald-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('kaprodi.grade-conversions.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Konversi Nilai</span>
                        </a>
                        <a href="{{ route('kaprodi.conversions.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('kaprodi.conversions.*') ? 'bg-amber-50 text-amber-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('kaprodi.conversions.*') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Tinjauan Konversi</span>
                        </a>
                    @endif

                    @if(auth()->user()->isDekan())
                        <div class="px-3 pt-4 mb-2">
                             <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Pusat Dekan</span>
                        </div>
                        <a href="{{ route('dekan.conversions.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('dekan.conversions.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('dekan.conversions.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Persetujuan Konversi</span>
                        </a>
                    @endif

                    @if(auth()->user()->isRektor())
                        <div class="px-3 pt-4 mb-2">
                             <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Pusat Rektor</span>
                        </div>
                        <a href="{{ route('rektor.conversions.index') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('rektor.conversions.*') ? 'bg-emerald-50 text-emerald-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('rektor.conversions.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Penerbitan SK</span>
                        </a>
                    @endif

                    {{-- @if(auth()->user()->isMahasiswa())
                        <div class="px-3 pt-4 mb-2">
                             <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Perjalanan PMB</span>
                        </div>
                        <a href="{{ route('mahasiswa.dashboard') }}" 
                           class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-emerald-50 text-emerald-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('mahasiswa.dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span x-show="sidebarOpen" class="ml-3 truncate">Konversi PMB</span>
                        </a>
                    @endif --}}

                    <div class="px-3 pt-4 mb-2">
                         <span x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Pengaturan</span>
                    </div>
                    <a href="{{ route('profile.password') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('profile.password') ? 'bg-blue-50 text-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <div class="shrink-0 w-6 h-6 flex items-center justify-center {{ request()->routeIs('profile.password') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <span x-show="sidebarOpen" class="ml-3 truncate">Ganti Kata Sandi</span>
                    </a>
                </nav>

                <!-- User profile -->
                <div class="p-4 border-t border-slate-100 shrink-0">
                    <div class="flex items-center" :class="sidebarOpen ? 'px-2' : 'justify-center'">
                        <div class="shrink-0">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div x-show="sidebarOpen" class="ml-3 min-w-0 grow">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ ucfirst(auth()->user()->role->value) }}</p>
                        </div>
                        <form x-show="sidebarOpen" method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        @endauth

        <!-- Main Content Wrapper -->
        <div @auth :class="{ 'lg:ml-64': sidebarOpen, 'lg:ml-20': !sidebarOpen }" @endauth class="flex flex-col grow sidebar-transition min-h-screen">
            @auth
                <!-- Top Header -->
                <header class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200 sticky top-0 z-30">
                    <button @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-md text-slate-500 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="grow px-4">
                        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">
                             @yield('title', 'Ringkasan')
                        </h2>
                    </div>
                </header>
            @endauth

            <!-- Content Area -->
            <main class="grow py-8 px-2 sm:px-4 lg:px-6 {{ !auth()->check() ? 'flex items-center justify-center bg-slate-100' : '' }}">
                <div class="{{ auth()->check() ? 'max-w-8xl mx-auto' : 'w-full' }}">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-slate-400 text-sm">
                    &copy; {{ date('Y') }} SIKMK. Semua hak dilindungi.
                </div>
            </footer>
        </div>
    </div>

    <!-- Simple Script for x-data simulation without Alpine if not present -->
    <!-- I'll use Alpine.js CDN for speed and "Wow" factor as it's standard for Laravel projects -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.searchable-select').forEach(el => {
                const ts = new TomSelect(el, {
                    create: false,
                    sortField: { field: "text", order: "asc" },
                    placeholder: el.getAttribute('placeholder') || 'Search...',
                    onInitialize: function() {
                        this.wrapper.classList.add('searchable-select');
                    }
                });
            });
        });
    </script>
</body>
</html>
