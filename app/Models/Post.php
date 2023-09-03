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

    protected $with = ['files','comments','mentionedUsers'];

    protected $appends = ['liked','reposted','reposted_count','likes_count'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function mentionedUsers(){
        return $this->belongsToMany(
            User::class,
            'post_has_mentions',
            'post_id',
            'mentioned_user_id'
        );
    }

    public function repostedByUsers(){
        return $this->belongsToMany(
            User::class,
            'user_reposts',
            'post_id',
            'user_id'
        );
    }

    public function likedByUsers(){
        return $this->belongsToMany(
            User::class,
            'user_likes_posts',
            'post_id',
            'user_id'
        );
    }

    public function getLikedAttribute(){
        return $this->likedByUsers()->where('user_id', auth()->user()->id)->exists();
    }

    public function getLikesCountAttribute(){
        return $this->likedByUsers()->count();
    }

    public function getRepostedAttribute(){
        return $this->repostedByUsers()->where('user_id', auth()->user()->id)->exists();
    }

    public function getRepostedCountAttribute(){
        return $this->repostedByUsers()->count();
    }
}
