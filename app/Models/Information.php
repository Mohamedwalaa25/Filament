<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    //protected $table = "information";

    protected $fillable=[
        'title',
        'phone',
        'address',
        'social_media',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ScopeFilter(){
        return Information::query()->wehere('user_id',  null);
    }
}
