<nav x-data="{ open: false }" class="sm:mb-5 mb-2">
    <!-- Primary Navigation Menu -->
    <div class="sm:mx-6 h-16">
        <div class="sm:h-2"></div>
        <div class="navbar bg-base-100 mb-40 shadow-xl rounded-box justify-between border-primary border-2 border-opacity-20 hover:border-opacity-30">
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                </a>
            </div>

            {{-- Responsive MENU:MAXI --}}
            <div class="hidden sm:flex">
                <x-nav-links />
            </div>
            {{-- Responsive MENU:MINI --}}
            <div class="sm:hidden flex-none">
                {{ __('Menu') }}
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-square btn-ghost m-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                        <x-nav-links :li="true" />
                    </ul>
                </div>
            </div>
            {{-- END Responsive MENU --}}

            <div class="flex-none">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar online placeholder @role(\App\Enums\RoleName::TEACHER) ring ring-primary @endrole">
                        <div class="bg-neutral-focus text-neutral-content rounded-full w-10">
                            <span class="text-xl">{{ Auth::user()->getInitials() }}</span>
                        </div>
                    </label>
                    <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <x-logout-link />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
