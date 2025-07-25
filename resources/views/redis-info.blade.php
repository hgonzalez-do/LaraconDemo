<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redis Configuration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Tailwind CSS Inline Fallback (shortened for clarity in this example) -->
        <style>
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
            /* Place other needed fallback styles here if required */
        </style>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    {{-- Header/Login Nav --}}
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 border text-[#1b1b18] dark:text-[#EDEDEC] border-[#19140035] dark:border-[#3E3E3A] rounded-sm text-sm leading-normal hover:border-[#1915014a] dark:hover:border-[#62605b]">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 text-[#1b1b18] dark:text-[#EDEDEC] border border-transparent rounded-sm text-sm leading-normal hover:border-[#19140035] dark:hover:border-[#3E3E3A]">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 text-[#1b1b18] dark:text-[#EDEDEC] border-[#19140035] dark:border-[#3E3E3A] border rounded-sm text-sm leading-normal hover:border-[#1915014a] dark:hover:border-[#62605b]">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    {{-- Main Content --}}
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

            {{-- Welcome / Docs Panel --}}
            <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                <h1 class="mb-1 font-medium">Let's get started &rarr; <span class="font-normal text-xs">{{ env('APP_URL') }}</span></h1>
                <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                    Laravel has an incredibly rich ecosystem.<br>
                    We suggest starting with the following.
                </p>
                <ul class="flex flex-col mb-4 lg:mb-6">
                    <li class="flex items-center gap-4 py-2 relative">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] w-3.5 h-3.5 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <span>
                            Read the
                            <a href="https://laravel.com/docs" target="_blank" class="inline-flex items-center font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433] ml-1">
                                Documentation
                                <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 ml-1">
                                    <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square"/>
                                </svg>
                            </a>
                        </span>
                    </li>
                    <li class="flex items-center gap-4 py-2 relative">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] w-3.5 h-3.5 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <span>
                            Watch video tutorials at
                            <a href="https://laracasts.com" target="_blank" class="inline-flex items-center font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433] ml-1">
                                Laracasts
                                <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 ml-1">
                                    <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square"/>
                                </svg>
                            </a>
                        </span>
                    </li>
                </ul>
                <ul class="flex gap-3 text-sm leading-normal">
                    <li>
                        <a href="https://cloud.laravel.com" target="_blank" class="inline-block px-5 py-1.5 bg-[#1b1b18] text-white border border-black rounded-sm dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black">
                            Deploy now
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Redis Status Panel --}}
            <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden p-6 flex flex-col">
                <h2 class="text-lg font-semibold mb-2">Redis Configuration</h2>

                <div class="mb-2">
                    <h3 class="text-base font-medium">Connection Status</h3>
                    <p>Status: <span class="font-bold">{{ $redisConnected ? 'Connected' : 'Not Connected' }}</span></p>
                    <p>Ping Response: <span class="font-mono">{{ $redisPing }}</span></p>
                </div>

                @if($redisConnected)
                    <div>
                        <h4 class="text-base font-medium mt-4 mb-1">Redis Server Info</h4>
                        <pre class="bg-[#FDFDFC] dark:bg-[#161615] rounded border border-[#e3e3e0] dark:border-[#3E3E3A] p-2 overflow-x-auto text-xs">{{ print_r($redisInfo, true) }}</pre>
                    </div>
                @else
                    <div class="mt-4 text-[#F53003] dark:text-[#FF4433] font-semibold">
                        Unable to connect to Redis. Check your configuration.
                    </div>
                @endif

                <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg pointer-events-none shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

</body>
</html>
