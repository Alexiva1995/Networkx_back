<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Formulary extends Model
{
    use HasFactory;

    const PENDING = 0;
    const PASSED = 1;
    const NOT_APPROVED = 2;
    const EXPIRED = 3;

    protected $fillable = [
        'project_id',
        'name',
        'login',
        'password',
        'leverage',
        'balance',
        'server',
        'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getStatusName() {
        $array = ['Pending', 'Passed', 'Not Approved', 'Expired'];
        return $array[$this->status];
    }

    public function getFormularyPassword() {
        $formulary = $this;
        $formulary->password = Crypt::decryptString($this->password);

        return $formulary;
    }
}
