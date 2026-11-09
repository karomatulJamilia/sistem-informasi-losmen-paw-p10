<?php
function validasiRentangTanggal(string $checkin, string $checkout): array {
    $errors = [];
    $in  = DateTimeImmutable::createFromFormat('!Y-m-d', $checkin);
    $out = DateTimeImmutable::createFromFormat('!Y-m-d', $checkout);

    if (!$in || $in->format('Y-m-d') !== $checkin) {
        $errors['checkin'] = 'Tanggal check-in tidak valid.';
    }
    if (!$out || $out->format('Y-m-d') !== $checkout) {
        $errors['checkout'] = 'Tanggal check-out tidak valid.';
    }
    if ($in && $out && $out <= $in) {
        $errors['checkout'] = 'Check-out harus setelah check-in.';
    }
    return $errors;
}