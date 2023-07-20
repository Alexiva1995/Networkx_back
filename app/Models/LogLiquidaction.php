<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogLiquidaction extends Model
{
    use HasFactory;
    protected $fillable = ['liquidation_id', 'comentario', 'accion'];

    public function liquidation()
    {
        return $this->belongsTo(Liquidation::class);
    }
}
