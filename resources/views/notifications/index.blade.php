@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-bell"></i> Notifications</h1>
        @if($notifications->where('lu', false)->count() > 0)
            <form action="{{ route('notifications.marquer-toutes-lues') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($notifications as $notification)
                <div class="card mb-3 {{ $notification->lu ? 'bg-light' : 'border-primary' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2">
                                    @if(!$notification->lu)
                                        <span class="badge bg-primary me-2">Nouveau</span>
                                    @endif
                                    {{ $notification->titre }}
                                </h5>
                                <p class="card-text">{{ $notification->message }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div>
                                @if(!$notification->lu)
                                    <form action="{{ route('notifications.marquer-lu', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune notification</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
