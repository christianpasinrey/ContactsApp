<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    CONST TYPE_IMAGE = 'image';
    CONST TYPE_VIDEO = 'video';
    CONST TYPE_AUDIO = 'audio';
    CONST TYPE_DOCUMENT = 'document';

    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'type',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function fileable(){
        return $this->morphTo();
    }
}
