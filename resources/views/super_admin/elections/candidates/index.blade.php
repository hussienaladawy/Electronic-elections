@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('Candidates for Election: ') . $election->title }}</h1>
        <a href="{{ route('super_admin.elections.candidates.create', $election->id) }}" class="btn btn-primary">
            {{ __('Add New Candidate') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Candidates List') }}</h5>
        </div>
        <div class="card-body">
            @if($candidates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Party') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidates as $candidate)
                                <tr>
                                    <td>{{ $candidate->id }}</td>
                                    <td>{{ $candidate->name }}</td>
                                    <td>{{ $candidate->party ?? __('Independent') }}</td>
                                    <td>{{ $candidate->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('super_admin.elections.candidates.show', [$election->id, $candidate->id]) }}" 
                                               class="btn btn-sm btn-info" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('super_admin.elections.candidates.edit', [$election->id, $candidate->id]) }}" 
                                               class="btn btn-sm btn-warning" title="{{ __('Edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('super_admin.elections.candidates.destroy', [$election->id, $candidate->id]) }}" 
                                                  style="display: inline-block;" 
                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this candidate?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $candidates->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No candidates found') }}</h5>
                    <p class="text-muted">{{ __('Start by adding the first candidate for this election.') }}</p>
                    <a href="{{ route('super_admin.elections.candidates.create', $election->id) }}" class="btn btn-primary">
                        {{ __('Add First Candidate') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('super_admin.elections.show', $election->id) }}" class="btn btn-secondary">
            {{ __('Back to Election Details') }}
        </a>
    </div>
</div>
@endsection

