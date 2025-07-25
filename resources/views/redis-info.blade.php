<x-header />
       
<x-top-menu />


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

            {{-- Valkey Status Panel --}}
            <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden p-6 flex flex-col">
                <h2 class="text-lg font-semibold mb-2">Valkey Configuration</h2>

                <div class="mb-2">
                    <h3 class="text-base font-medium">Connection Status</h3>
                    <p>Status: <span class="font-bold">{{ $redisConnected ? 'Connected' : 'Not Connected' }}</span></p>
                    <p>Ping Response: <span class="font-mono">{{ $redisPing }}</span></p>
                </div>

                @if($redisConnected)
                    <div>
                        <h4 class="text-base font-medium mt-4 mb-1">Valkey Server Info</h4>
                        <pre class="bg-[#FDFDFC] dark:bg-[#161615] rounded border border-[#e3e3e0] dark:border-[#3E3E3A] p-2 overflow-x-auto text-xs">{{ print_r($redisInfo, true) }}</pre>
                    </div>
                @else
                    <div class="mt-4 text-[#F53003] dark:text-[#FF4433] font-semibold">
                        Unable to connect to Valkey. Check your configuration.
                    </div>
                @endif

                <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg pointer-events-none shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
            </div>

<x-footer />