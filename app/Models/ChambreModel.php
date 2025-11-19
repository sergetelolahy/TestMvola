<?php

namespace App\Models;


use App\Models\TypeChambreModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function services()
    {
        return $this->belongsToMany(ServiceModel::class, 'chambre_service','chambre_id', 'service_id');
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            ReservationModel::class, // Utiliser ReservationModel, pas Reservation
            'chambre_reservation',   // Nom de la table pivot
            'chambre_id',            // Clé étrangère de chambre dans la table pivot
            'reservation_id'         // Clé étrangère de réservation dans la table pivot
        )->withPivot(['date_debut', 'date_fin', 'prix'])
         ->withTimestamps();
    }
    
}