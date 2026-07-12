<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingGood extends Model
{
    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'harga_beli' => 'decimal:2',
    ];

    protected $fillable = [
        'branch_id',
        'user_id',
        'product_id',
        'jumlah',
        'harga_beli',
        'tanggal_masuk',
        'keterangan',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
