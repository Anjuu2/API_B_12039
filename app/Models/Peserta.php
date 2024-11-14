<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "pesertas";
    protected $primaryKey = "id";

    protected $fillable = [
        'id_user',
        'id_event',
        'nama',
        'email',
        'telepon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
