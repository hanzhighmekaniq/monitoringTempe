<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $data = SensorData::create([
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $data
        ]);
    }
}