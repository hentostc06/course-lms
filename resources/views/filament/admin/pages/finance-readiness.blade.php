<x-filament-panels::page>
    <div role="status" class="rounded-lg border border-amber-400 bg-amber-50 p-5 text-amber-950 dark:bg-amber-950 dark:text-amber-100">
        <strong>NOT_LIVE — Pembayaran belum diaktifkan</strong>
        <p>Belum ada gateway produksi, transaksi, refund atau payout. Halaman ini bukan laporan keuangan.</p>
    </div>

    <x-filament::section heading="Keputusan sebelum go-live">
        <div class="grid gap-3 text-sm">
            <p>D02 — Komisi marketplace dan promosi (klien).</p>
            <p>D03 — Payment gateway, merchant dan KYC (klien/provider).</p>
            <p>D04 — Biaya gateway, pajak, settlement (klien/akuntan).</p>
            <p>D05 — Refund dan chargeback (klien).</p>
            <p>D06 — Ambang payout dan penahanan dana (klien/provider).</p>
        </div>

        <p class="mt-4">
            Setelah disetujui: ledger append-only, verifikasi webhook,
            idempotensi, rekonsiliasi, baru payout/refund.
        </p>
    </x-filament::section>
</x-filament-panels::page>
