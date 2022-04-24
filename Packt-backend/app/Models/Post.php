<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_title',
        'post_body',
        'user_id'
    ];

    // Post relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
