@extends('layouts.app')

@section('title', 'Barang')

@section('main-content')
    <x-page-header title="Data Barang" />

    <x-form.card title="Data Barang" :addRoute="'barang.create'">
        <x-form.table>
            @slot('header')
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>QTY</th>
                <th>Diskon</th>
                <th>Action</th>
            @endslot

            @foreach ($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang->kode }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ $barang->harga }}</td>
                    <td>{{ $barang->qty }}</td>
                    <td>{{ $barang->diskon_pct }}%</td>
                    <td>
                        <x-form.button-link :route="'barang.edit'" :id="$barang->id" type="edit" label="Edit" />
                        <x-form.button-link :route="'barang.destroy'" :id="$barang->id" type="delete" label="Delete" />
                    </td>
                </tr>
            @endforeach
        </x-form.table>
    </x-form.card>
@endsection
