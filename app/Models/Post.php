<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='posts';

    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];

    protected $with = ['files','comments'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }
}
