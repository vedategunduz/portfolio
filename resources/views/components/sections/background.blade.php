<div class="relative overflow-hidden min-h-screen">
    <div class="absolute inset-0 z-0 scroll-parallax-bg" aria-hidden="true">
        <div class="blob blob-1 absolute -top-32 -left-32 w-96 h-96 md:w-150 md:h-150 lg:w-175 lg:h-175 rounded-full bg-linear-to-br from-purple-500/35 to-cyan-400/25 blur-3xl pointer-events-none"></div>

        <div class="blob blob-2 absolute -top-20 -right-40 w-80 h-80 md:w-150 md:h-125 lg:w-150 lg:h-150 rounded-full bg-linear-to-br from-pink-500/30 to-amber-400/20 blur-[120px] pointer-events-none"></div>

        <div class="blob blob-3 absolute top-1/3 -left-48 w-72 h-72 md:w-112.5 md:h-112.5 lg:w-137.5 lg:h-137.5 rounded-full bg-linear-to-br from-cyan-400/25 to-purple-500/30 blur-3xl pointer-events-none"></div>

        <div class="blob blob-4 absolute top-1/2 -right-32 w-96 h-96 md:w-137.5 md:h-137.5 lg:w-162.5 lg:h-162.5 rounded-full bg-linear-to-br from-amber-400/20 to-pink-500/25 blur-[120px] pointer-events-none"></div>

        <div class="blob blob-5 absolute -bottom-40 -left-20 w-80 h-80 md:w-150 md:h-125 lg:w-150 lg:h-150 rounded-full bg-linear-to-br from-purple-600/30 to-cyan-500/20 blur-3xl pointer-events-none"></div>
    </div>

    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
