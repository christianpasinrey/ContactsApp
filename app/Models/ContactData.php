<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactData extends Model
{
    use HasFactory;

    protected $table = 'contacts_data';

    CONST TYPE_EMAIL = 'email';
    CONST TYPE_PHONE = 'phone';
    CONST TYPE_LINKED = 'linkedin';
    CONST TYPE_FACEBOOK = 'facebook';
    CONST TYPE_INSTAGRAM = 'instagram';

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'label',
        'type',
        'value',
        'is_main',
    ];

    protected $visible = [
        'id',
        'label',
        'type',
        'value',
        'is_main',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
