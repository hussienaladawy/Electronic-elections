@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Permissions Dashboard</h1>
    <p>Manage user role permissions across the application.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('super_admin.permissions.update') }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 20%;">Permission</th>
                        @foreach ($roles as $role)
                            <th class="text-center">{{ ucwords(str_replace('_', ' ', $role->name)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $permission)) }}</td>
                            @foreach ($roles as $role)
                                <td class="text-center">
                                    <input type="checkbox"
                                           name="permissions[{{ $role->name }}][]"
                                           value="{{ $permission }}"
                                           @if(in_array($permission, $rolePermissions[$role->name] ?? [])) checked @endif>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
    </form>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection
