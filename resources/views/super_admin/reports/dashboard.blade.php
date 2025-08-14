@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Super Admin Reports Dashboard') }}</div>

                <div class="card-body">
                    <p>Welcome to the Super Admin Reports Dashboard!</p>
                    <p>This section will contain various reports and analytics for the election system.</p>
                    <!-- Add your report widgets and charts here -->
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection




