<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\TransactionSales;
use App\Models\TransactionSalesDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // private function generateCode()
    // {
    //     $latestBarang = MasterBarang::orderBy('kode', 'desc')->first();
    //     if ($latestBarang) {
    //         $lastNumber = (int) substr($latestBarang->code, 1);
    //         $number = $lastNumber + 1;
    //     } else {
    //         $number = 1;
    //     }

    //     $code = chr(rand(65, 90)) . str_pad($number, 3, '0', STR_PAD_LEFT);

    //     $existingCode = MasterBarang::where('kode', $code)->exists();
    //     if ($existingCode) {
    //         return $this->generateCode();
    //     }

    //     return $code;
    // }

    private function removeCurrencyFormat($value): float
    {
        // Remove any non-numeric characters except the decimal separator
        $cleanedValue = preg_replace('/[^\d,]/', '', $value);

        // Replace comma with dot for decimal separator if needed
        $numericValue = str_replace(',', '.', $cleanedValue);

        // Return as float
        return (float) $numericValue;
    }

    private function generateTransactionCode()
    {
        $date = new \DateTime();
        $yearMonth = $date->format('Ym');

        $latestTransaction = TransactionSales::where('kode', 'like', "$yearMonth%")
            ->orderBy('kode', 'desc')
            ->first();

        if ($latestTransaction) {
            $lastNumber = (int) substr($latestTransaction->kode, -5);
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        $transactionCode = "$yearMonth-$nextNumber";

        return $transactionCode;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = TransactionSales::all();
        $grandTotal = $transactions->sum('total_bayar');
        $formattedTotal = 'Rp ' . number_format($grandTotal, 2, ',', '.');

        return view('transaksi.index', compact('transactions', 'formattedTotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transactionCode = $this->generateTransactionCode();

        $customers = User::where('role', 'costumer')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => [
                        'display' => "{$item->kode} - {$item->name}",
                        'telp' => $item->telp,
                    ],
                ];
            });
        $barangs = MasterBarang::all()->mapWithKeys(function ($item) {
            return [
                $item->id => [
                    'display' => "{$item->kode} - {$item->nama}",
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'harga' => $item->harga,
                    'qty' => $item->qty,
                    'diskon_pct' => $item->diskon_pct,
                ],
            ];
        });

        return view('transaksi.create', compact('customers', 'transactionCode', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        // die();

        $request->validate([
            'kode' => 'required',
            'customer' => 'required',
            'tgl' => 'required|date',
            'barang' => 'required|array',
        ]);

        $transaction = TransactionSales::create([
            'kode' => $request->input('kode'),
            'tgl' => $request->input('tgl'),
            'id_sales' => Auth::user()->id,
            'id_costumer' => $request->input('customer'),
            'subtotal' => $this->removeCurrencyFormat($request->input('subtotal')),
            'diskon' => $this->removeCurrencyFormat($request->input('diskon')),
            'ongkir' => $this->removeCurrencyFormat($request->input('ongkir')),
            'total_bayar' => $this->removeCurrencyFormat($request->input('total_bayar')),
        ]);

        foreach ($request->input('barang') as $id_barang) {
            // (int) $qty = '';
            $diskonRp = $this->removeCurrencyFormat($request->input('diskonRp')[$id_barang]);
            $qty = $request->input('jumlah')[$id_barang];
            $hargaDiskon = $this->removeCurrencyFormat($request->input('hargaDiskon')[$id_barang]);
            $total = $this->removeCurrencyFormat($request->input('total')[$id_barang]);

            TransactionSalesDetail::create([
                'id_transaction_sales' => $transaction->id,
                'id_barang' => $id_barang,
                'qty' => $qty,
                'diskon_nilai' => $diskonRp,
                'harga_diskon' => $hargaDiskon,
                'total_harga' => $total,
            ]);

            MasterBarang::where('id', $id_barang)->decrement('qty', $qty);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = TransactionSales::with('rDetails')->findOrFail($id);
        $customers = User::where('role', 'costumer')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => [
                        'display' => "{$item->kode} - {$item->name}",
                        'telp' => $item->telp,
                    ],
                ];
            });
        $barangs = MasterBarang::all()->mapWithKeys(function ($item) {
            return [
                $item->id => [
                    'display' => "{$item->kode} - {$item->nama}",
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'harga' => $item->harga,
                    'qty' => $item->qty,
                    'diskon_pct' => $item->diskon_pct,
                ],
            ];
        });
        $transactionDetails = $transaction->rDetails->mapWithKeys(function ($detail) {
            return [
                $detail->id_barang => [
                    'qty' => $detail->qty,
                    'diskon_nilai' => $detail->diskon_nilai,
                    'harga_diskon' => $detail->harga_diskon,
                    'total_harga' => $detail->total_harga,
                ],
            ];
        });
        return view('transaksi.edit', compact('transaction', 'customers', 'barangs', 'transactionDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'customer' => 'required',
            'tgl' => 'required|date',
            'barang' => 'required|array',
        ]);

        $transaction = TransactionSales::findOrFail($id);
        $transaction->update([
            'kode' => $request->input('kode'),
            'tgl' => $request->input('tgl'),
            'id_sales' => Auth::user()->id,
            'id_costumer' => $request->input('customer'),
            'subtotal' => $this->removeCurrencyFormat($request->input('subtotal')),
            'diskon' => $this->removeCurrencyFormat($request->input('diskon')),
            'ongkir' => $this->removeCurrencyFormat($request->input('ongkir')),
            'total_bayar' => $this->removeCurrencyFormat($request->input('total_bayar')),
        ]);

        $existingDetails = $transaction->rDetails;
        // $existingDetailIds = $existingDetails->pluck('id_barang')->all();
        $newDetailIds = $request->input('barang');

        // Delete details that are no longer in the updated transaction
        foreach ($existingDetails as $detail) {
            if (!in_array($detail->id_barang, $newDetailIds)) {
                MasterBarang::where('id', $detail->id_barang)->increment('qty', $detail->qty); // Restore stock
                $detail->delete();
            }
        }

        // Update existing details and create new details
        foreach ($newDetailIds as $id_barang) {
            $diskonRp = $this->removeCurrencyFormat($request->input('diskonRp')[$id_barang]);
            $qty = $request->input('jumlah')[$id_barang];
            $hargaDiskon = $this->removeCurrencyFormat($request->input('hargaDiskon')[$id_barang]);
            $total = $this->removeCurrencyFormat($request->input('total')[$id_barang]);

            $detail = $transaction->rDetails()->where('id_barang', $id_barang)->first();
            if ($detail) {
                // Update existing detail
                MasterBarang::where('id', $id_barang)->increment('qty', $detail->qty); // Restore old stock
                $detail->update([
                    'qty' => $qty,
                    'diskon_nilai' => $diskonRp,
                    'harga_diskon' => $hargaDiskon,
                    'total_harga' => $total,
                ]);
                MasterBarang::where('id', $id_barang)->decrement('qty', $qty); // Deduct new stock
            } else {
                // Create new detail
                TransactionSalesDetail::create([
                    'id_transaction_sales' => $transaction->id,
                    'id_barang' => $id_barang,
                    'qty' => $qty,
                    'diskon_nilai' => $diskonRp,
                    'harga_diskon' => $hargaDiskon,
                    'total_harga' => $total,
                ]);
                MasterBarang::where('id', $id_barang)->decrement('qty', $qty); // Deduct new stock
            }
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TransactionSalesDetail::where('id_transaction_sales', $id)->delete();
        TransactionSales::destroy($id);
        return back()->with('success', 'Transaksi deleted successfully.');
    }
}
