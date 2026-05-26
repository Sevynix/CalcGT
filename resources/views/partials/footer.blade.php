            <!-- Global Footer embedded within scroll to ensure it stays below content -->
            <footer class="bg-white border-t border-slate-200 py-6 px-4 md:px-8 shrink-0 w-full mt-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Left: Copyright -->
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-slate-500 font-medium tracking-wide">&copy; {{ date('Y') }} Growtopia Calculator. All rights reserved.</p>
                    </div>

                    <!-- Right: Links -->
                    <div class="flex items-center gap-4 text-sm font-semibold text-slate-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition-colors duration-200">Home</a>
                        <span class="text-slate-200">|</span>
                        <a href="https://discord.gg/zzg7bJjzDH" target="_blank" rel="noopener noreferrer" class="hover:text-brand-500 transition-colors duration-200">Discord</a>
                        <span class="text-slate-200">|</span>
                        <span class="text-slate-400">Made by <span class="font-bold text-slate-500">Sevynix</span></span>
                    </div>
                </div>
            </footer>
