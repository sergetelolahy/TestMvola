<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;

    protected $table = "services";
    protected $fillable = ["nom", "description"];

    public function chambres()
    {
        return $this->belongsToMany(ChambreModel::class, 'chambre_service','chambre_id', 'service_id');
    }
}
