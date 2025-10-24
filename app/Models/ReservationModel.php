<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationModel extends Model
{
    use HasFactory;
    
    protected $table = 'reservations'; 
    protected $fillable = [
        'id_client',
        'id_chambre',
        'date_debut',
        'date_fin',
        'statut',
        'tarif_template',
        'date_creation',
        'check_in_time',
        'check_out_time'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_creation' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'id_client');
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(ChambreModel::class, 'id_chambre');
    }

    // Scopes utiles
    public function scopeActive($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeConfirmee($query)
    {
        return $query->where('statut', 'confirmée');
    }

    // Méthodes métier
    public function peutCheckIn(): bool
    {
        return $this->statut === 'confirmée' && is_null($this->check_in_time);
    }

    public function peutCheckOut(): bool
    {
        return !is_null($this->check_in_time) && is_null($this->check_out_time);
    }

    public function effectuerCheckIn(): bool
    {
        if ($this->peutCheckIn()) {
            $this->update([
                'check_in_time' => now(),
                'statut' => 'en_cours'
            ]);
            return true;
        }
        return false;
    }

    public function effectuerCheckOut(): bool
    {
        if ($this->peutCheckOut()) {
            $this->update([
                'check_out_time' => now(),
                'statut' => 'terminée'
            ]);
            return true;
        }
        return false;
    }

    public function dureeSejour(): int
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInDays($this->check_out_time);
        }
        return 0;
    }

    public function paiements()
    {
        return $this->hasMany(PaiementModel::class, 'id_reservation');
    }
}
