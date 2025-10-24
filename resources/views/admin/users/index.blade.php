@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users"></i> Gestion des Utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouvel Utilisateur
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                            <td><span class="badge bg-{{ $user->active ? 'success' : 'danger' }}">{{ $user->active ? 'Actif' : 'Inactif' }}</span></td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">Aucun utilisateur</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($users) && method_exists($users, 'links'))
                {{ $users->links() }}
            @endif
        </div>
    </div>
@endsection
