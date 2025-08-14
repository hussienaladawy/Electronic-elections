@extends('layouts.app')

@section('title', 'التصويت - ' . $election->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-2 text-primary">{{ $election->name }}</h1>
                            <p class="text-muted mb-0">{{ $election->description }}</p>
                        </div>
                        <div class="text-right">
                            <div class="badge bg-success mb-2">نشطة</div>
                            <div class="small text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                تنتهي: {{ $election->end_date->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Voting Instructions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <h5 class="alert-heading">
                    <i class="fas fa-info-circle mr-2"></i>
                    تعليمات التصويت
                </h5>
                <hr>
                <ul class="mb-0">
                    <li>اقرأ برامج المرشحين بعناية قبل اتخاذ قرارك</li>
                    <li>يمكنك اختيار مرشح واحد فقط في هذه الانتخابات</li>
                    <li>تأكد من اختيارك قبل الضغط على "تأكيد التصويت"</li>
                    <li>بعد التصويت، ستحصل على رمز تحقق للتأكد من صحة صوتك</li>
                    <li>لا يمكن تغيير صوتك بعد التأكيد</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Voting Form -->
    <form id="votingForm" method="POST" action="{{ route('voting.submit', $election->id) }}">
        @csrf

  

        <!-- Candidates List -->
        <div class="row">
            <div class="col-12">
                <div class="form-group mt-4">
    <label for="voter_password">كلمة مرور الناخب:</label>
    
    <input type="password" 
           name="voter_password" 
           id="voter_password" 
           class="form-control @error('voter_password') is-invalid @enderror" 
           placeholder="أدخل كلمة المرور لتأكيد التصويت" 
           required>

    @error('voter_password')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>
<br>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            اختر مرشحك ({{ count($candidates) }} مرشح)
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(count($candidates) > 0)
                            <div class="row">
                                @foreach($candidates as $candidate)
                                <div class="col-lg-6 mb-4">
                                    <div class="card candidate-card h-100" data-candidate-id="{{ $candidate->id }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="mr-3">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" 
                                                               class="custom-control-input" 
                                                               id="candidate_{{ $candidate->id }}" 
                                                               name="candidate_id" 
                                                               value="{{ $candidate->id }}" 
                                                               required>
                                                        <label class="custom-control-label" 
                                                               for="candidate_{{ $candidate->id }}"></label>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="candidate-photo mr-3">
                                                            @if($candidate->photo)
                                                                <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                                     alt="{{ $candidate->name }}" 
                                                                     class="rounded-circle" 
                                                                     width="60" height="60">
                                                            @else
                                                                <div class="icon-circle bg-primary">
                                                                    <i class="fas fa-user text-white"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h5 class="card-title mb-1">{{ $candidate->name }}</h5>
                                                            <p class="text-muted small mb-0">{{ $candidate->position ?? 'مرشح' }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($candidate->biography)
                                                    <div class="mb-3">
                                                        <h6 class="text-primary">نبذة شخصية:</h6>
                                                        <p class="small text-muted">{{ Str::limit($candidate->biography, 150) }}</p>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($candidate->program)
                                                    <div class="mb-3">
                                                        <h6 class="text-primary">البرنامج الانتخابي:</h6>
                                                        <p class="small text-muted">{{ Str::limit($candidate->program, 200) }}</p>
                                                    </div>
                                                    @endif
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            @if($candidate->party)
                                                            <span class="badge bg-info">{{ $candidate->party }}</span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <button type="button" 
                                                                    class="btn btn-outline-primary btn-sm" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#candidateModal{{ $candidate->id }}">
                                                                عرض التفاصيل
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                                <h5 class="text-muted">لا يوجد مرشحين</h5>
                                <p class="text-muted">لم يتم تسجيل أي مرشحين لهذه الانتخابات بعد</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Voting Actions -->
        @if(count($candidates) > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow border-left-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-warning mb-1">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    تأكيد التصويت
                                </h6>
                                <p class="text-muted mb-0 small">
                                    تأكد من اختيارك قبل الضغط على "تأكيد التصويت". لا يمكن تغيير صوتك بعد التأكيد.
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('vote', $election->id) }}" 
                                   class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    العودة
                                </a>
                                <button type="button" 
                                        class="btn btn-success" 
                                        id="confirmVoteBtn" 
                                        disabled 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#confirmModal">
                                    <i class="fas fa-vote-yea mr-1"></i>
                                    تأكيد التصويت
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>

<!-- Candidate Detail Modals -->
@foreach($candidates as $candidate)
<div class="modal fade" id="candidateModal{{ $candidate->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $candidate->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($candidate->photo)
                            <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                 alt="{{ $candidate->name }}" 
                                 class="img-fluid rounded-circle mb-3" 
                                 style="max-width: 150px;">
                        @else
                            <div class="icon-circle bg-primary mx-auto mb-3" style="width: 150px; height: 150px;">
                                <i class="fas fa-user text-white fa-4x"></i>
                            </div>
                        @endif
                        <h5>{{ $candidate->name }}</h5>
                        @if($candidate->position)
                            <p class="text-muted">{{ $candidate->position }}</p>
                        @endif
                        @if($candidate->party)
                            <span class="badge bg-info">{{ $candidate->party }}</span>
                        @endif
                    </div>
                    <div class="col-md-8">
                        @if($candidate->biography)
                        <div class="mb-4">
                            <h6 class="text-primary">نبذة شخصية:</h6>
                            <p>{{ $candidate->biography }}</p>
                        </div>
                        @endif
                        
                        @if($candidate->program)
                        <div class="mb-4">
                            <h6 class="text-primary">البرنامج الانتخابي:</h6>
                            <p>{{ $candidate->program }}</p>
                        </div>
                        @endif
                        
                        @if($candidate->achievements)
                        <div class="mb-4">
                            <h6 class="text-primary">الإنجازات:</h6>
                            <p>{{ $candidate->achievements }}</p>
                        </div>
                        @endif
                        
                        @if($candidate->contact_info)
                        <div class="mb-4">
                            <h6 class="text-primary">معلومات الاتصال:</h6>
                            <p>{{ $candidate->contact_info }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="selectCandidate({{ $candidate->id }})">
                    اختيار هذا المرشح
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    تأكيد التصويت
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-vote-yea fa-4x text-warning mb-3"></i>
                    <h5>هل أنت متأكد من اختيارك؟</h5>
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <strong>تنبيه مهم:</strong> بعد تأكيد التصويت، لن تتمكن من تغيير اختيارك. تأكد من أن هذا هو المرشح الذي تريد التصويت له.
                </div>
                
                <div id="selectedCandidateInfo" class="card">
                    <div class="card-body">
                        <h6 class="text-primary">المرشح المختار:</h6>
                        <div id="candidateDetails"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    إلغاء
                </button>
                <button type="button" class="btn btn-success" id="finalConfirmBtn">
                    <i class="fas fa-check mr-1"></i>
                    نعم، أؤكد التصويت
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.candidate-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.candidate-card:hover {
    border-color: #4e73df;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.candidate-card.selected {
    border-color: #1cc88a;
    background-color: #f8fff9;
}

.icon-circle {
    height: 60px;
    width: 60px;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

@media (max-width: 768px) {
    .candidate-card {
        margin-bottom: 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: stretch;
    }
    
    .d-flex.justify-content-between > div {
        margin-bottom: 1rem;
    }
    
    .d-flex.justify-content-between > div:last-child {
        margin-bottom: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const candidateCards = document.querySelectorAll('.candidate-card');
    const confirmVoteBtn = document.getElementById('confirmVoteBtn');
    const finalConfirmBtn = document.getElementById('finalConfirmBtn');
    const votingForm = document.getElementById('votingForm');
    
    // Handle candidate selection
    candidateCards.forEach(card => {
        card.addEventListener('click', function() {
            const candidateId = this.dataset.candidateId;
            const radioButton = document.getElementById(`candidate_${candidateId}`);
            
            // Clear all selections
            candidateCards.forEach(c => c.classList.remove('selected'));
            
            // Select this candidate
            radioButton.checked = true;
            this.classList.add('selected');
            
            // Enable confirm button
            confirmVoteBtn.disabled = false;
            
            // Update confirmation modal
            updateConfirmationModal(candidateId);
        });
    });
    
    // Handle radio button changes
    document.querySelectorAll('input[name="candidate_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const candidateId = this.value;
                
                // Clear all selections
                candidateCards.forEach(c => c.classList.remove('selected'));
                
                // Select this candidate's card
                const card = document.querySelector(`[data-candidate-id="${candidateId}"]`);
                if (card) {
                    card.classList.add('selected');
                }
                
                // Enable confirm button
                confirmVoteBtn.disabled = false;
                
                // Update confirmation modal
                updateConfirmationModal(candidateId);
            }
        });
    });
    
    // Handle final confirmation
    finalConfirmBtn.addEventListener('click', function() {
        // Show loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> جاري التصويت...';
        this.disabled = true;
        
        // Submit the form
        votingForm.submit();
    });
});

function selectCandidate(candidateId) {
    const radioButton = document.getElementById(`candidate_${candidateId}`);
    const card = document.querySelector(`[data-candidate-id="${candidateId}"]`);
    
    // Clear all selections
    document.querySelectorAll('.candidate-card').forEach(c => c.classList.remove('selected'));
    
    // Select this candidate
    radioButton.checked = true;
    card.classList.add('selected');
    
    // Enable confirm button
    document.getElementById('confirmVoteBtn').disabled = false;
    
    // Update confirmation modal
    updateConfirmationModal(candidateId);
    
    // Close the candidate detail modal
    const modal = bootstrap.Modal.getInstance(document.getElementById(`candidateModal${candidateId}`));
    if (modal) {
        modal.hide();
    }
}

function updateConfirmationModal(candidateId) {
    const card = document.querySelector(`[data-candidate-id="${candidateId}"]`);
    const candidateName = card.querySelector('.card-title').textContent;
    const candidatePosition = card.querySelector('.text-muted.small')?.textContent || '';
    
    const candidateDetails = document.getElementById('candidateDetails');
    candidateDetails.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="icon-circle bg-primary mr-3">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <h6 class="mb-0">${candidateName}</h6>
                <small class="text-muted">${candidatePosition}</small>
            </div>
        </div>
    `;
}

// Prevent accidental page refresh during voting
window.addEventListener('beforeunload', function(e) {
    const selectedCandidate = document.querySelector('input[name="candidate_id"]:checked');
    if (selectedCandidate) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

