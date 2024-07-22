@extends('layouts.app')

@section('title', 'User')

@section('main-content')
    <x-page-header title="Data User" />

    <x-form.card title="Data User" :addRoute="'user.create'">
        <x-form.table>
            @slot('header')
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>HP</th>
                <th>Role</th>
                <th>Action</th>
            @endslot

            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telp }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <x-form.button-link :route="'user.edit'" :id="$user->id" type="edit" label="Edit" />
                        <x-form.button-link :route="'user.destroy'" :id="$user->id" type="delete" label="Delete" />
                    </td>
                </tr>
            @endforeach
        </x-form.table>
    </x-form.card>
@endsection
