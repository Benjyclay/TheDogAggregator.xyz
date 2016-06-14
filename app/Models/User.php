<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class User extends Model
{
	protected $table = 'users';
	
    public static function insertUserIntoDB($ip)
    {
        \DB::table('users')->insert([
            'ip_address' => $ip,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return;
    }
}
