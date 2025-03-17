<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = [

        'district_code',
        'district_name',
    ];
    public function facility()
    {
        return $this->hasMany(Facility::class, 'district_code', 'district_code');
    }
    public function dmo()
    {
        return $this->hasOne(User::class, 'district_code', 'district_code')
        ->where('role', 'DMO');
    }

}
