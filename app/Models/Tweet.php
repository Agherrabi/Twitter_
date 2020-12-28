<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable=['content','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getCreatedAtAttribute($date){
        return Carbon::parse($date)->format('d M. Y');
    }
}
