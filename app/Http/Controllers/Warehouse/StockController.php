<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $stocks = Stock::with(['product.category', 'branch'])
            ->where('branch_id', $user->cabang_id)
            ->orderBy('id', 'asc')
            ->get();

        return view('warehouse.stocks', compact('stocks'));
    }
}
