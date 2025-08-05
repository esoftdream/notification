<?php

if (! function_exists('format_mobilephone')) {
    /**
     * Ubah nomor telepon ke format internasional.
     *
     * Format nomor telepon diterima dalam bentuk string.
     * Jika nomor telepon diawali dengan +, maka dianggap sudah dalam format internasional.
     * Jika diawali dengan 0 atau 62, maka akan diubah ke +62.
     *
     * Jika tidak sesuai format yang dikenali, maka akan dilempar exception.
     *
     * @param string $nomor Nomor telepon yang akan diubah.
     *
     * @return string Nomor telepon dalam format internasional.
     *
     * @throws Exception Jika nomor telepon tidak valid.
     */
    function format_mobilephone(string $nomor): string
    {
        $nomor = str_replace([' ', '-', '.'], '', $nomor);

        if ($nomor[0] === '+') {
            return $nomor;
        }

        if ($nomor[0] === '0' && $nomor[1] === '8') {
            return '+62' . substr($nomor, 1);
        }

        if ($nomor[0] === '6' && $nomor[1] === '2') {
            return '+' . $nomor;
        }

        throw new Exception('Nomor telepon tidak valid.', 400);
    }
}
