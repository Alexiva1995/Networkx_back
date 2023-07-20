<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WalletComission extends Model
{
    use HasFactory;
    protected $table = 'wallets_commissions';
    
    protected $fillable = [
        'user_id',
        'buyer_id',
        'level',
        'description',
        'membership_id',
        'amount',
        'amount_retired',
        'amount_available',
        'amount_last_liquidation',
        'type',
        'liquidation_id',
        'status',
        'available_withdraw',
        'order_id',
        'liquidado',
        'father_cyborg_purchased_id'
    ];
    const STATUS_AVAILABLE = 0;
    const STATUS_PENDING = 4;
    const STATUS_PAID = 2;

    const TYPE_MATRIX20 = 0;
    const TYPE_MATRIX200 = 1;
    const TYPE_MATRIX2000= 2;

    // protected function status(): Attribute {
    //     return new Attribute(
    //         get: fn($value) => ['Available', 'Requested', 'Paid', 'Voided', 'Subtracted'][$value],
    //     );
    // }

    public function scopeFilter($query, $filter)
    {
        if($filter) {
            $query->where('description', 'LIKE', "%$filter%");
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    /**
     * Permite obtener la orden de esta comision
     * @return void
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function project()
    {
        return $this->order->project ?? null;
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'membership_id', 'id');
    }

    /**
     * Permite obtener al usuario de una comision
     * @return void
     */
    public function getWalletUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Permite obtener al referido de una comision
     * @return void
     */
    public function getWalletReferred()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }
    public function getStatus()
    {
        switch ($this->status) {
            case 0:
                return 'Available';
            case 1:
                return 'Requested';
            case 2:
                return 'Paid';
            case 3:
                return 'Voided';
        }
    }
    public function englishDescription()
    {
        switch ($this->description) {
            case 'Bono directo':
                return 'Direct Bonus';
            case 'Bono asignado':
                return 'Assigned bonus';
            case 'Bono Unilevel':
                return 'Bono Unilevel';
        }
    }
}
