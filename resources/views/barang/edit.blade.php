@extends('layouts.app')

@section('title', 'Edit Barang')

@section('main-content')
    <x-form.card title="Edit Data Barang">
        <x-form.form method="POST" route='barang.update' id="{{$barang->id}}">
            <x-form.input type="text" name="kode" label="Kode Barang" placeholder="Kode Barang" id="kode" value="{{$barang->kode}}"/>
            <x-form.input type="text" name="nama" label="Nama Barang" placeholder="Nama Barang" id="nama" value="{{$barang->nama}}"/>
            <x-form.input type="number" name="harga" label="Harga" placeholder="Harga" id="harga" value="{{$barang->harga}}"/>
            <x-form.input type="number" name="qty" label="Jumlah Barang" placeholder="Jumlah Barang" id="qty" value="{{$barang->qty}}"/>
            <x-form.input type="number" name="diskon_pct" label="Diskon Barang" placeholder="Diskon Barang" id="diskon_pct" value="{{$barang->diskon_pct}}"/>
            <x-form.button label="Update Barang" />
        </x-form.form>
    </x-form.card>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#role').select2({
                theme: 'bootstrap4',
                placeholder: '--- Pilih ---',
            });

            $('#role').on('change', function() {
                var selectedRole = $(this).val();
                var passwordField = $('#password');

                if (selectedRole === 'costumer') {
                    passwordField.prop('readonly', true);
                } else {
                    passwordField.prop('readonly', false);
                }
            });

            $('#role').trigger('change');
        });
    </script>
@endpush
