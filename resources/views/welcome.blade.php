<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InnLedger - Hotel Management</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Libre+Franklin:wght@300;400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --gold: #c9a227;
                --gold-light: #e8d48b;
                --navy: #0c1015;
                --navy-light: #1a2332;
            }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes lineGrow {
                from { transform: scaleX(0); }
                to { transform: scaleX(1); }
            }

            .fade-up {
                animation: fadeUp 0.8s ease-out forwards;
            }

            .fade-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
            .fade-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
            .fade-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
            .fade-up-delay-4 { animation-delay: 0.4s; opacity: 0; }

            .line-grow {
                animation: lineGrow 1s ease-out forwards;
                animation-delay: 0.5s;
                transform: scaleX(0);
            }

            .input-field {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(201, 162, 39, 0.15);
                transition: all 0.3s ease;
            }

            .input-field:focus {
                outline: none;
                background: rgba(255, 255, 255, 0.05);
                border-color: var(--gold);
                box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.1);
            }

            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.25);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s ease;
            }

            .btn-primary:hover::before {
                left: 100%;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(201, 162, 39, 0.3);
            }

            .geometric-corner {
                position: absolute;
                width: 60px;
                height: 60px;
                border: 1px solid rgba(201, 162, 39, 0.3);
            }

            .corner-tl { top: 0; left: 0; border-right: none; border-bottom: none; }
            .corner-tr { top: 0; right: 0; border-left: none; border-bottom: none; }
            .corner-bl { bottom: 0; left: 0; border-right: none; border-top: none; }
            .corner-br { bottom: 0; right: 0; border-left: none; border-top: none; }
        </style>
    </head>
    <body class="antialiased min-h-screen bg-[#0c1015] overflow-x-hidden">
        <div class="min-h-screen flex">
            <div class="hidden lg:flex lg:w-1/2 relative bg-[#0a0d11] items-center justify-center overflow-hidden">
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=&apos;60&apos; height=&apos;60&apos; viewBox=&apos;0 0 60 60&apos; xmlns=&apos;http://www.w3.org/2000/svg&apos;%3E%3Cpath d=&apos;M30 0L60 30L30 60L0 30z&apos; fill=&apos;none&apos; stroke=&apos;%23c9a227&apos; stroke-width=&apos;0.5&apos;/%3E%3C/svg%3E'); background-size: 60px 60px;"></div>

                <div class="absolute top-1/4 left-1/4 w-64 h-64 border border-[#c9a227]/20 rotate-45"></div>
                <div class="absolute bottom-1/4 right-1/4 w-48 h-48 border border-[#c9a227]/10 rotate-12"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-[#c9a227]/5 rounded-full"></div>

                <div class="relative z-10 text-center px-12">
                    <div class="mb-8">
                        <svg class="w-20 h-20 mx-auto text-[#c9a227]" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50 5L95 30V70L50 95L5 70V30L50 5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M50 20L80 37.5V62.5L50 80L20 62.5V37.5L50 20Z" stroke="currentColor" stroke-width="1"/>
                            <circle cx="50" cy="50" r="12" stroke="currentColor" stroke-width="1"/>
                            <circle cx="50" cy="50" r="4" fill="currentColor"/>
                        </svg>
                    </div>

                    <h2 class="font-['Playfair_Display'] text-5xl text-white mb-4 tracking-wide">InnLedger</h2>
                    <div class="w-24 h-px bg-gradient-to-r from-transparent via-[#c9a227] to-transparent mx-auto mb-6 line-grow"></div>
                    <p class="font-['Libre_Franklin'] text-[#c9a227]/70 text-sm tracking-[0.25em] uppercase">Hotel Management System</p>

                    <div class="mt-16 grid grid-cols-3 gap-8 text-center">
                        <div class="fade-up fade-up-delay-1">
                            <div class="text-3xl font-['Playfair_Display'] text-white mb-1">24/7</div>
                            <div class="text-xs text-white/40 tracking-wider uppercase">Support</div>
                        </div>
                        <div class="fade-up fade-up-delay-2">
                            <div class="text-3xl font-['Playfair_Display'] text-white mb-1">100+</div>
                            <div class="text-xs text-white/40 tracking-wider uppercase">Properties</div>
                        </div>
                        <div class="fade-up fade-up-delay-3">
                            <div class="text-3xl font-['Playfair_Display'] text-white mb-1">5★</div>
                            <div class="text-xs text-white/40 tracking-wider uppercase">Service</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16 relative">
                <div class="geometric-corner corner-tl"></div>
                <div class="geometric-corner corner-tr"></div>
                <div class="geometric-corner corner-bl"></div>
                <div class="geometric-corner corner-br"></div>

                <div class="w-full max-w-md">
                    <div class="lg:hidden text-center mb-12 fade-up">
                        <h1 class="font-['Playfair_Display'] text-4xl text-white mb-2">InnLedger</h1>
                        <p class="text-[#c9a227]/60 text-xs tracking-[0.2em] uppercase">Hotel Management</p>
                    </div>

                    <div class="fade-up fade-up-delay-1">
                        <h1 class="font-['Playfair_Display'] text-3xl text-white mb-2">Welcome Back</h1>
                        <p class="font-['Libre_Franklin'] text-white/50 text-sm mb-10">Sign in to access your dashboard</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div class="fade-up fade-up-delay-2">
                            <label for="email" class="block font-['Libre_Franklin'] text-xs text-white/60 tracking-wider uppercase mb-3">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                autofocus
                                autocomplete="username"
                                class="input-field w-full px-5 py-4 rounded-lg text-white font-['Libre_Franklin'] text-sm"
                                placeholder="Enter your email"
                            />
                        </div>

                        <div class="fade-up fade-up-delay-3">
                            <label for="password" class="block font-['Libre_Franklin'] text-xs text-white/60 tracking-wider uppercase mb-3">
                                Password
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="input-field w-full px-5 py-4 rounded-lg text-white font-['Libre_Franklin'] text-sm"
                                placeholder="Enter your password"
                            />
                        </div>

                        <div class="flex items-center justify-between fade-up fade-up-delay-4">
                            <label class="flex items-center cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded border-[#c9a227]/30 bg-transparent text-[#c9a227] focus:ring-[#c9a227] focus:ring-offset-0 focus:ring-offset-transparent"
                                />
                                <span class="ml-3 font-['Libre_Franklin'] text-sm text-white/50 group-hover:text-white/70 transition-colors">
                                    Remember me
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-['Libre_Franklin'] text-sm text-[#c9a227]/70 hover:text-[#c9a227] transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <div class="pt-4 fade-up fade-up-delay-4">
                            <button
                                type="submit"
                                class="btn-primary w-full py-4 rounded-lg text-[#0c1015] font-['Libre_Franklin'] font-medium tracking-wider uppercase text-sm"
                            >
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="mt-12 pt-8 border-t border-white/5 text-center fade-up fade-up-delay-4">
                        <p class="font-['Libre_Franklin'] text-xs text-white/30">
                            &copy; {{ date('Y') }} InnLedger. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
