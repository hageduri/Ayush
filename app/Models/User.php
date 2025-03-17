<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    //test

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return $this->Role === 'Admin'||$this->Role === 'DMO'||$this->Role === 'MO';
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     **/
    protected $fillable = [
        'name',
        'image',
        'email',
        'password',
        'nin',
        'role',
        'gender',
        'dob',
        'bank_name',
        'account_no',
        'ifsc_code',
        'district_code',
        'address',
        'contact_1',
        'contact_2',
        'status',
        'designation',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function facility()
{
    return $this->belongsTo(Facility::class, 'nin', 'nin');
}
public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
    public function canAccessPanel(Panel $panel): bool
{
    return $this->status === '0'; // Allow only active users
}

}
