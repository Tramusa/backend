<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory; 
              
    protected $fillable = [ 'trip_order', 'ceco', 'customer', 'name', 'mail', 'application_medium',  'phone', 'position', 'date', 'hour', 'origin', 'p_intermediate', 'p_authorized', 'destination', 'type', 'detaills' , 'operator', 'user', 'lost_signal', 'no_signal_time'
        , 'signal_recovery', 'passenger_list', 'passengers_check', 'missing_passengers', 'passengers_out', 'invoice_folio', 'breathalyzer_test', 'confirmation_start_trip', 'hours_sleep', 'driving_log', 'status', 'reason', 'end_date', 'end_hour', 'part_hour', 'travel_time'];

    // Relación con puntos de interés (origen)
    public function originPoint()
    {
        return $this->belongsTo(PointsInterest::class, 'origin');
    }

    // Relación con puntos de interés (destino)
    public function destinationPoint()
    {
        return $this->belongsTo(PointsInterest::class, 'destination');
    }

    // Relación con unidades de viaje
    public function units()
    {
        return $this->hasMany(Units_Trips::class, 'trip');
    }

    // Relación con cliente
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer');
    }
}
