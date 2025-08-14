@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Candidate Details') }}</div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Name:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $candidate->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Election:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $election->title }}
                        </div>
                    </div>

                    @if($candidate->party)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Party:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $candidate->party }}
                        </div>
                    </div>
                    @endif

                    @if($candidate->bio)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Biography:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $candidate->bio }}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Created At:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $candidate->created_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Updated At:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $candidate->updated_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.elections.edit', [$election->id, $candidate->id]) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                        <a href="{{ route('admin.elections.candidates', $election->id) }}" class="btn btn-secondary">{{ __('Back to Candidates') }}</a>
                        
                        <form method="POST" action="{{ route('admin.elections.destroy', [$election->id, $candidate->id]) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this candidate?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

