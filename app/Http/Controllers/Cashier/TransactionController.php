<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $cashier = Auth::user();
        $branchId = $cashier->branch_id;

        $query = Transaction::with(['details.product'])
            ->where('cashier_id', $cashier->id)
            ->where('branch_id', $branchId);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest('tanggal_transaksi')
            ->paginate(10, ['*'], 'transactions_page')
            ->withQueryString();

        $categories = Category::orderBy('nama')->get();

        $products = Product::with(['category', 'supplier', 'stocks'])
            ->where('status', 'active')
            ->whereHas('stocks', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('nama')
            ->paginate(5, ['*'], 'products_page')
            ->withQueryString();

        return view('cashier.transactions.index', compact(
            'transactions',
            'products',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.jumlah' => ['required', 'integer', 'min:1'],
            'uang_dibayar' => ['required', 'numeric', 'min:0'],
            'print_receipt' => ['nullable', 'in:0,1'],
        ]);

        $cashier = Auth::user();
        $branchId = $cashier->branch_id;

        try {
            $transaction = DB::transaction(function () use ($request, $cashier, $branchId) {
                $totalBayar = 0;
                $items = [];

                foreach ($request->products as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    $stock = Stock::where('branch_id', $branchId)
                        ->where('product_id', $product->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$stock || $stock->jumlah_stok < $item['jumlah']) {
                        throw new \Exception('Stok produk ' . $product->nama . ' tidak mencukupi.');
                    }

                    $subtotal = $product->harga_jual * $item['jumlah'];
                    $totalBayar += $subtotal;

                    $items[] = [
                        'product' => $product,
                        'stock' => $stock,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $product->harga_jual,
                        'subtotal' => $subtotal,
                    ];
                }

                if ($request->uang_dibayar < $totalBayar) {
                    throw new \Exception('Uang dibayar kurang dari total transaksi.');
                }

                $transaction = Transaction::create([
                    'branch_id' => $branchId,
                    'cashier_id' => $cashier->id,
                    'tanggal_transaksi' => now(),
                    'total_bayar' => $totalBayar,
                    'uang_dibayar' => $request->uang_dibayar,
                    'kembalian' => $request->uang_dibayar - $totalBayar,
                    'status' => 'success',
                ]);

                foreach ($items as $item) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product']->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['stock']->decrement('jumlah_stok', $item['jumlah']);
                }

                return $transaction;
            });

            if ($request->print_receipt == 1) {
                return redirect()
                    ->route('cashier.transactions.receipt', $transaction->id);
            }

            return redirect()
                ->route('cashier.transactions.index')
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $cashier = Auth::user();

        abort_if(
            $transaction->cashier_id !== $cashier->id ||
                $transaction->branch_id !== $cashier->branch_id,
            403
        );

        $transaction->load([
            'branch',
            'cashier',
            'details.product.category',
        ]);

        return view('cashier.transactions.show', compact('transaction'));
    }

    public function history(Request $request)
    {
        $cashier = Auth::user();
        $branchId = $cashier->branch_id;

        $query = Transaction::with(['details.product'])
            ->where('cashier_id', $cashier->id)
            ->where('branch_id', $branchId);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        return view('cashier.transactions.history', compact('transactions'));
    }

    public function receipt(Transaction $transaction)
    {
        $cashier = Auth::user();

        abort_if(
            $transaction->cashier_id !== $cashier->id ||
                $transaction->branch_id !== $cashier->branch_id,
            403
        );

        $transaction->load([
            'branch',
            'cashier',
            'details.product',
        ]);

        $pdf = Pdf::loadView('cashier.transactions.receipt', compact('transaction'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('struk-transaksi-' . $transaction->id . '.pdf');
    }
}
