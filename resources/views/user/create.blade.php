@extends('layouts.app')

@section('title', 'Create User')

@section('main-content')
    <x-form.card title="Create Data User">
        <x-form.form method="POST" route='user.store'>
            <x-form.input type="text" name="name" label="Name" placeholder="Name" id="name" />
            <x-form.input type="email" name="email" label="Email" placeholder="Email" id="email" />
            <x-form.input type="password" name="password" label="Password" placeholder="Kosongkan Password Untuk Costumer" id="password" />
            <x-form.input type="number" name="telp" label="Nomor HP" placeholder="Nomor HP" id="telp" />
            <x-form.select name="role" id="role" label="Role" :options="['admin'=>'admin','sales'=>'sales','costumer'=>'costumer']" selected="{{ old('role') }}" />
            <x-form.button label="Add User" />
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
