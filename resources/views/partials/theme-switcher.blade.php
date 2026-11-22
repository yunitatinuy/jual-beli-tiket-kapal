<div class="relative" x-data="{
    open: false,
    theme: localStorage.getItem('theme') || 'system',
    setTheme(val) {
        this.theme = val;
        localStorage.setItem('theme', val);
        this.apply();
        this.open = false;
    },
    apply() {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (this.theme === 'dark' || (this.theme === 'system' && prefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    init() {
        this.apply();
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.theme === 'system') this.apply();
        });
    }
}" @click.outside="open = false">
    {{-- Trigger Button --}}
    <button @click="open = !open" type="button"
        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
        title="Ubah tema: Terang, Gelap, atau Sesuai Device">
        <span class="flex items-center">
            <template x-if="theme === 'light'">
                <i class="bi bi-sun-fill text-amber-500 text-sm"></i>
            </template>
            <template x-if="theme === 'dark'">
                <i class="bi bi-moon-stars-fill text-indigo-400 text-sm"></i>
            </template>
            <template x-if="theme === 'system'">
                <i class="bi bi-display text-[#c8175d] text-sm"></i>
            </template>
        </span>
        <span class="hidden md:inline-block capitalize" x-text="theme === 'light' ? 'Terang' : (theme === 'dark' ? 'Gelap' : 'Auto')"></span>
        <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
    </button>

    {{-- Dropdown Menu --}}
    <div x-cloak x-show="open" x-transition
        class="absolute right-0 mt-2 w-44 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl z-50 dark:border-slate-700 dark:bg-slate-800">
        <div class="px-3 py-1.5 border-b border-slate-100 text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:border-slate-700">
            Pilih Tema Tampilan
        </div>
        <button type="button" @click="setTheme('light')"
            class="flex w-full items-center justify-between px-3.5 py-2 text-xs font-medium transition hover:bg-pink-50 hover:text-[#c8175d] dark:hover:bg-slate-700"
            :class="theme === 'light' ? 'text-[#c8175d] font-bold bg-pink-50/50 dark:bg-slate-700/50' : 'text-slate-700 dark:text-slate-200'">
            <span class="flex items-center gap-2">
                <i class="bi bi-sun-fill text-amber-500 text-sm"></i> Terang (Light)
            </span>
            <i x-show="theme === 'light'" class="bi bi-check2 text-base text-[#c8175d]"></i>
        </button>

        <button type="button" @click="setTheme('dark')"
            class="flex w-full items-center justify-between px-3.5 py-2 text-xs font-medium transition hover:bg-pink-50 hover:text-[#c8175d] dark:hover:bg-slate-700"
            :class="theme === 'dark' ? 'text-[#c8175d] font-bold bg-pink-50/50 dark:bg-slate-700/50' : 'text-slate-700 dark:text-slate-200'">
            <span class="flex items-center gap-2">
                <i class="bi bi-moon-stars-fill text-indigo-400 text-sm"></i> Gelap (Dark)
            </span>
            <i x-show="theme === 'dark'" class="bi bi-check2 text-base text-[#c8175d]"></i>
        </button>

        <button type="button" @click="setTheme('system')"
            class="flex w-full items-center justify-between px-3.5 py-2 text-xs font-medium transition hover:bg-pink-50 hover:text-[#c8175d] dark:hover:bg-slate-700"
            :class="theme === 'system' ? 'text-[#c8175d] font-bold bg-pink-50/50 dark:bg-slate-700/50' : 'text-slate-700 dark:text-slate-200'">
            <span class="flex items-center gap-2">
                <i class="bi bi-display text-[#c8175d] text-sm"></i> Sesuai Device
            </span>
            <i x-show="theme === 'system'" class="bi bi-check2 text-base text-[#c8175d]"></i>
        </button>
    </div>
</div>
