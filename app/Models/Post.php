<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id'
    ];

    /**
     * Belongs To Relation - Eloquent Relation For Post user
     *
     * @return void
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

     /**
     * Has Many Relation - Eloquent Relation for Comments
     *
     * @return void
     */
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
