<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'user_id',
        'country',
        'document_id',
        'postal_code',
        'phone_number',
        'status',
        'state',
        'street',
        'department',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->BelongsTo(User::class, 'user_id', 'id');
    }

    public function scopeName($query, $filter)
    {
        if($filter)
            $query->whereHas('user', function($query) use ($filter)
            {
                return $query->where('name', 'LIKE', "%$filter%");
            })
            ->orWhere('user_id', $filter);
    }

}
