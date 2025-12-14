@extends('layouts.app')

@section('content')
    {{-- HEADER --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- HEADER LOGO --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('asset/img/logo.png') }}" alt="Bug Tracker API Logo" class="h-8 w-8 object-contain">
                    <span class="font-bold text-xl tracking-tight">Bug Tracker API</span>
                </div>


                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-8">
                    <a href="http://localhost:8000/api/documentation#/" target="_blank"
                        class="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">
                        API Docs
                    </a>
                    <a href="#features"
                        class="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">Features</a>
                    <a href="#how-it-works"
                        class="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">How It Works</a>
                    <a href="#docs" class="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">API
                        Docs</a>
                    <a href="#github"
                        class="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">GitHub</a>
                    {{-- <a href="{{ route('dashboard') ?? '#' }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors shadow-lg shadow-red-600/20">
                        Dashboard
                    </a> --}}
                </nav>

                {{-- Mobile Menu Button (non-functional dulu, biar simple) --}}
                <button class="md:hidden p-2 text-gray-600">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="relative pt-20 pb-32 overflow-hidden bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                {{-- Left Content --}}
                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-3 py-1 mb-8 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">v1.1 is now live</span>
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-black text-gray-900 leading-[1.1] mb-6">
                        Bug Tracker API for <span class="text-red-600">Quality Assurance</span>
                    </h1>

                    <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-lg">
                        Streamline your debugging workflow with a robust, developer-friendly REST API. Centralize bug
                        logging,
                        track resolution status, and integrate seamlessly with your existing tools.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 mb-10">
                        <a href="http://localhost:8000/api/documentation#/" target="_blank"
                            class="bg-gray-900 hover:bg-black text-white px-8 py-3.5 rounded-lg font-bold text-sm transition-transform hover:-translate-y-0.5 shadow-xl shadow-gray-900/10">
                            View API Documentation
                        </a>

                        {{-- <a href="{{ route('dashboard') ?? '#' }}"
                            class="bg-white hover:bg-gray-50 text-gray-900 border border-gray-200 px-8 py-3.5 rounded-lg font-bold text-sm transition-colors">
                            Go to Dashboard
                        </a> --}}
                    </div>

                    {{-- <div class="flex items-center gap-4 border-t border-gray-100 pt-8">
                        <div class="flex -space-x-3">
                            <img src="https://picsum.photos/seed/51/100" alt="User"
                                class="w-10 h-10 rounded-full border-2 border-white ring-1 ring-gray-100 object-cover">
                            <img src="https://picsum.photos/seed/52/100" alt="User"
                                class="w-10 h-10 rounded-full border-2 border-white ring-1 ring-gray-100 object-cover">
                            <img src="https://picsum.photos/seed/53/100" alt="User"
                                class="w-10 h-10 rounded-full border-2 border-white ring-1 ring-gray-100 object-cover">
                        </div>
                        <p class="text-sm font-medium text-gray-500">Trusted by 500+ developers</p>
                    </div> --}}
                </div>

                {{-- Right Content (Mockup card) --}}
                <div class="relative lg:ml-auto w-full max-w-lg">
                    <div
                        class="absolute -top-20 -right-20 w-96 h-96 bg-red-100 rounded-full blur-3xl opacity-50 mix-blend-multiply">
                    </div>
                    <div
                        class="absolute -bottom-20 -left-20 w-96 h-96 bg-orange-100 rounded-full blur-3xl opacity-50 mix-blend-multiply">
                    </div>

                    <div
                        class="relative bg-white rounded-2xl shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        {{-- Card Header --}}
                        <div
                            class="bg-gray-50/80 backdrop-blur px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="text-xs font-mono text-gray-400">api/v1/bugs</div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-2">
                            <div
                                class="grid grid-cols-[1fr_auto] gap-4 px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-1">
                                <div>Issue</div>
                                <div>Status</div>
                            </div>

                            <div class="space-y-1">
                                {{-- Row 1 --}}
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-lg flex items-center justify-center bg-red-50 text-red-500">
                                            <span class="material-symbols-outlined text-[20px]">error</span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">Auth Token Expired</h4>
                                            <p class="text-xs text-gray-500 font-medium">Opened 2h ago</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wide text-red-700 bg-red-100 border-red-200">
                                        Critical
                                    </span>
                                </div>

                                {{-- Row 2 --}}
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-lg flex items-center justify-center bg-orange-50 text-orange-500">
                                            <span class="material-symbols-outlined text-[20px]">warning</span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">Layout Shift on Mobile</h4>
                                            <p class="text-xs text-gray-500 font-medium">Opened 1d ago</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wide text-orange-700 bg-orange-100 border-orange-200">
                                        Review
                                    </span>
                                </div>

                                {{-- Row 3 --}}
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-lg flex items-center justify-center bg-gray-100 text-gray-500">
                                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">Typo in Footer</h4>
                                            <p class="text-xs text-gray-500 font-medium">Resolved 3d ago</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wide text-gray-700 bg-gray-100 border-gray-200">
                                        Fixed
                                    </span>
                                </div>

                                {{-- Row 4 --}}
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-50 text-blue-500">
                                            <span class="material-symbols-outlined text-[20px]">bug_report</span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">API Rate Limit</h4>
                                            <p class="text-xs text-gray-500 font-medium">Opened 5m ago</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wide text-blue-700 bg-blue-100 border-blue-200">
                                        New
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- FEATURES (Why This API?) --}}
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why This API?</h2>
                <p class="text-lg text-gray-600 max-w-2xl">
                    Designed to make bug tracking simple, effective, and easy to integrate into your existing workflow.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Card 1 --}}
                <div
                    class="bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-gray-900 text-white">
                        <span class="material-symbols-outlined">database</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Centralized Logging</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Keep all your issues in one place with a unified database accessible via REST.
                    </p>
                </div>

                {{-- Card 2 --}}
                <div
                    class="bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-white border border-gray-200 text-red-600">
                        <span class="material-symbols-outlined">account_tree</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Clear Status Workflow</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Track bugs from "Open" to "Resolved" with strictly defined state transitions.
                    </p>
                </div>

                {{-- Card 3 --}}
                <div
                    class="bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-white border border-gray-200 text-red-600">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Role Collaboration</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Assign roles for QA, Developers, and Managers securely with scopes.
                    </p>
                </div>

                {{-- Card 4 --}}
                <div
                    class="bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 bg-white border border-gray-200 text-red-600">
                        <span class="material-symbols-outlined">integration_instructions</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Easy Integration</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Simple JSON endpoints to connect with your frontend apps in minutes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="how-it-works" class="py-24 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-20">
                <h2 class="text-4xl font-black text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600">Three simple steps to manage your quality assurance process.</p>
            </div>

            <div class="relative grid md:grid-cols-3 gap-12 max-w-5xl mx-auto">
                <div
                    class="hidden md:block absolute top-10 left-[16%] right-[16%] h-0.5 bg-gradient-to-r from-gray-200 via-red-200 to-gray-200">
                </div>

                {{-- Step 1 --}}
                <div class="relative z-10 flex flex-col items-center">
                    <div
                        class="w-20 h-20 bg-white rounded-2xl shadow-lg shadow-gray-200 border border-gray-100 flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-red-600 text-4xl">add_circle</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">1. Report</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                        QA logs a new bug via POST request with details and severity.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="relative z-10 flex flex-col items-center">
                    <div
                        class="w-20 h-20 bg-white rounded-2xl shadow-lg shadow-gray-200 border border-gray-100 flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-red-600 text-4xl">build</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">2. Fix</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                        Developers pick up the issue, fix the code, and update status.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="relative z-10 flex flex-col items-center">
                    <div
                        class="w-20 h-20 bg-white rounded-2xl shadow-lg shadow-gray-200 border border-gray-100 flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-red-600 text-4xl">verified</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">3. Verify</h3>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                        QA verifies the fix and closes the ticket. Cycle complete.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CAPABILITIES --}}
    <section id="docs" class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                {{-- Left Content --}}
                <div class="order-2 lg:order-1">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Key API Capabilities</h2>
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed">
                        Everything you need to build a custom bug tracking frontend or integrate into your CLI tools.
                    </p>

                    <ul class="space-y-8">
                        <li class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <span class="material-symbols-outlined text-green-600 text-sm font-bold">check</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Secure Authentication</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Sanctum-powered token authentication for secure API access.
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <span class="material-symbols-outlined text-green-600 text-sm font-bold">check</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Full CRUD Operations</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Create, Read, Update, and Delete bugs with granular permissions.
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <span class="material-symbols-outlined text-green-600 text-sm font-bold">check</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Filtering & Sorting</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Advanced query parameters to find exactly what you need.
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mt-1">
                                <span class="material-symbols-outlined text-green-600 text-sm font-bold">check</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Comments & Attachments</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Collaborate directly on tickets with comment threads.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Right Content (Code Block) --}}
                <div class="order-1 lg:order-2">
                    <div class="rounded-xl overflow-hidden bg-[#1e1e1e] shadow-2xl ring-4 ring-gray-100">
                        <div class="flex items-center justify-between px-4 py-3 bg-[#2d2d2d] border-b border-white/5">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-bold text-green-400 bg-green-400/10 px-1.5 py-0.5 rounded uppercase">GET</span>
                                <span class="text-xs text-gray-400 font-mono">/api/bugs/101</span>
                            </div>
                        </div>
                        <div class="p-6 overflow-x-auto">
                            <pre class="text-sm font-mono text-gray-300 leading-relaxed"><code>{
  <span class="text-red-400">"id"</span>: <span class="text-blue-300">101</span>,
  <span class="text-red-400">"title"</span>: <span class="text-green-300">"Homepage crash on iOS"</span>,
  <span class="text-red-400">"status"</span>: <span class="text-green-300">"open"</span>,
  <span class="text-red-400">"severity"</span>: <span class="text-red-300 font-bold">"critical"</span>,
  <span class="text-red-400">"assigned_to"</span>: {
    <span class="text-red-400">"id"</span>: <span class="text-blue-300">5</span>,
    <span class="text-red-400">"name"</span>: <span class="text-green-300">"Alex Dev"</span>,
    <span class="text-red-400">"role"</span>: <span class="text-green-300">"developer"</span>
  },
  <span class="text-red-400">"created_at"</span>: <span class="text-green-300">"2023-10-25T14:30:00Z"</span>
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TARGET AUDIENCE --}}
    <section id="github" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">Who is this for?</h2>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- QA --}}
                <div
                    class="bg-white p-8 rounded-xl border-t-4 shadow-sm flex flex-col items-center text-center hover:shadow-xl transition-shadow border-red-500">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6 bg-red-50 text-red-600">
                        <span class="material-symbols-outlined text-3xl">pest_control</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">QA Engineers</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Log bugs quickly with all necessary metadata. Track verification cycles without the clutter.
                    </p>
                </div>

                {{-- Developers --}}
                <div
                    class="bg-white p-8 rounded-xl border-t-4 shadow-sm flex flex-col items-center text-center hover:shadow-xl transition-shadow border-gray-900">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6 bg-gray-100 text-gray-900">
                        <span class="material-symbols-outlined text-3xl">code</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Developers</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Access bug details via terminal or custom dashboards. Integrate directly into your CI/CD.
                    </p>
                </div>

                {{-- PMs --}}
                <div
                    class="bg-white p-8 rounded-xl border-t-4 shadow-sm flex flex-col items-center text-center hover:shadow-xl transition-shadow border-gray-400">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6 bg-gray-50 text-gray-600">
                        <span class="material-symbols-outlined text-3xl">manage_accounts</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Project Managers</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Get a high-level view of project health and bug resolution velocity through report endpoints.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-100 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('asset/img/logo.png') }}" alt="Bug Tracker API Logo"
                            class="h-8 w-8 object-contain">
                        <span class="font-bold text-xl">Bug Tracker API</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        A robust, open-source bug tracking solution designed for modern development teams using Laravel.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#features"
                                class="text-sm text-gray-500 hover:text-red-600 transition-colors">Features</a></li>
                        <li><a href="#docs"
                                class="text-sm text-gray-500 hover:text-red-600 transition-colors">Integrations</a></li>
                        <li><a href="http://localhost:8000/api/documentation#/" target="_blank"
                                class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                Documentation
                            </a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Resources</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="https://github.com/firmanhoy/BugTrackerAPI" target="_blank"
                                class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                GitHub Repo
                            </a>
                        </li>
                        <li><a href="http://localhost:8000/api/documentation#/" target="_blank"
                                class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                API Reference
                            </a></li>
                        <li><a href="#"
                                class="text-sm text-gray-500 hover:text-red-600 transition-colors">Community</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-500 hover:text-red-600 transition-colors">Privacy
                                Policy</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-red-600 transition-colors">Terms of
                                Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400">© 2025 Bug Tracker API. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="https://github.com/firmanhoy/BugTrackerAPI" target="_blank"
                        class="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors">
                        GitHub
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">alternate_email</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
@endsection
