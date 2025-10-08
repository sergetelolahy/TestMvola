<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeChambreModel extends Model
{
    use HasFactory;
    protected $table = 'typechambres';
    protected $fillable = ["nom", "nbrLit","maxPersonnes","description"];

    public function chambres(): HasMany
    {
        return $this->hasMany(ChambreModel::class, 'typechambre_id');
    }
}
