<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka rupiah penuh, mis. 505000 -> "Rp505.000".
     */
    function rupiah($amount): string
    {
        return 'Rp' . number_format((int) $amount, 0, ',', '.');
    }
}
