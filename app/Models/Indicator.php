<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;
    protected $fillable = [
        'nin',
        'district_code',
        'indicator_1',
        'indicator_2',
        'indicator_3',
        'indicator_4',
        'indicator_5',
        'indicator_6',
        'indicator_7',
        'indicator_8',
        'indicator_9',
        'indicator_10',
        'month',
        'status',
        'remarks',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'nin', 'nin');
    }
}
