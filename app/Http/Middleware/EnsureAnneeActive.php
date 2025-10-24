<?php

namespace App\Http\Middleware;

use App\Models\AnneeUniversitaire;
use Closure;
use Illuminate\Http\Request;

class EnsureAnneeActive
{
    public function handle(Request $request, Closure $next)
    {
        $anneeActive = AnneeUniversitaire::where('active', true)->first();

        if (!$anneeActive && !in_array($request->route()->getName(), ['admin.annees-universitaires.index', 'admin.annees-universitaires.create'])) {
            return redirect()->route('admin.annees-universitaires.index')
                ->with('warning', 'Veuillez activer une année universitaire avant de continuer.');
        }

        // Partager l'année active avec toutes les vues
        if ($anneeActive) {
            view()->share('anneeActive', $anneeActive);
        }

        return $next($request);
    }
}
