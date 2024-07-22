<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = MasterBarang::all();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10',
            'nama' => 'required|string|max:100',
            'harga' => 'required|integer',
            'qty' => 'required|integer',
        ]);

        // $kode = $this->generateCode();

        MasterBarang::create([
            'kode' => $request->input('kode'),
            'nama' => $request->input('nama'),
            'harga' => $request->input('harga'),
            'qty' => $request->input('qty'),
            'diskon_pct' => $request->input('diskon_pct'),
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = MasterBarang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = MasterBarang::findOrFail($id);

        // Lakukan update data pengguna
        $barang->update([
            'nama' => $request->input('nama'),
            'nama' => $request->input('nama'),
            'harga' => $request->input('harga'),
            'qty' => $request->input('qty'),
            'diskon_pct' => $request->input('diskon_pct'),
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MasterBarang::destroy($id);
        return back()->with('success', 'Barang deleted successfully.');
    }
}
