<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'lastname',
        'middlename',
        'email',
        'phone',
        'photo',
        'is_send_notifications',
        'birthdate',
        'ref_sex',
        'kindred_spirit',
        'password',
        'remember_token',
        'forgot_token',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $dates = [
        'birthdate',
        'email_verified_at',
        'phone_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'forgot_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sex()
    {
        return $this->belongsTo(RefSex::class, 'ref_sex');
    }
}
