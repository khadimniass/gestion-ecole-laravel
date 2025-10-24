<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventDuplicatePfe
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->estEtudiant()) {
            if (Auth::user()->aDejaUnPfeEnCours()) {
                return redirect()->route('etudiant.mon-pfe.index')
                    ->with('info', 'Vous avez déjà un PFE en cours.');
            }
        }

        return $next($request);
    }
}
