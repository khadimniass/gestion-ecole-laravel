<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use App\Models\Filiere;
use App\Models\HistoriqueEncadrement;
use App\Models\Notification;
use App\Models\Pfe;
use App\Models\SujetPfe;
use App\Models\User;
use App\Policies\DemandeEncadrementPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PfePolicy;
use App\Policies\SujetPfePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        SujetPfe::class => SujetPfePolicy::class,
        Pfe::class => PfePolicy::class,
        DemandeEncadrement::class => DemandeEncadrementPolicy::class,
        User::class => UserPolicy::class,
        Notification::class => NotificationPolicy::class,
        Filiere::class => \App\Policies\FilierePolicy::class,
        AnneeUniversitaire::class => \App\Policies\AnneeUniversitairePolicy::class,
        HistoriqueEncadrement::class => \App\Policies\HistoriqueEncadrementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
