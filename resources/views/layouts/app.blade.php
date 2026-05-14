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
                        <x-nav-link href="{{ route('admin.periods.index') }}" :active="request()->routeIs('admin.periods.*')">Assessment Periods</x-nav-link>
                        <x-nav-link href="{{ route('admin.assignments.index') }}" :active="request()->routeIs('admin.assignments.*')">360° Mapping</x-nav-link>
                        <x-nav-link href="{{ route('admin.progress.index') }}" :active="request()->routeIs('admin.progress.*')">Monitor Progress</x-nav-link>
                        <x-nav-link href="{{ route('management.reports.index') }}" :active="request()->routeIs('management.reports.*')">Performance Reports</x-nav-link>
                        <x-nav-link href="{{ route('assessment.dashboard') }}" :active="request()->routeIs('assessment.dashboard')">Personal Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('assessment.tasks') }}" :active="request()->routeIs('assessment.tasks') || request()->routeIs('assessment.form.*')">Assessment Tasks</x-nav-link>
                        
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-1">Master Data</p>
                        <x-nav-link href="{{ route('admin.employees.index') }}" :active="request()->routeIs('admin.employees.*')">Employee Directory</x-nav-link>
                        <x-nav-link href="{{ route('admin.departments.index') }}" :active="request()->routeIs('admin.departments.*')">Departments</x-nav-link>
                        <x-nav-link href="{{ route('admin.akhlaq-values.index') }}" :active="request()->routeIs('admin.akhlaq-values.*')">Core Values (AKHLAK)</x-nav-link>
                        <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">User Accounts</x-nav-link>
                        <x-nav-link href="{{ route('admin.audit-logs.index') }}" :active="request()->routeIs('admin.audit-logs.*')">System Logs</x-nav-link>
                    
                    @elseif($role === 'manajemen')
                        <x-nav-link href="{{ route('management.dashboard') }}" :active="request()->routeIs('management.dashboard')">Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('management.reports.index') }}" :active="request()->routeIs('management.reports.*')">Performance Reports</x-nav-link>
                        <x-nav-link href="{{ route('assessment.dashboard') }}" :active="request()->routeIs('assessment.dashboard')">Personal Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('assessment.tasks') }}" :active="request()->routeIs('assessment.tasks') || request()->routeIs('assessment.form.*')">Assessment Tasks</x-nav-link>
                    
                    @elseif(in_array($role, ['atasan', 'karyawan']))
                        <x-nav-link href="{{ route('assessment.dashboard') }}" :active="request()->routeIs('assessment.dashboard')">Dashboard</x-nav-link>
                        <x-nav-link href="{{ route('assessment.tasks') }}" :active="request()->routeIs('assessment.tasks') || request()->routeIs('assessment.form.*')">Assessment Tasks</x-nav-link>
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
