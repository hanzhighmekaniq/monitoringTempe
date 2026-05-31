<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    protected $fillable = [
        'fan_mode',
        'heater_spread_mode',
        'system_mode',
        'fan_pwm',
        'heater_pwm'
    ];
}
