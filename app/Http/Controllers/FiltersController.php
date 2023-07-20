<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class FiltersController extends Controller
{
    public function filtersOrderAdmin(request $request)
    {
        $filter = $request->dataFilter;

        $query = Order::with('user');

        $query->when(isset($filter['user_id']), function ($q) use ($filter) {
            $q->where('user_id', $filter['user_id']);
        });

        $query->when(isset($filter['name']), function ($q) use ($filter) {
            $q->whereHas('user', function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['name'] . '%');
            });
        });

        $data = $query->get();

        return response()->json($data, 200);
    }

    public function filtersProductAdmin(request $request)
    {
        $filter = $request->dataToProduct;

        $query = Product::query();

        foreach ([
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
        ] as $field) {
            $value = $filter[$field] ?? null;

            if ($value !== null) {
                if (in_array($field, ['name', 'street', 'department'])) {
                    $query->where($field, 'like', '%' . $value . '%');
                } elseif (in_array($field, ['created_at', 'updated_at'])) {
                    $query->whereDate($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        $data = $query->get();

        return response()->json($data, 200);
    }
}
