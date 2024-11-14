<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;
    protected $table = "users";
    protected $primaryKey = "id";

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }
}
