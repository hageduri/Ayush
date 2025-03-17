<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
        <!-- Styles -->
        @vite('resources/css/app.css')
        <script src="//unpkg.com/alpinejs" defer></script>

    </head>
    <body class="font-sans antialiased text-[#1427cf] ">

        {{-- <div class="bg-cover bg-fixed bg-center bg-no-repeat h-screen px-2 text-red-300  dark:text-white">
            <!-- Content here -->
            This is my background with dark and light mode support.
        </div> --}}
        <div  class="bg-cover bg-fixed bg-center bg-no-repeat  bg-lgt dark:bg-drk">
            <!-- Nav here -->
            <nav x-data="{ dropdownOpen: false }" class="px-5 py-3 flex justify-between items-center">
                <div class=" font-bold text-2xl text-blue-900 dark:text-white">
                    AAMS
                </div>

                <div class="hidden md:block">
                    <!-- Content here will be hidden on mobile and visible on medium screens (md) and larger -->
                    <ul class="flex list-none gap-8 dark:text-white">
                        <li class="hover:scale-110 duration-150"><a href="">Home</a></li>
                        <li class="hover:scale-110 duration-150"><a href="#about">About</a></li>
                        <li class="hover:scale-110 duration-150"><a href="">Statistics</a></li>
                        <li class="hover:scale-110 duration-150"><a href="{{route('filament.admin.auth.login')}}">Login</a></li>
                    </ul>
                </div>


                {{-- mobile view --}}
                <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false" class=" md:hidden dark:text-white text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>

                    <ul x-show="dropdownOpen"
                    x-transition:enter="transition transform ease-out duration-300"
                    x-transition:enter-start="translate-x-full opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition transform ease-in duration-200"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-full opacity-0"

                    class="absolute h-full right-0 rounded-md dark:bg-gray-900/90 bg-white p-5 text-lg text-left">
                        <li class="hover:scale-110 duration-150 p-1"><a href="">Home</a></li>
                        <li class="hover:scale-110 duration-150 p-1"><a href="#about">About</a></li>
                        <li class="hover:scale-110 duration-150 p-1"><a href="">Statistics</a></li>
                        <li class="hover:scale-110 duration-150 p-1"><a href="{{route('filament.admin.auth.login')}}">Login</a></li>
                    </ul>
                </button>




            </nav>


            {{-- Quote --}}
            <div class="flex-col items-center justify-center  text-center dark:text-white mt-10">
                <p class="text-3xl md:text-6xl">
                    Your expertise,
                </p>
                <p class="text-3xl md:text-6xl">our commitment building a healthier future.</p>
            </div>

            {{-- image --}}
            <div class="h-[200px] md:h-[400px] overflow-hidden flex items-center justify-center gap-2">
                <img class="h-full object-cover" src="background/bg3.png" alt="">
            </div>

            {{-- login button --}}
            <div class=" flex justify-center items-center ">
                <a class="px-5 py-2 rounded-md bg-blue-900 hover:bg-blue-600
                 dark:bg-blue-200 dark:hover:bg-blue-300 dark:text-blue-800 hover:scale-110 duration-150 text-white font-semibold text-md"
                href="{{route('filament.admin.auth.login')}}">Login</a>
            </div>

            {{-- About --}}
            <div id="about" class="md:p-10 p-3 mt-20 dark:text-white">
                <p class="font-bold md:text-2xl md:p-2">About</p>
                <p class="md:text-xl md:p-2 text-justify text-sm">Welcome to Arunachal Ayushman Arogya Mandir (AAAM)—a dedicated portal for streamlined data entry related to treatments at Ayush medical facilities. Through AAAM, we ensure that all medical records are accurately maintained and provide a reliable basis for awarding incentives to our hardworking medical officers and team members. This system promotes efficient healthcare management and recognizes the commitment of those serving the community through Ayush facilities.</p>
            </div>

            {{-- <footer class="bg-blue-900">
                <div class="p-4 text-white">

                    my link jo v hai
                </div>
            </footer> --}}





        </div>


    </body>
</html>
