@extends('layouts.app')

@section('title', 'Create Barang')

@section('main-content')
    <x-form.card title="Create Data Barang">
        <x-form.form method="POST" route='barang.store'>
            <x-form.input type="text" name="kode" label="Kode Barang" placeholder="Kode Barang" id="kode" />
            <x-form.input type="text" name="nama" label="Nama Barang" placeholder="Nama Barang" id="nama" />
            <x-form.input type="number" name="harga" label="Harga" placeholder="Harga" id="harga" />
            <x-form.input type="number" name="qty" label="Jumlah Barang" placeholder="Jumlah Barang" id="qty" />
            <x-form.input type="number" name="diskon_pct" label="Diskon Barang" placeholder="Diskon Barang" id="diskon_pct" />
            <x-form.button label="Add Barang" />
        </x-form.form>
    </x-form.card>
@endsection

