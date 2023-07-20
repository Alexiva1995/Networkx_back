<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Liquidaction extends Model
{
    use HasFactory;
    protected $table = 'liquidactions';

    protected $fillable = [
        'user_id', 'membership_id', 'total', 'monto_bruto', 'feed', 'hash',
        'wallet_used', 'status', 'code_correo', 'fecha_code', 'type','reference',
        'secret', 'processId'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(PackageMembership::class, 'membership_id');
    }
    public function wallet()
    {
        return Crypt::decrypt($this->wallet_used);
    }
    public function getStatus()
    {
        $status = 'inactive';
        
        switch ($this->status) {
            case 0:
                $status = 'Pending';
                break;
            case 1:
                $status = 'Approved';
                break;
            case 2:
                $status = 'Rejected';
                break;
        }

        return $status;
    }
}
