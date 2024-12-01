<?php

if (!function_exists('terbilang')) {
    function terbilang($number)
    {
        $number = abs($number);
        $words = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";

        if ($number < 12) {
            $temp = " " . $words[$number];
        } elseif ($number < 20) {
            $temp = terbilang($number - 10) . " Belas";
        } elseif ($number < 100) {
            $temp = terbilang($number / 10) . " Puluh" . terbilang($number % 10);
        } elseif ($number < 200) {
            $temp = " Seratus" . terbilang($number - 100);
        } elseif ($number < 1000) {
            $temp = terbilang($number / 100) . " Ratus" . terbilang($number % 100);
        } elseif ($number < 2000) {
            $temp = " Seribu" . terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $temp = terbilang($number / 1000) . " Ribu" . terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $temp = terbilang($number / 1000000) . " Juta" . terbilang($number % 1000000);
        } else {
            $temp = terbilang($number / 1000000000) . " Miliar" . terbilang(fmod($number, 1000000000));
        }

        return trim($temp);
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}

if (!function_exists('unformatNumber')) {
    function unformatNumber($number)
    {
        // Hapus titik dan mengubah koma menjadi titik desimal
        return (float) str_replace('.', '', $number); 
    }
}
