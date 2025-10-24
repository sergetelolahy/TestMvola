<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientModel extends Model
{
    use HasFactory;

    protected $table = 'clients';  
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'tel',
        'cin',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'id_client');
    }
}
