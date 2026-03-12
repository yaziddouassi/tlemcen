<?php

namespace Tlemcen\Tlemcen\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezvousClient extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'user_id',
      'usernom',
      'userprenom',
      'usertelephone',
      'usermail',
      'useradresse',
    ];
}