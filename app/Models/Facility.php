<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    protected $fillable =[

        'nin',
        'proposed_date',
        'name',
        'facility_type',
        'district_code',
        'block_name',
        'incharge',
        'image',
        'status',
    ];

    public function user()
{
    return $this->hasOne(User::class, 'nin', 'nin');
}
public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }

public function member()
{
    return $this->hasMany(AhwcMember::class, 'nin', 'nin');
}

public function indicator()
{
    return $this->hasMany(Indicator::class, 'nin', 'nin');
}
}
