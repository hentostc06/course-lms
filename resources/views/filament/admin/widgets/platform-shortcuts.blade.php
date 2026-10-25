<x-filament-widgets::widget>
    <x-filament::section heading="Operasional marketplace" description="Kelola konten dan kualitas kelas dari satu tempat.">
        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ \App\Filament\Admin\Pages\ContentOperations::getUrl() }}" class="block rounded-lg border border-gray-300 p-5 hover:border-orange-600 dark:border-gray-700">
                <p class="text-base font-semibold">Moderasi & kategori</p><p class="mt-2 text-sm text-gray-500">Review instruktur, review kursus, serta mengelola kategori.</p>
            </a>
            <a href="{{ url('/courses') }}" class="block rounded-lg border border-gray-300 p-5 hover:border-orange-600 dark:border-gray-700">
                <p class="text-base font-semibold">Lihat katalog publik</p><p class="mt-2 text-sm text-gray-500">Pastikan hasil publikasi tampil sesuai kurikulum.</p>
            </a>
            @if(auth()->user()?->hasRole('super_admin'))
                <a href="{{ \App\Filament\Admin\Pages\UserMonitoring::getUrl() }}" class="block rounded-lg border border-gray-300 p-5 hover:border-orange-600 dark:border-gray-700">
                    <p class="text-base font-semibold">Monitoring pengguna</p><p class="mt-2 text-sm text-gray-500">Terbatas pada superadmin: sesi dan aktivitas akun.</p>
                </a>
                <a href="{{ \App\Filament\Admin\Pages\AdminAccounts::getUrl() }}" class="block rounded-lg border border-gray-300 p-5 hover:border-orange-600 dark:border-gray-700">
                    <p class="text-base font-semibold">Kelola administrator</p><p class="mt-2 text-sm text-gray-500">Tambah admin, reset password admin, ubah password pribadi.</p>
                </a>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
