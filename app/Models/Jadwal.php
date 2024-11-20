<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "jadwal";
    protected $primaryKey = "id";

    protected $fillable = [
        'id_event',
        'judul_sesi',
        'deskripsi_sesi',
        'waktu_mulai',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jadwal) {
            $jadwal->waktu_selesai = date('Y-m-d H:i:s', strtotime($jadwal->waktu_mulai . ' +1 day'));
        });
    }
}
