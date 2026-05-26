<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Control;

class AktuatorController extends Controller
{
    // ======================
    // GET FAN MODE
    // ======================

    public function getFanMode()
    {
        $control = Control::first();

        return response()->json([
            'fan_mode' => $control->fan_mode
        ]);
    }

    // ======================
    // UPDATE FAN MODE
    // ======================

    public function updateFanMode($mode)
    {
        $control = Control::first();

        $control->fan_mode = $mode;

        $control->save();

        return back();
    }

    // ======================
    // GET HEATER SPREAD MODE
    // ======================

    public function getHeaterSpreadMode()
    {
        $control = Control::first();

        return response()->json([
            'heater_spread_mode' =>
            $control->heater_spread_mode
        ]);
    }

    // ======================
    // UPDATE HEATER SPREAD MODE
    // ======================

    public function updateHeaterSpreadMode($mode)
    {
        $control = Control::first();

        $control->heater_spread_mode = $mode;

        $control->save();

        return back();
    }
}