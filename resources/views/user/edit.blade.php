@extends('layouts.app')

@section('title', 'Edit User')

@section('main-content')
    <x-form.card title="Edit Data User">
        <x-form.form method="POST" route='user.update' id="{{$user->id}}">
            <x-form.input type="text" name="name" label="Name" placeholder="Name" id="name" value="{{$user->name}}" />
            <x-form.input type="email" name="email" label="Email" placeholder="Email" id="email" value="{{$user->email}}"/>
            <x-form.input type="password" name="password" label="Password" placeholder="Kosongkan Password Untuk Costumer" id="password" />
            <x-form.input type="number" name="telp" label="Nomor HP" placeholder="Nomor HP" id="telp" value="{{$user->telp}}"/>
            <x-form.select name="role" id="role" label="Role" :options="['admin','sales','costumer']" selected="{{$user->role}}" />
            <x-form.button label="Update User" />
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
