@extends('layouts.app')
@section('title', 'Années Universitaires')
@section('content')
    <h1><i class="fas fa-calendar"></i> Années Universitaires</h1>
    <div class="card"><div class="card-body">
        <a href="{{ route('admin.annees-universitaires.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Nouvelle Année</a>
        <table class="table"><thead><tr><th>Année</th><th>Active</th><th>Actions</th></tr></thead><tbody>
        @forelse($annees ?? [] as $annee)
            <tr><td>{{ $annee->annee }}</td><td><span class="badge bg-{{ $annee->active ? 'success' : 'secondary' }}">{{ $annee->active ? 'Active' : 'Inactive' }}</span></td><td><a href="{{ route('admin.annees-universitaires.edit', $annee) }}" class="btn btn-sm btn-warning">Modifier</a></td></tr>
        @empty
            <tr><td colspan="3" class="text-center">Aucune année</td></tr>
        @endforelse
        </tbody></table>
    </div></div>
@endsection
