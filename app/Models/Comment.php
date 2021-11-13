<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_id'
    ];

    /**
     * Belongs To Relation - Eloquent Relation For Comment user
     *
     * @return void
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs To Relation - Eloquent Relation For Comment post
     *
     * @return void
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }
}
