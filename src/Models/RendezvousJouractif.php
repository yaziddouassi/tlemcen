<?php

namespace Tlemcen\Tlemcen\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezvousJouractif extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jour',
        'mois',
        'annee', 
        'nbheuredispo',
        'nbheureserve',
        'journee',
        'ladate',
        'status',
    ];
}
