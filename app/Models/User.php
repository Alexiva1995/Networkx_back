<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'email',
        'phone',
        'code_security',
        'status_change',
        'code_verified_at',
        'prefix_id',
        'buyer_id',
        'binary_id',
        'binary_side',
        'user_name',
        'nickname_referral_link',
        'name',
        'last_name',
        'admin',
        'status',
        'token_auth',
        'token_jwt',
        'email_verified_at',
        'matrix_level',
        'wallet',
        'kyc',
        'can_buy_fast',
        'profile_picture',
        'father_cyborg_purchased_id',
        'type_service',
    ];

    // protected $with = [
    //     'orders',
    //     'referrals',
    //     'prefix',
    //     'wallets'
    // ];

    const INACTIVE = '0';
    const ACTIVE = '1';
    const ELIMINATED = '2';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token_jwt',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function coupon()
    {
        return $this->hasMany(Coupon::class, 'user_id');
    }
    public function couponBuy()
    {
        return $this->hasMany(Coupon::class, 'buyer_id');
    }
	public function ticket()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }
    public function ticketSupport()
    {
        return $this->hasMany(MessageTicket::class, 'support_id');
    }
    public function prefix()
    {
        return $this->belongsTo(Prefix::class, 'prefix_id');
    }

    public function sponsor_binary()
    {
        return $this->belongsTo(User::class, 'binary_id');
    }

    public function binaryChildrens()
    {
        return $this->hasMany(User::class, 'binary_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function padre()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'buyer_id');
    }
    public function children()
    {
        return $this->hasMany(User::class, 'buyer_id');
    }

    public function hasActiveSuscription()
    {
        $result = false;
        $order = $this->orders->where('status','1')->last();
        if($order && $order->membership) {
            $result = $order->membership->where('status', '0')->exists();
        }
        return $result;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallets()
    {
        return $this->hasMany(WalletComission::class);
    }

    public function decryptWallet()
    {
        return Crypt::decrypt($this->wallet);
    }

    public function getProgram()
    {
        $order = $this->orders->where('status', '1')->last();

        if($order) {
            return $order->packageMembership;
        }

        return null;
    }

    public function getPackage()
    {
        return $this->hasMany(Package::class, 'user_id');
    }


    public function getStatus()
    {
        $status = 'inactive';

        switch ($this->status) {
            case '0':
                $status = 'Inactive';
                break;
            case '1':
                $status = 'Active';
                break;
            case '2':
                $status = 'Deleted';
                break;
        }

        return $status;
    }

    public function fullName()
    {
        return ucwords($this->name." ".$this->last_name);
    }

    // Relacion uno a muchos con la tabla user_coupons
    public function coupons()
    {
        return $this->belongsToMany('App\Models\Coupon');
    }

    public function inversions()
    {
        return $this->hasMany(Inversion::class);
    }

    public function marketPurchased()
    {
        return $this->hasMany(MarketPurchased::class);
    }
    /**
     * Este metodo retorna la matrix/cyborg a la que pertenece este usuario, donde el dueño es su padre (es decir el buyer_id)
     */
    public function getFatherMarketPurchased()
    {
        return MarketPurchased::where('user_id', $this->buyer_id)->where('id', $this->father_cyborg_purchased_id)->first();
    }
}
