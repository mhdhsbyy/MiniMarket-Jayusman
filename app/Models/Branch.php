<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'kota',
        'alamat',
        'status'
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'branch_id', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            });
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'branch_id', 'id')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['supervisor', 'cashier', 'warehouse']);
            });
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'branch_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'branch_id', 'id');
    }

}
