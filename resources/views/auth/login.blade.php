<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Penilaian Kinerja 360°</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
        .bg-image {
            background-image: url('{{ asset("images/corporate_bg.png") }}');
            background-size: cover;
            background-position: center;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4 lg:p-0">

    <div class="w-full max-w-6xl h-auto lg:h-[600px] flex flex-col lg:flex-row rounded-3xl overflow-hidden shadow-2xl relative">
        
        <!-- Left Side: Image and Branding -->
        <div class="hidden lg:flex lg:w-3/5 bg-image relative items-center justify-center p-12">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-indigo-900/90 mix-blend-multiply"></div>
            
            <div class="relative z-10 text-white w-full max-w-lg">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-4">
                    Employee Performance Appraisal<br/>
                    <span class="text-blue-300">360° Core Values AKHLAK</span>
                </h1>
                <p class="text-lg text-blue-100 mb-8 max-w-md">
                    Build a professional, transparent, and high-performance environment with PT Energi Nusantara.
                </p>
                
                <div class="flex items-center space-x-4">
                    <div class="h-1 w-12 bg-blue-500 rounded-full"></div>
                    <p class="text-sm font-medium text-gray-300 tracking-wider uppercase">PT Energi Nusantara</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-2/5 glass-panel p-8 lg:p-12 flex flex-col justify-center relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-bl-[100px] -z-10"></div>
            
            <div class="mb-10 lg:hidden">
                <h2 class="text-2xl font-bold text-gray-900">360° Core Values AKHLAK</h2>
                <p class="text-sm text-gray-500 mt-1">PT Energi Nusantara</p>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome</h2>
                <p class="text-gray-500">Please sign in to your account to continue.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6 text-sm flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required 
                            class="pl-10 w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-gray-50/50 outline-none font-medium"
                            placeholder="Enter NIK or Email">
                    </div>
                    @error('username') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required 
                            class="pl-10 w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-gray-50/50 outline-none font-medium"
                            placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer group">
                        <input type="checkbox" name="remember" class="rounded w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors">
                        <span class="font-medium group-hover:text-blue-600 transition-colors">Remember my credentials</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center space-x-2 mt-4">
                    <span>Login</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-8 text-center text-xs font-medium text-gray-400">
                &copy; {{ date('Y') }} PT Energi Nusantara. All rights reserved.
            </div>
        </div>
    </div>

</body>
</html>
