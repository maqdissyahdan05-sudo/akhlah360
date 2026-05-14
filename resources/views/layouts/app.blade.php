<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - PT Energi Nusantara</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        h1, h2, h3, h4, h5, h6, .brand-font { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex bg-gray-50/50">
    
    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col hidden md:flex shadow-2xl shadow-blue-900/5 z-10 relative">
        <div class="h-20 flex items-center px-6 border-b border-white/10 bg-gradient-to-r from-blue-700 to-indigo-800 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-24 h-24 bg-blue-400/20 rounded-full blur-xl"></div>
            <h1 class="text-xl font-extrabold text-white brand-font tracking-tight relative z-10">PT Energi Nusantara</h1>
        </div>
        
        <div class="flex-1 overflow-y-auto py-6 px-4">
            <div class="mb-6 px-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main Menu</p>
                <nav class="space-y-1">
                    @php $role = auth()->user()->role->role_slug; @endphp
                    
                    @if($role === 'admin_hr')
                        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
                        
                        <div x-data="{ open: {{ request()->routeIs('admin.periods.*') || request()->routeIs('admin.assignments.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-all group">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Create Assessment Form
                                </span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 ml-4 pl-4 border-l border-gray-100 space-y-1">
                                <x-nav-link href="{{ route('admin.periods.index') }}" :active="request()->routeIs('admin.periods.*')" class="!py-1.5 !text-xs">Manage Assessment Period</x-nav-link>
                                <x-nav-link href="{{ route('admin.akhlaq-values.index') }}" :active="request()->routeIs('admin.akhlaq-values.*')" class="!py-1.5 !text-xs">Manage Assessment Questions</x-nav-link>
                                <x-nav-link href="{{ route('admin.assignments.index') }}" :active="request()->routeIs('admin.assignments.*')" class="!py-1.5 !text-xs">Assign 360 Raters</x-nav-link>
                            </div>
                        </div>

                        <div x-data="{ open: {{ request()->routeIs('admin.progress.*') || request()->routeIs('admin.audit-logs.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-all group">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    Assessment Progress
                                </span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 ml-4 pl-4 border-l border-gray-100 space-y-1">
                                <x-nav-link href="{{ route('admin.progress.index') }}" :active="request()->routeIs('admin.progress.*')" class="!py-1.5 !text-xs">Monitor Assessment Progress</x-nav-link>
                                <x-nav-link href="{{ route('admin.audit-logs.index') }}" :active="request()->routeIs('admin.audit-logs.*')" class="!py-1.5 !text-xs">View Audit Trail</x-nav-link>
                                <x-nav-link href="{{ route('admin.progress.notifications') }}" :active="request()->routeIs('admin.progress.notifications')" class="!py-1.5 !text-xs">Send Notification</x-nav-link>
                            </div>
                        </div>

                        <div x-data="{ open: {{ request()->routeIs('management.reports.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-all group">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                    Assessment Report
                                </span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 ml-4 pl-4 border-l border-gray-100 space-y-1">
                                <x-nav-link href="{{ route('management.reports.index') }}" :active="request()->routeIs('management.reports.index')" class="!py-1.5 !text-xs">View Assessment Report</x-nav-link>
                                <x-nav-link href="{{ route('management.reports.gap-analysis') }}" :active="request()->routeIs('management.reports.gap-analysis')" class="!py-1.5 !text-xs">View Gap Analysis</x-nav-link>
                                <x-nav-link href="{{ route('management.reports.trend') }}" :active="request()->routeIs('management.reports.trend')" class="!py-1.5 !text-xs">View Employee Performance Trend</x-nav-link>
                                <a href="{{ route('management.reports.export-preview') }}" class="block px-4 py-1.5 text-xs font-bold {{ request()->routeIs('management.reports.export-preview') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600' }} rounded-md transition-all">
                                    Export Report <span class="ml-1 text-[10px] bg-blue-600 text-white px-1 rounded">NEW</span>
                                </a>
                            </div>
                        </div>

                        <x-nav-link href="{{ route('assessment.dashboard') }}" :active="request()->routeIs('assessment.dashboard')">Personal Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('assessment.tasks') }}" :active="request()->routeIs('assessment.tasks') || request()->routeIs('assessment.form.*')">Assessment Form</x-nav-link>
                    
                    @elseif($role === 'manajemen')
                        <x-nav-link href="{{ route('management.dashboard') }}" :active="request()->routeIs('management.dashboard')">Dashboard</x-nav-link>
                        
                        <div x-data="{ open: {{ request()->routeIs('management.reports.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-all group">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                    Assessment Report
                                </span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 ml-4 pl-4 border-l border-gray-100 space-y-1">
                                <x-nav-link href="{{ route('management.reports.index') }}" :active="request()->routeIs('management.reports.index')" class="!py-1.5 !text-xs">View Assessment Report</x-nav-link>
                                <x-nav-link href="{{ route('management.reports.gap-analysis') }}" :active="request()->routeIs('management.reports.gap-analysis')" class="!py-1.5 !text-xs">View Gap Analysis</x-nav-link>
                                <x-nav-link href="{{ route('management.reports.trend') }}" :active="request()->routeIs('management.reports.trend')" class="!py-1.5 !text-xs">View Employee Performance Trend</x-nav-link>
                                <a href="{{ route('management.reports.export-preview') }}" class="block px-4 py-1.5 text-xs font-bold {{ request()->routeIs('management.reports.export-preview') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600' }} rounded-md transition-all">
                                    Export Report <span class="ml-1 text-[10px] bg-blue-600 text-white px-1 rounded">NEW</span>
                                </a>
                            </div>
                        </div>

                        <x-nav-link href="{{ route('assessment.dashboard') }}" :active="request()->routeIs('assessment.dashboard')">Personal Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('assessment.tasks') }}" :active="request()->routeIs('assessment.tasks') || request()->routeIs('assessment.form.*')">Assessment Form</x-nav-link>
                    
                    @elseif(in_array($role, ['atasan', 'karyawan']))
                        <x-nav-link href="{{ route('assessment.dashboard') }}" :active="request()->routeIs('assessment.dashboard')">View Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('assessment.tasks') }}" :active="request()->routeIs('assessment.tasks') || request()->routeIs('assessment.form.*')">Assessment Form</x-nav-link>
                    @endif
                </nav>
            </div>
        </div>

        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 text-white flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->employee->employee_name ?? auth()->user()->username, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->employee->employee_name ?? auth()->user()->username }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->role->role_name }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50/50 relative">
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-blue-50/50 to-transparent pointer-events-none"></div>
        
        <!-- Top Navbar for Mobile -->
        <header class="h-16 bg-gradient-to-r from-blue-700 to-indigo-800 flex items-center justify-between px-4 md:hidden shadow-md z-10 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNSkiLz48L3N2Zz4=')] opacity-50"></div>
            <h1 class="text-xl font-extrabold text-white brand-font tracking-tight relative z-10">Energi Nusantara</h1>
            <!-- Mobile menu button omitted for brevity, assuming desktop-first for this demo -->
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 relative z-0">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">@yield('title')</h2>
                    @if(View::hasSection('subtitle'))
                        <p class="text-sm text-gray-500 mt-1">@yield('subtitle')</p>
                    @endif
                </div>

                @if(session('success'))
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm mb-6 border border-green-200 shadow-sm flex items-start">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm mb-6 border border-red-200 shadow-sm flex items-start">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

</body>
</html>
