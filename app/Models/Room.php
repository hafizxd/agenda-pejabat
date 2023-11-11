<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agenda;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function agendas() {
        return $this->hasMany(Agenda::class);
    }
}
