@extends('layouts.app')
@section('title', 'Gestion des Soutenances')
@section('content')
    <h1><i class="fas fa-calendar-check"></i> Gestion des Soutenances</h1>
    <div class="card"><div class="card-body">
        <table class="table"><thead><tr><th>PFE</th><th>Date</th><th>Salle</th><th>Actions</th></tr></thead><tbody>
        @forelse($soutenances ?? [] as $soutenance)
            <tr><td>{{ $soutenance->numero_pfe }}</td><td>{{ $soutenance->date_soutenance }}</td><td>{{ $soutenance->salle_soutenance }}</td><td><a href="{{ route('admin.soutenances.jury.edit', $soutenance) }}" class="btn btn-sm btn-primary">Gérer Jury</a></td></tr>
        @empty
            <tr><td colspan="4" class="text-center">Aucune soutenance</td></tr>
        @endforelse
        </tbody></table>
    </div></div>
@endsection
