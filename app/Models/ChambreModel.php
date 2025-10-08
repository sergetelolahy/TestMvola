<?php

namespace App\Models;


use App\Models\TypeChambreModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChambreModel extends Model
{
    use HasFactory;
    protected $table = 'chambres';
    protected $fillable = ['numero', 'prix','typechambre_id'];

    // Relation : une chambre appartient à un type de chambre
    public function typeChambre(): BelongsTo
    {
        return $this->belongsTo(TypeChambreModel::class, 'typechambre_id', 'id');
    }
    
}