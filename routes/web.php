<?php

use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\AnneeUniversitaireController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeEncadrementController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PfeController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\SoutenanceController;
use App\Http\Controllers\SujetPfeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


// Authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Routes protégées (authentification requise)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Redirection vers le dashboard approprié
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->estAdmin() || $user->estCoordinateur()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->estEnseignant()) {
            return redirect()->route('enseignant.dashboard');
        } else {
            return redirect()->route('etudiant.dashboard');
        }
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Routes communes à tous les utilisateurs authentifiés
    |--------------------------------------------------------------------------
    */

    // Recherche et statistiques
    Route::prefix('recherche')->name('recherche.')->group(function () {
        Route::get('/', [RechercheController::class, 'index'])->name('index');
        Route::get('/historique', [RechercheController::class, 'historique'])->name('historique');
        Route::get('/statistiques', [RechercheController::class, 'statistiques'])->name('statistiques');
    });

    // Sujets PFE (consultation)
    Route::resource('sujets', SujetPfeController::class)->only(['index', 'show']);

    // PFE (consultation)
    Route::resource('pfes', PfeController::class)->only(['index', 'show']);

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/marquer-lu', [NotificationController::class, 'marquerLu'])->name('marquer-lu');
        Route::post('/marquer-toutes-lues', [NotificationController::class, 'marquerToutesLues'])->name('marquer-toutes-lues');
    });

    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('index');
        Route::get('/edit', [UserController::class, 'editProfile'])->name('edit');
        Route::put('/update', [UserController::class, 'updateProfile'])->name('update');
        Route::put('/password', [UserController::class, 'updatePassword'])->name('password');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Admin/Coordinateur
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin,coordinateur'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Gestion des utilisateurs
        Route::resource('users', UserController::class);

        // Import des données
        Route::prefix('import')->name('import.')->group(function () {
            Route::get('/', [ImportController::class, 'index'])->name('index');
            Route::get('/form', [ImportController::class, 'showImportForm'])->name('form');
            Route::post('/etudiants', [ImportController::class, 'importEtudiants'])->name('etudiants');
            Route::post('/enseignants', [ImportController::class, 'importEnseignants'])->name('enseignants');
            Route::get('/template/{type}', [ImportController::class, 'downloadTemplate'])->name('template');
        });

        // Validation des sujets
        Route::prefix('sujets')->name('sujets.')->group(function () {
            Route::get('/validation', [SujetPfeController::class, 'indexValidation'])->name('validation');
            Route::patch('/{sujet}/valider', [SujetPfeController::class, 'valider'])->name('valider');
            Route::patch('/{sujet}/rejeter', [SujetPfeController::class, 'rejeter'])->name('rejeter');
        });

        // Gestion des années universitaires
        Route::resource('annees-universitaires', AnneeUniversitaireController::class);

        // Gestion des départements
        Route::resource('departements', DepartementController::class);

        // Gestion des filières
        Route::resource('filieres', FiliereController::class);

        // Affectation des jurys
        Route::prefix('soutenances')->name('soutenances.')->group(function () {
            Route::get('/', [SoutenanceController::class, 'index'])->name('index');
            Route::get('/{pfe}/jury', [SoutenanceController::class, 'editJury'])->name('jury.edit');
            Route::post('/{pfe}/jury', [SoutenanceController::class, 'updateJury'])->name('jury.update');
        });

        // Export des données
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/pfes', [ExportController::class, 'exportPfes'])->name('pfes');
            Route::get('/etudiants', [ExportController::class, 'exportEtudiants'])->name('etudiants');
            Route::get('/encadrements', [ExportController::class, 'exportEncadrements'])->name('encadrements');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Enseignant/Coordinateur
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:enseignant,coordinateur'])->prefix('enseignant')->name('enseignant.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'enseignant'])->name('dashboard');

        // Gestion des sujets (création, modification)
        Route::resource('sujets', SujetPfeController::class)->except(['index', 'show']);

        // Gestion des demandes d'encadrement
        Route::prefix('demandes')->name('demandes.')->group(function () {
            Route::get('/', [DemandeEncadrementController::class, 'index'])->name('index');
            Route::get('/{demande}', [DemandeEncadrementController::class, 'show'])->name('show');
            Route::post('/{demande}/accepter', [DemandeEncadrementController::class, 'accepter'])->name('accepter');
            Route::post('/{demande}/refuser', [DemandeEncadrementController::class, 'refuser'])->name('refuser');
        });

        // Gestion des PFE encadrés
        Route::prefix('pfes')->name('pfes.')->group(function () {
            Route::get('/mes-pfes', [PfeController::class, 'mesPfes'])->name('mes-pfes');
            Route::get('/{pfe}/edit', [PfeController::class, 'edit'])->name('edit');
            Route::put('/{pfe}', [PfeController::class, 'update'])->name('update');
            Route::post('/{pfe}/terminer', [PfeController::class, 'terminer'])->name('terminer');
            Route::post('/{pfe}/noter', [PfeController::class, 'noter'])->name('noter');
        });

        // Historique des encadrements
        Route::get('/historique', [HistoriqueController::class, 'mesEncadrements'])->name('historique');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Étudiant
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'etudiant'])->name('dashboard');

        // Demandes d'encadrement
        Route::prefix('demandes')->name('demandes.')->group(function () {
            Route::get('/', [DemandeEncadrementController::class, 'index'])->name('index');
            Route::get('/create', [DemandeEncadrementController::class, 'create'])->name('create');
            Route::post('/', [DemandeEncadrementController::class, 'store'])->name('store');
            Route::get('/{demande}', [DemandeEncadrementController::class, 'show'])->name('show');
            Route::delete('/{demande}', [DemandeEncadrementController::class, 'destroy'])->name('destroy');
        });

        // Gestion du groupe
        Route::prefix('groupe')->name('groupe.')->group(function () {
            Route::get('/', [GroupeController::class, 'index'])->name('index');
            Route::get('/create', [GroupeController::class, 'create'])->name('create');
            Route::post('/', [GroupeController::class, 'store'])->name('store');
            Route::post('/inviter', [GroupeController::class, 'inviter'])->name('inviter');
            Route::post('/invitation/{invitation}/accepter', [GroupeController::class, 'accepterInvitation'])->name('invitation.accepter');
            Route::post('/invitation/{invitation}/refuser', [GroupeController::class, 'refuserInvitation'])->name('invitation.refuser');
            Route::delete('/membre/{membre}', [GroupeController::class, 'retirerMembre'])->name('membre.retirer');
        });

        // Mon PFE
        Route::prefix('mon-pfe')->name('mon-pfe.')->group(function () {
            Route::get('/', [PfeController::class, 'monPfe'])->name('index');
            Route::post('/upload-rapport', [PfeController::class, 'uploadRapport'])->name('upload-rapport');
            Route::post('/upload-presentation', [PfeController::class, 'uploadPresentation'])->name('upload-presentation');
        });

        // Sujets disponibles
        Route::get('/sujets-disponibles', [SujetPfeController::class, 'sujetsDisponibles'])->name('sujets.disponibles');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes pour le téléchargement de documents
    |--------------------------------------------------------------------------
    */

    Route::prefix('download')->name('download.')->group(function () {
        Route::get('/pfe/{pfe}/rapport', [PfeController::class, 'downloadRapport'])->name('rapport');
        Route::get('/pfe/{pfe}/presentation', [PfeController::class, 'downloadPresentation'])->name('presentation');
    });
});

/*
|--------------------------------------------------------------------------
| Routes API (optionnel - pour des fonctionnalités AJAX)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'api'])->prefix('api')->name('api.')->group(function () {

    // Recherche d'étudiants (pour l'invitation au groupe)
    Route::get('/etudiants/search', [UserController::class, 'searchEtudiants'])->name('etudiants.search');

    // Recherche de sujets
    Route::get('/sujets/search', [SujetPfeController::class, 'search'])->name('sujets.search');

    // Statistiques dashboard (graphiques)
    Route::get('/stats/dashboard', [DashboardController::class, 'statsApi'])->name('stats.dashboard');

    // Notifications en temps réel
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Vérification de disponibilité
    Route::post('/check-matricule', [UserController::class, 'checkMatricule'])->name('check.matricule');
    Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');
});
