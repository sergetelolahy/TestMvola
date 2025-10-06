<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;
    protected $fillable = ['numero', 'type_chambre_id', 'statut'];

    public function typeChambre()
    {
        return $this->belongsTo(TypeChambre::class);
    }
}
