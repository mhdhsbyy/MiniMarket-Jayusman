<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutgoingGood extends Model
{
    protected $fillable = [
        'branch_id',
        'product_id',
        'user_id',
        'tanggal_keluar',
        'jumlah',
        'keterangan',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
