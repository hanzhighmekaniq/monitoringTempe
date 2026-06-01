<?php

namespace App\Services;

class FuzzyService
{
    public function calculate(float $temperature, float $humidity): array
    {
        // =====================================================
        // FUZZIFIKASI SUHU
        // =====================================================

        $tempDingin = $this->trapmf(
            $temperature,
            20,
            20,
            29,
            31
        );

        $tempNormal = $this->trimf(
            $temperature,
            29,
            31,
            34
        );

        $tempPanas = $this->trapmf(
            $temperature,
            31,
            34,
            40,
            40
        );

        // =====================================================
        // FUZZIFIKASI KELEMBAPAN
        // =====================================================

        $humKering = $this->trapmf(
            $humidity,
            0,
            0,
            69,
            74
        );

        $humNormal = $this->trimf(
            $humidity,
            69,
            74,
            79
        );

        $humLembab = $this->trapmf(
            $humidity,
            74,
            79,
            100,
            100
        );

        // =====================================================
        // RULE BASE
        // =====================================================

        $r1 = min($tempDingin, $humKering);
        $r2 = min($tempDingin, $humNormal);
        $r3 = min($tempDingin, $humLembab);

        $r4 = min($tempNormal, $humKering);
        $r5 = min($tempNormal, $humNormal);
        $r6 = min($tempNormal, $humLembab);

        $r7 = min($tempPanas, $humKering);
        $r8 = min($tempPanas, $humNormal);
        $r9 = min($tempPanas, $humLembab);

        // =====================================================
        // AGREGASI FAN
        // =====================================================

        $fanMati = max($r2, $r3, $r5, $r6);
        $fanLambat = max($r1, $r4);
        $fanCepat = max($r7, $r8, $r9);

        // =====================================================
        // AGREGASI HEATER
        // =====================================================

        $heaterMati = max(
            $r4,
            $r5,
            $r7,
            $r8,
            $r9
        );

        $heaterLambat = max(
            $r1,
            $r6
        );

        $heaterTerang = max($r2, $r3);

        // =====================================================
        // TITIK TENGAH OUTPUT
        // =====================================================

        $FAN_MATI = 63.75;
        $FAN_LAMBAT = 127.50;
        $FAN_CEPAT = 191.25;

        $HEATER_MATI = 63.75;
        $HEATER_LAMBAT = 127.50;
        $HEATER_TERANG = 191.25;

        // =====================================================
        // DEFUZZIFIKASI
        // =====================================================

        $fanPwm = (
            ($fanMati * $FAN_MATI) +
            ($fanLambat * $FAN_LAMBAT) +
            ($fanCepat * $FAN_CEPAT)
        ) / max(
            ($fanMati + $fanLambat + $fanCepat),
            0.0001
        );

        $heaterPwm = (
            ($heaterMati * $HEATER_MATI) +
            ($heaterLambat * $HEATER_LAMBAT) +
            ($heaterTerang * $HEATER_TERANG)
        ) / max(
            ($heaterMati + $heaterLambat + $heaterTerang),
            0.0001
        );

        return [
            'fan_pwm' => round($fanPwm, 2),
            'heater_pwm' => round($heaterPwm, 2),

            'fan_mati' => round($fanMati, 3),
            'fan_lambat' => round($fanLambat, 3),
            'fan_cepat' => round($fanCepat, 3),

            'heater_mati' => round($heaterMati, 3),
            'heater_lambat' => round($heaterLambat, 3),
            'heater_terang' => round($heaterTerang, 3),
        ];
    }

    // =====================================================
    // TRIANGULAR MEMBERSHIP FUNCTION
    // =====================================================

    private function trimf(
        float $x,
        float $a,
        float $b,
        float $c
    ): float {

        if ($x <= $a || $x >= $c) {
            return 0;
        }

        if ($x == $b) {
            return 1;
        }

        if ($x < $b) {
            return ($x - $a) / ($b - $a);
        }

        return ($c - $x) / ($c - $b);
    }

    // =====================================================
    // TRAPEZOIDAL MEMBERSHIP FUNCTION
    // =====================================================

    private function trapmf(
        float $x,
        float $a,
        float $b,
        float $c,
        float $d
    ): float {

        if ($x <= $a) {
            return ($a == $b) ? 1 : 0;
        }

        if ($x >= $d) {
            return ($c == $d) ? 1 : 0;
        }

        if ($x >= $b && $x <= $c) {
            return 1;
        }

        if ($x > $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        }

        if ($x > $c && $x < $d) {
            return ($d - $x) / ($d - $c);
        }

        return 0;
    }
}