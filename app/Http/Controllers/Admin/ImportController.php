<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index()
    {
        $this->authorize('import', User::class);

        $logs = ImportLog::with('importePar')
            ->latest()
            ->paginate(20);

        return view('admin.import.index', compact('logs'));
    }

    public function showImportForm()
    {
        $this->authorize('import', User::class);

        $filieres = Filiere::actives()->get();

        return view('admin.import.form', compact('filieres'));
    }

    public function importEtudiants(Request $request)
    {
        $this->authorize('import', User::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_etude' => 'required|in:L1,L2,L3,M1,M2'
        ]);

        $filiere = Filiere::find($request->filiere_id);
        $niveau = $request->niveau_etude;

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('imports', $fileName);

        DB::beginTransaction();
        try {
            $data = $this->parseFile(storage_path('app/imports/' . $fileName));

            $totalLignes = count($data);
            $succes = 0;
            $erreurs = 0;
            $erreursDetails = [];

            foreach ($data as $index => $row) {
                try {
                    // Vérifier les champs requis
                    if (empty($row['matricule']) || empty($row['nom']) || empty($row['email'])) {
                        throw new \Exception("Données manquantes");
                    }

                    // Vérifier l'unicité du matricule et email
                    if (User::where('matricule', $row['matricule'])->exists()) {
                        throw new \Exception("Matricule déjà existant: " . $row['matricule']);
                    }

                    if (User::where('email', $row['email'])->exists()) {
                        throw new \Exception("Email déjà existant: " . $row['email']);
                    }

                    // Créer l'étudiant
                    User::create([
                        'matricule' => $row['matricule'],
                        'name' => $row['nom'] . ' ' . ($row['prenom'] ?? ''),
                        'email' => $row['email'],
                        'password' => Hash::make($row['matricule']), // Mot de passe par défaut = matricule
                        'role' => 'etudiant',
                        'filiere_id' => $filiere->id,
                        'niveau_etude' => $niveau,
                        'telephone' => $row['telephone'] ?? null,
                        'active' => true
                    ]);

                    $succes++;

                } catch (\Exception $e) {
                    $erreurs++;
                    $erreursDetails[] = [
                        'ligne' => $index + 2,
                        'matricule' => $row['matricule'] ?? 'N/A',
                        'erreur' => $e->getMessage()
                    ];
                }
            }

            // Créer le log d'import
            ImportLog::create([
                'type_import' => 'etudiants',
                'fichier' => $fileName,
                'nombre_lignes' => $totalLignes,
                'nombre_succes' => $succes,
                'nombre_erreurs' => $erreurs,
                'erreurs_details' => $erreursDetails,
                'importe_par' => auth()->id()
            ]);

            DB::commit();

            $message = "Import terminé: $succes étudiants importés avec succès";
            if ($erreurs > 0) {
                $message .= ", $erreurs erreurs rencontrées";
            }

            return redirect()->route('admin.import.form')
                ->with('success', $message)
                ->with('erreurs', $erreursDetails);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importEnseignants(Request $request)
    {
        $this->authorize('import', User::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'departement' => 'required|string'
        ]);

        $departement = $request->departement;
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('imports', $fileName);

        DB::beginTransaction();
        try {
            $data = $this->parseFile(storage_path('app/imports/' . $fileName));

            $totalLignes = count($data);
            $succes = 0;
            $erreurs = 0;
            $erreursDetails = [];

            foreach ($data as $index => $row) {
                try {
                    if (empty($row['nom']) || empty($row['email'])) {
                        throw new \Exception("Données manquantes");
                    }

                    if (User::where('email', $row['email'])->exists()) {
                        throw new \Exception("Email déjà existant: " . $row['email']);
                    }

                    User::create([
                        'name' => $row['nom'] . ' ' . ($row['prenom'] ?? ''),
                        'email' => $row['email'],
                        'password' => Hash::make(Str::random(8)), // Mot de passe temporaire
                        'role' => 'enseignant',
                        'departement' => $departement,
                        'telephone' => $row['telephone'] ?? null,
                        'specialite' => $row['specialite'] ?? null,
                        'active' => true
                    ]);

                    $succes++;

                } catch (\Exception $e) {
                    $erreurs++;
                    $erreursDetails[] = [
                        'ligne' => $index + 2,
                        'nom' => $row['nom'] ?? 'N/A',
                        'erreur' => $e->getMessage()
                    ];
                }
            }

            ImportLog::create([
                'type_import' => 'enseignants',
                'fichier' => $fileName,
                'nombre_lignes' => $totalLignes,
                'nombre_succes' => $succes,
                'nombre_erreurs' => $erreurs,
                'erreurs_details' => $erreursDetails,
                'importe_par' => auth()->id()
            ]);

            DB::commit();

            $message = "Import terminé: $succes enseignants importés avec succès";
            if ($erreurs > 0) {
                $message .= ", $erreurs erreurs rencontrées";
            }

            return redirect()->route('admin.import.form')
                ->with('success', $message)
                ->with('erreurs', $erreursDetails);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate($type)
    {
        $this->authorize('import', User::class);

        $headers = [];
        $data = [];

        if ($type === 'etudiants') {
            $headers = ['matricule', 'nom', 'prenom', 'email', 'telephone'];
            $data = [
                ['ETU001', 'BA', 'Aissata', 'aissata.ba@email.com', '0612345678'],
                ['ETU002', 'DIALLO', 'Mamadou', 'mamadou.diallo@email.com', '0623456789'],
            ];
        } elseif ($type === 'enseignants') {
            $headers = ['nom', 'prenom', 'email', 'telephone', 'specialite'];
            $data = [
                ['DIAKITE', 'Mohamed Lamine', 'ml.diakite@email.com', '0612345678', 'Génie Logiciel'],
                ['FALL', 'Fatou', 'f.fall@email.com', '0623456789', 'Base de données'],
            ];
        }

        $fileName = "template_import_{$type}.csv";
        $handle = fopen('php://temp', 'r+');

        // Écrire les en-têtes
        fputcsv($handle, $headers);

        // Écrire les exemples de données
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }

    private function parseFile($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $data = [];

        if ($extension === 'csv') {
            $handle = fopen($filePath, 'r');
            $headers = fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === count($headers)) {
                    $data[] = array_combine($headers, $row);
                }
            }

            fclose($handle);
        } else {
            // Pour Excel, utiliser une librairie comme Maatwebsite/Excel
            // ou PHPSpreadsheet
            $data = Excel::toArray(null, $filePath)[0];

            // Transformer en tableau associatif
            $headers = array_shift($data);
            $data = array_map(function($row) use ($headers) {
                return array_combine($headers, $row);
            }, $data);
        }

        return $data;
    }
}
