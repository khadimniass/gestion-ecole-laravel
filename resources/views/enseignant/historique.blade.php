@extends('layouts.app')

@section('title', 'Historique de mes Encadrements')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-history"></i> Historique de mes Encadrements</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Année</th>
                        <th>Sujet</th>
                        <th>Étudiants</th>
                        <th>Période</th>
                        <th>Note</th>
                        <th>Résultat</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($historique as $item)
                        <tr>
                            <td>{{ $item->anneeUniversitaire->annee }}</td>
                            <td>{{ $item->titre_sujet }}</td>
                            <td>
                                @foreach($item->etudiants as $etudiant)
                                    <span class="badge bg-light text-dark">{{ $etudiant['nom'] }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $item->date_debut->format('d/m/Y') }} -
                                {{ $item->date_fin->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($item->note_finale)
                                    <strong>{{ $item->note_finale }}/20</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->resultat == 'reussi' ? 'success' : 'danger' }}">
                                    {{ ucfirst($item->resultat) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="mb-0">Aucun encadrement dans l'historique</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($historique->hasPages())
                <div class="mt-3">
                    {{ $historique->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
