<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['linkedin','instagram','facebook','phones','emails'];

    protected $with = ['contacts'];

    public function contactData()
    {
        return $this->morphMany(ContactData::class, 'contactable');
    }

    public function contacts()
    {
        return $this->belongsToMany(
            User::class,
            'user_has_contacts',
            'user_id',
            'contact_id'
        );
    }

    public function getLinkedinAttribute()
    {
        $linkedIn = $this->contactData->where('type', ContactData::TYPE_LINKED)->first();
        if( $linkedIn == null){
            return null;
        }
        return $linkedIn->value;
    }

    public function getInstagramAttribute()
    {
        $instagram = $this->contactData->where('type', ContactData::TYPE_INSTAGRAM)->first();

        if( $instagram == null){
            return null;
        }
        return $instagram->value;
    }

    public function getFacebookAttribute()
    {
        $facebook = $this->contactData->where('type', ContactData::TYPE_FACEBOOK)->first();
        if( $facebook == null){
            return null;
        }
        return $facebook->value;
    }

    public function getPhonesAttribute()
    {
        return $this->contactData->where('type', ContactData::TYPE_PHONE);
    }

    public function getEmailsAttribute()
    {
        return $this->contactData->where('type', ContactData::TYPE_EMAIL);
    }
}
