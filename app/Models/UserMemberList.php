<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserMemberList extends Model
{
    protected $table = null; // No need for a specific table

    public static function getData()
    {
        return DB::table('users')
            ->select('users.nin', 'users.name as user_name', 'members.name as member_name', 'users.district')
            ->join('members', 'users.nin', '=', 'members.nin')
            ->selectRaw('users.nin, users.name as user_name, members.name as member_name, users.district')
            ->get();
    }
}
