<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementModel extends Model
{
    use HasFactory;

    // Nom de la table (facultatif si le nom correspond à la convention)
    protected $table = 'paiements';

    // Champs autorisés à être remplis
    protected $fillable = [
        'id_reservation',
        'montant',
        'date_paiement',
        'mode_paiement',
        'status',
    ];

    // Relation avec Reservation (plusieurs paiements pour une seule réservation)
    public function reservation()
    {
        return $this->belongsTo(ReservationModel::class, 'id_reservation');
    }
}
