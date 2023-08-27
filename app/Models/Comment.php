<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='comments';

    protected $fillable = [
        'body',
        'user_id',
        'post_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }
}
