<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use Uuid;

    protected $table = 'permintaan';

    protected $guarded = [];

    protected $casts = [
        'detail_paket' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');

    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'jaminan', 'id');
    }
}
