<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhwcMember extends Model
{
    use HasFactory;
    protected $fillable = [

        'nin',
        'name',
        'image',
        'gender',
        'dob',
        'address',
        'contact_1',
        'contact_2',
        'email',
        'role',
        'bank_name',
        'district_code',
        'account_no',
        'ifsc_code',
        'status',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'nin', 'nin');
    }
}
