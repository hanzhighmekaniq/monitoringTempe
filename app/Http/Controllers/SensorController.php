<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Control;
use App\Services\FuzzyService;

class SensorController extends Controller
{
    public function store(
        Request $request,
        FuzzyService $fuzzy
    )
    {

        $request->validate([
            'temperature' => 'required|numeric|between:0,60',
            'humidity' => 'required|numeric|between:0,100'
        ]);

        $control = Control::first();

        $fanPwm = null;
        $heaterPwm = null;

        if (
            $control &&
            $control->system_mode === 'auto'
        ) {

            $result = $fuzzy->calculate(
                $request->temperature,
                $request->humidity
            );

            $fanPwm = round($result['fan_pwm']);
            $heaterPwm = round($result['heater_pwm']);

            $control->update([
                'fan_pwm' => round($result['fan_pwm']),
                'heater_pwm' => round($result['heater_pwm']),
            ]);
        }

        SensorData::create([
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'fan_pwm' => $fanPwm,
            'heater_pwm' => $heaterPwm
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}