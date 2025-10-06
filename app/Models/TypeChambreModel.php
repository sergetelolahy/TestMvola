<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeChambreModel extends Model
{
    use HasFactory;
    protected $table = 'typechambres';
    protected $fillable = ["nom", "nbrLit","maxPersonnes","description"];
}
