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
    
    protected $fillable = ['name', 'a_paterno', 'a_materno', 'alias', 'email', 'password', 'active', 'rol', 'avatar', 'signature'];
    
    protected $hidden = [ 'password', 'remember_token',];
   
    protected $casts = [ 'email_verified_at' => 'datetime', ];
}
