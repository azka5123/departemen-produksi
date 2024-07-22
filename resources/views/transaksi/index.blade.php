@extends('layouts.app')

@section('title', 'Transaksi')

@section('main-content')
    <x-page-header title="Data Transaksi" />
    <x-form.card  :addRoute="'transaksi.create'" :title="'Grand Total :' . $formattedTotal" textColor="secondary">
        <x-form.table>
            @slot('header')
                <th>No</th>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Nama Costumer</th>
                <th>Jumlah Barang</th>
                <th>Sub Total</th>
                <th>Diskon</th>
                <th>Ongkir</th>
                <th>Total</th>
                <th>Action</th>
            @endslot

            @foreach ($transactions as $transaksi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->kode }}</td>
                    <td>{{ $transaksi->tgl }}</td>
                    <td>{{ $transaksi->rCostumer->name }}</td>
                    <td> {{ $transaksi->rDetails->count() }}</td>
                    <td>@rupiah($transaksi->subtotal)</td>
                    <td>@rupiah($transaksi->diskon)</td>
                    <td>@rupiah($transaksi->ongkir)</td>
                    <td>@rupiah($transaksi->total_bayar)</td>
                    <td>
                        <x-form.button-link :route="'transaksi.edit'" :id="$transaksi->id" type="edit" label="Edit" />
                        <x-form.button-link :route="'transaksi.destroy'" :id="$transaksi->id" type="delete" label="Delete" />
                    </td>
                </tr>
            @endforeach
            {{-- <tr>
                <td colspan="6"></td>
                <td colspan="2" class="border font-weight-bold">Grand Total :</td>
                <td colspan="2" class="border font-weight-bold">@rupiah($transactions->sum('total_bayar'))</td>
            </tr> --}}
        </x-form.table>
    </x-form.card>
@endsection
