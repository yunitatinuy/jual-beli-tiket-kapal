<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka rupiah penuh, mis. 505000 -> "Rp505.000".
     */
    function rupiah($amount): string
    {
        if ($amount instanceof \Illuminate\Support\Collection) {
            $amount = $amount->min('Harga') ?? $amount->first()?->Harga ?? 0;
        }

        return 'Rp' . number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}
