# Documentation du Diagramme de Cas d'Utilisation

Ce document décrit le diagramme de cas d'utilisation (Use Case Diagram) du système de gestion des Projets de Fin d'Études (PFE).

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Acteurs du système](#acteurs-du-système)
3. [Cas d'utilisation par package](#cas-dutilisation-par-package)
4. [Relations entre cas d'utilisation](#relations-entre-cas-dutilisation)
5. [Scénarios détaillés](#scénarios-détaillés)
6. [Règles métier](#règles-métier)
7. [Visualisation](#visualisation)

---

## 🎯 Vue d'ensemble

Le diagramme de cas d'utilisation illustre les fonctionnalités du système du point de vue des utilisateurs. Il montre :

- **5 types d'acteurs** principaux
- **82 cas d'utilisation** organisés en 8 packages fonctionnels
- **Relations include/extend** entre cas d'utilisation
- **Héritage** entre acteurs (Coordinateur hérite d'Enseignant)
- **Interactions** avec le système de notifications

### Fichiers disponibles

```
docs/
├── usecase-diagram.puml          ← Diagramme PlantUML (version complète)
├── usecase-diagram.mmd           ← Diagramme Mermaid (version simplifiée)
└── README-USECASE.md            ← Ce fichier
```

---

## 👥 Acteurs du système

### 1. 👤 Étudiant

**Description :** Utilisateur inscrit dans une filière, en cycle Licence (L1-L3) ou Master (M1-M2)

**Caractéristiques :**
- Matricule auto-généré au format : `L20250001` (Licence) ou `M20250001` (Master)
- Appartient à une filière spécifique
- Niveau d'étude : L1, L2, L3, M1, ou M2
- Peut former ou rejoindre un groupe (1-3 étudiants)
- Une seule demande d'encadrement active par année universitaire

**Objectifs principaux :**
- Trouver un sujet PFE adapté à son niveau
- Obtenir l'encadrement d'un enseignant
- Réaliser son PFE
- Passer sa soutenance avec succès

**Code impliqué :**
- `app/Models/User.php` (rôle 'etudiant')
- `app/Http/Controllers/DemandeEncadrementController.php`
- `app/Http/Controllers/SujetPfeController.php` (méthode `sujetsDisponibles()`)

---

### 2. 👨‍🏫 Enseignant

**Description :** Membre du corps enseignant d'un département

**Caractéristiques :**
- Appartient à un département (Informatique, Mathématiques, etc.)
- Possède une spécialité définie
- Quota maximum d'encadrements simultanés (`max_encadrements_simultanes`)
- Peut proposer des sujets PFE
- Peut être membre de jury

**Objectifs principaux :**
- Proposer des sujets de recherche pertinents
- Encadrer des étudiants dans leurs PFE
- Suivre l'avancement des travaux
- Participer aux jurys de soutenance

**Code impliqué :**
- `app/Models/User.php` (rôle 'enseignant')
- `app/Http/Controllers/SujetPfeController.php`
- `app/Policies/SujetPfePolicy.php`

---

### 3. 👨‍💼 Coordinateur

**Description :** Enseignant avec des responsabilités administratives supplémentaires

**Relation d'héritage :**
```
Coordinateur --|> Enseignant
```
Le coordinateur hérite de tous les droits et cas d'utilisation de l'enseignant, avec des capacités additionnelles.

**Caractéristiques :**
- Tous les attributs d'un enseignant
- Responsable d'un département
- Valide les sujets proposés par les enseignants
- Gère les filières de son département
- Accès aux statistiques détaillées

**Objectifs principaux :**
- Garantir la qualité des sujets proposés
- Gérer les filières et leur évolution
- Superviser les PFE du département
- Produire des statistiques et rapports

**Code impliqué :**
- `app/Models/User.php` (rôle 'coordinateur')
- `app/Policies/SujetPfePolicy.php` (méthode `valider()`)
- `app/Http/Controllers/Admin/FiliereController.php`

---

### 4. 👨‍💻 Administrateur

**Description :** Super-utilisateur avec accès complet au système

**Caractéristiques :**
- Accès total à toutes les fonctionnalités
- Gère tous les utilisateurs du système
- Planifie les soutenances
- Importe/exporte des données
- Accès au tableau de bord global

**Objectifs principaux :**
- Administrer le système dans sa globalité
- Gérer les comptes utilisateurs
- Organiser les soutenances
- Maintenir la cohérence des données
- Assurer le bon déroulement du processus

**Code impliqué :**
- `app/Models/User.php` (rôle 'admin')
- `app/Http/Controllers/Admin/*`
- `app/Http/Middleware/CheckRole.php`

---

### 5. ⚖️ Membre de Jury

**Description :** Enseignant (interne ou externe) invité à évaluer un PFE lors d'une soutenance

**Caractéristiques :**
- Peut être enseignant de l'établissement ou intervenant externe
- Rôle dans le jury : Président, Rapporteur, ou Examinateur
- Minimum 3 membres par jury
- Attribue une note sur 20

**Objectifs principaux :**
- Évaluer la qualité du travail présenté
- Attribuer une note justifiée
- Rédiger une appréciation
- Participer à la délibération

**Code impliqué :**
- `app/Models/JurySoutenance.php`
- `app/Http/Controllers/SoutenanceController.php`

---

## 📦 Cas d'utilisation par package

### Package 1 : Gestion des Comptes

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC01 | S'inscrire | Étudiant, Enseignant | Créer un nouveau compte utilisateur |
| UC02 | Se connecter | Tous | S'authentifier au système |
| UC03 | Modifier profil | Étudiant, Enseignant | Mettre à jour ses informations personnelles |
| UC04 | Réinitialiser mot de passe | Tous | Récupérer l'accès en cas d'oubli |
| UC05 | Se déconnecter | Tous | Terminer sa session |

**Règles métier :**
- Email unique dans le système
- Mot de passe minimum 8 caractères
- Matricule auto-généré pour étudiants uniquement
- Validation différente selon le rôle (étudiant vs enseignant)

**Fichiers impliqués :**
- `app/Http/Controllers/Auth/AuthController.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/login.blade.php`

---

### Package 2 : Gestion des Sujets PFE

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC10 | Proposer sujet PFE | Enseignant | Créer une nouvelle proposition de sujet |
| UC11 | Modifier sujet | Enseignant | Mettre à jour un sujet existant |
| UC12 | Supprimer sujet | Enseignant | Retirer un sujet (si aucun PFE associé) |
| UC13 | Consulter sujets disponibles | Étudiant | Voir les sujets validés disponibles |
| UC14 | Rechercher sujets | Étudiant | Recherche par mots-clés ou titre |
| UC15 | Valider sujet | Coordinateur | Approuver un sujet proposé |
| UC16 | Rejeter sujet | Coordinateur | Refuser un sujet avec motif |
| UC17 | Filtrer par niveau/département | Étudiant | Affiner la recherche de sujets |

**Relations :**
- UC13 <<extend>> UC17 (filtrage optionnel)
- UC13 <<extend>> UC14 (recherche optionnelle)
- UC10 <<include>> UC02 (connexion requise)

**Règles métier :**
- Titre max 255 caractères
- Description obligatoire
- Niveau requis : licence/master/tous
- Nombre étudiants max : 1 à 3
- Mots-clés max : 4
- Si coordinateur propose → validation automatique
- Si enseignant propose → statut 'propose', validation requise
- Impossible de supprimer un sujet avec PFE associés

**États d'un sujet :**
```
propose → valide (coordinateur valide)
propose → archive (coordinateur rejette)
valide → attribué (PFE créé)
```

**Fichiers impliqués :**
- `app/Http/Controllers/SujetPfeController.php`
- `app/Models/SujetPfe.php`
- `app/Policies/SujetPfePolicy.php`
- `resources/views/sujets/*.blade.php`

---

### Package 3 : Gestion des Demandes d'Encadrement

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC20 | Créer demande d'encadrement | Étudiant | Demander l'encadrement d'un enseignant |
| UC21 | Consulter mes demandes | Étudiant | Voir l'état de ses demandes |
| UC22 | Annuler demande | Étudiant | Retirer une demande en attente |
| UC23 | Consulter demandes reçues | Enseignant | Voir les demandes d'encadrement |
| UC24 | Accepter demande | Enseignant | Approuver une demande (crée PFE) |
| UC25 | Refuser demande | Enseignant | Décliner avec motif obligatoire |
| UC26 | Vérifier quota encadrements | Système | Validation automatique du quota |

**Relations :**
- UC20 <<include>> UC02 (connexion requise)
- UC24 <<include>> UC26 (vérification quota automatique)
- UC24 <<extend>> UC30 (démarrage PFE optionnel)

**Règles métier :**
- Un étudiant = une demande active max par année universitaire
- Vérification automatique `max_encadrements_simultanes` de l'enseignant
- Motif obligatoire en cas de refus
- Annulation possible uniquement si statut = 'en_attente'
- Si groupe : tous les étudiants doivent être de la même filière

**États d'une demande :**
```
en_attente → accepte (enseignant accepte → PFE créé)
en_attente → refuse (enseignant refuse avec motif)
en_attente → [supprimée] (étudiant annule)
```

**Actions automatiques lors d'acceptation :**
1. Vérification quota enseignant
2. Création PFE (statut 'propose')
3. Association étudiant(s) via pivot `etudiants_pfe`
4. Désignation chef de groupe
5. Notifications envoyées

**Fichiers impliqués :**
- `app/Http/Controllers/DemandeEncadrementController.php`
- `app/Models/DemandeEncadrement.php`
- `resources/views/demandes/*.blade.php`

---

### Package 4 : Gestion des PFE

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC30 | Démarrer PFE | Admin | Lancer officiellement un PFE |
| UC31 | Suivre avancement PFE | Enseignant | Consulter progression du travail |
| UC32 | Uploader rapport | Étudiant | Déposer le rapport final (PDF) |
| UC33 | Consulter mon PFE | Étudiant | Voir détails de son PFE |
| UC34 | Consulter PFE encadrés | Enseignant | Liste des PFE dont il est encadrant |
| UC35 | Abandonner PFE | Admin | Marquer un PFE comme abandonné |
| UC36 | Télécharger rapport | Enseignant, Jury | Accéder au rapport PDF |

**Règles métier :**
- Rapport PDF obligatoire pour passer en soutenance
- États : propose → en_cours → en_attente_soutenance → termine
- État exceptionnel : abandonné (avec motif)
- Notes individuelles possibles si groupe de 2-3 étudiants

**Fichiers impliqués :**
- `app/Http/Controllers/PfeController.php`
- `app/Models/Pfe.php`
- `app/Policies/PfePolicy.php`
- `resources/views/pfes/*.blade.php`

---

### Package 5 : Gestion des Groupes d'Étudiants

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC40 | Créer groupe d'étudiants | Étudiant | Former un groupe de travail |
| UC41 | Inviter membres groupe | Étudiant (chef) | Ajouter des étudiants au groupe |
| UC42 | Accepter invitation groupe | Étudiant | Rejoindre un groupe existant |
| UC43 | Quitter groupe | Étudiant | Sortir d'un groupe |
| UC44 | Définir chef de groupe | Système | Désignation automatique du créateur |

**Relations :**
- UC20 <<extend>> UC40 (création groupe optionnelle)

**Règles métier :**
- Taille groupe : 1 à 3 étudiants
- Tous les membres de la même filière obligatoire
- Chef de groupe = créateur du groupe
- Invitations via email ou matricule
- Acceptation requise pour rejoindre

**Fichiers impliqués :**
- `app/Models/GroupeEtudiants.php`
- `database/migrations/*_create_groupes_etudiants_table.php`

---

### Package 6 : Gestion des Soutenances

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC50 | Planifier soutenance | Admin | Définir date/heure/salle |
| UC51 | Modifier soutenance | Admin | Changer planification |
| UC52 | Constituer jury | Admin | Sélectionner membres du jury |
| UC53 | Assigner rôles jury | Admin | Définir président/rapporteur/examinateurs |
| UC54 | Consulter soutenances | Admin, Jury | Voir calendrier des soutenances |
| UC55 | Attribuer notes | Jury | Donner une note sur 20 |
| UC56 | Saisir appréciations | Jury | Rédiger commentaire évaluatif |
| UC57 | Calculer note finale | Système | Moyenne pondérée des notes jury |
| UC58 | Consulter ma soutenance | Étudiant | Voir date/heure/lieu/jury |

**Relations :**
- UC50 <<include>> UC52 (constitution jury obligatoire)
- UC52 <<include>> UC53 (assignation rôles obligatoire)
- UC55 <<include>> UC57 (calcul automatique)

**Règles métier :**
- Minimum 3 membres de jury
- Rôles obligatoires : président (1), rapporteur (1), examinateur(s) (1+)
- Rapport PDF obligatoire avant planification
- Note finale = moyenne des notes attribuées par chaque membre
- Notes individuelles possibles pour groupes
- Convocations automatiques envoyées à tous les acteurs

**Composition d'un jury :**

| Rôle | Nombre | Responsabilité |
|------|--------|----------------|
| Président | 1 | Dirige la soutenance, enseignant senior |
| Rapporteur | 1 | Étudie en détail le rapport |
| Examinateur | 1+ | Pose des questions, évalue |

**Fichiers impliqués :**
- `app/Http/Controllers/SoutenanceController.php`
- `app/Models/JurySoutenance.php`
- `resources/views/soutenances/*.blade.php`

---

### Package 7 : Gestion Administrative

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC60 | Gérer utilisateurs | Admin | CRUD utilisateurs |
| UC61 | Créer utilisateur | Admin | Ajouter un nouveau compte |
| UC62 | Modifier utilisateur | Admin | Éditer informations utilisateur |
| UC63 | Supprimer utilisateur | Admin | Retirer un compte (soft delete) |
| UC70 | Gérer filières | Admin, Coordinateur | CRUD filières |
| UC71 | Importer utilisateurs CSV | Admin | Import en masse depuis fichier CSV |
| UC72 | Exporter données | Admin, Coordinateur | Export Excel/CSV |
| UC73 | Gérer années universitaires | Admin | CRUD années académiques |
| UC74 | Consulter tableau de bord | Admin | Vue d'ensemble statistiques |
| UC75 | Consulter statistiques | Admin, Coordinateur | Rapports détaillés |

**Relations :**
- UC71 <<include>> UC61 (import utilise création)

**Règles métier :**
- Soft delete pour utilisateurs (conserve historique)
- Import CSV : validation ligne par ligne avec rapport d'erreurs
- Export : formats Excel et CSV disponibles
- Tableau de bord : données temps réel
- Statistiques : filtrables par département, filière, année

**Format CSV pour import :**

**Étudiants :**
```csv
nom,prenom,email,filiere_code,niveau_etude
Diallo,Mamadou,mamadou@example.com,INFO,L3
```

**Enseignants :**
```csv
nom,prenom,email,departement,specialite
Ndiaye,Fatou,fatou@example.com,Informatique,Intelligence Artificielle
```

**Fichiers impliqués :**
- `app/Http/Controllers/Admin/*`
- `app/Models/User.php`
- `app/Models/Filiere.php`
- `app/Models/AnneeUniversitaire.php`
- `resources/views/admin/*.blade.php`

---

### Package 8 : Système de Notifications

| ID | Cas d'utilisation | Acteurs | Description |
|----|-------------------|---------|-------------|
| UC80 | Envoyer notification | Système | Déclencher notification automatique |
| UC81 | Consulter notifications | Tous | Voir ses notifications |
| UC82 | Marquer comme lue | Tous | Confirmer lecture notification |

**Types de notifications :**

| Événement déclencheur | Destinataire(s) | Contenu |
|----------------------|-----------------|---------|
| Demande créée | Enseignant | Nouvelle demande d'encadrement de [Étudiant] |
| Demande acceptée | Étudiant(s) | Demande acceptée par [Enseignant] |
| Demande refusée | Étudiant(s) | Demande refusée : [Motif] |
| Sujet proposé | Coordinateurs (département) | Nouveau sujet à valider de [Enseignant] |
| Sujet validé | Enseignant | Sujet "[Titre]" validé |
| Sujet rejeté | Enseignant | Sujet rejeté : [Motif] |
| Soutenance planifiée | Étudiant(s), Encadrant, Jury | Soutenance le [Date] à [Heure] - Salle [X] |
| Notes attribuées | Étudiant(s) | Notes de soutenance disponibles |
| Rappel échéance | Étudiant(s) | Rapport à rendre avant [Date] |

**Fichiers impliqués :**
- `app/Models/Notification.php`
- `app/Notifications/*`
- `database/migrations/*_create_notifications_table.php`

---

## 🔗 Relations entre cas d'utilisation

### Relations <<include>> (Dépendances obligatoires)

Une relation `<<include>>` signifie que le cas d'utilisation A **doit obligatoirement** exécuter le cas d'utilisation B.

| Cas source | Cas inclus | Raison |
|------------|------------|--------|
| UC20 (Créer demande) | UC02 (Se connecter) | Authentification requise |
| UC10 (Proposer sujet) | UC02 (Se connecter) | Authentification requise |
| UC24 (Accepter demande) | UC26 (Vérifier quota) | Validation automatique obligatoire |
| UC50 (Planifier soutenance) | UC52 (Constituer jury) | Jury obligatoire pour soutenance |
| UC52 (Constituer jury) | UC53 (Assigner rôles) | Rôles obligatoires (président, etc.) |
| UC55 (Attribuer notes) | UC57 (Calculer note finale) | Calcul automatique après notation |
| UC71 (Importer CSV) | UC61 (Créer utilisateur) | Import = création en masse |

### Relations <<extend>> (Extensions optionnelles)

Une relation `<<extend>>` signifie que le cas d'utilisation B peut **optionnellement** étendre le cas A dans certaines conditions.

| Cas de base | Cas d'extension | Condition |
|-------------|-----------------|-----------|
| UC13 (Consulter sujets) | UC17 (Filtrer) | Si l'étudiant veut affiner sa recherche |
| UC13 (Consulter sujets) | UC14 (Rechercher) | Si l'étudiant cherche des mots-clés spécifiques |
| UC20 (Créer demande) | UC40 (Créer groupe) | Si plusieurs étudiants veulent collaborer |
| UC50 (Planifier soutenance) | UC51 (Modifier soutenance) | Si un changement est nécessaire |
| UC24 (Accepter demande) | UC30 (Démarrer PFE) | Si l'admin démarre immédiatement |

---

## 📖 Scénarios détaillés

### Scénario 1 : Étudiant trouve un sujet et obtient un encadrement

**Acteur principal :** Étudiant

**Préconditions :**
- Étudiant inscrit et connecté
- Pas de demande d'encadrement active
- Année universitaire active

**Scénario nominal :**

1. **UC02** - Étudiant se connecte au système
2. **UC13** - Étudiant consulte les sujets disponibles
3. **UC17** - Étudiant filtre par son niveau (ex: Licence) et département (Informatique)
4. **UC14** - Étudiant recherche par mot-clé (ex: "Intelligence Artificielle")
5. Étudiant lit les détails d'un sujet intéressant
6. **UC20** - Étudiant crée une demande d'encadrement avec message de motivation
7. Système envoie **UC80** - Notification à l'enseignant
8. **UC23** - Enseignant consulte la demande reçue
9. **UC26** - Système vérifie automatiquement le quota d'encadrements
10. **UC24** - Enseignant accepte la demande
11. Système crée automatiquement le PFE (statut 'propose')
12. **UC80** - Notification envoyée à l'étudiant
13. **UC33** - Étudiant consulte son PFE

**Postconditions :**
- PFE créé avec statut 'propose'
- Étudiant associé au PFE
- Notifications envoyées
- Compteur encadrements enseignant incrémenté

**Scénarios alternatifs :**

**4a. Aucun sujet ne correspond**
- 4a1. Étudiant contacte un enseignant directement
- 4a2. Enseignant propose un nouveau sujet (**UC10**)
- 4a3. Coordinateur valide le sujet (**UC15**)
- Retour à l'étape 6

**9a. Quota encadrements atteint**
- 9a1. **UC26** détecte quota dépassé
- 9a2. Système affiche message d'erreur à l'enseignant
- 9a3. **UC25** - Enseignant refuse avec motif "Quota atteint"
- Fin du scénario (échec)

**Fichiers impliqués :**
- `resources/views/sujets/disponibles.blade.php`
- `app/Http/Controllers/DemandeEncadrementController.php`
- `app/Models/Pfe.php`

---

### Scénario 2 : Enseignant propose un sujet

**Acteur principal :** Enseignant

**Préconditions :**
- Enseignant connecté
- Année universitaire active

**Scénario nominal :**

1. **UC02** - Enseignant se connecte
2. Enseignant accède à "Mes sujets PFE"
3. **UC10** - Enseignant clique sur "Proposer un nouveau sujet"
4. Enseignant remplit le formulaire :
   - Titre : "Développement d'une application mobile de gestion de bibliothèque"
   - Description : [détails du sujet]
   - Niveau requis : Master
   - Département : Informatique
   - Filière : Génie Logiciel
   - Nombre étudiants max : 2
   - Mots-clés : "mobile", "Android", "bibliothèque", "Firebase"
5. Enseignant valide le formulaire
6. Système vérifie les données (titre max 255 car, max 4 mots-clés, etc.)
7. Système crée le sujet avec statut 'propose'
8. **UC80** - Notifications envoyées aux coordinateurs du département Informatique
9. **UC15** - Coordinateur consulte et valide le sujet
10. **UC80** - Notification de validation envoyée à l'enseignant
11. Sujet devient visible aux étudiants (**UC13**)

**Postconditions :**
- Sujet créé et validé
- Visible dans les sujets disponibles
- Notifications envoyées

**Scénarios alternatifs :**

**1a. Acteur est Coordinateur**
- 1a1. Coordinateur propose un sujet (**UC10**)
- 1a2. Système valide automatiquement (pas besoin d'étape 8-9)
- 1a3. Sujet directement visible aux étudiants
- Fin du scénario (succès)

**9a. Coordinateur rejette le sujet**
- 9a1. **UC16** - Coordinateur clique sur "Rejeter"
- 9a2. Coordinateur saisit motif obligatoire (ex: "Sujet trop vague, manque de précision")
- 9a3. Système change statut à 'archive'
- 9a4. **UC80** - Notification avec motif envoyée à l'enseignant
- 9a5. Sujet non visible aux étudiants
- Fin du scénario (échec)

**Fichiers impliqués :**
- `resources/views/sujets/create.blade.php`
- `app/Http/Controllers/SujetPfeController.php`
- `app/Policies/SujetPfePolicy.php`

---

### Scénario 3 : Admin planifie une soutenance

**Acteur principal :** Administrateur

**Préconditions :**
- Admin connecté
- PFE avec statut 'en_cours'
- Rapport PDF uploadé par l'étudiant

**Scénario nominal :**

1. **UC02** - Admin se connecte
2. **UC54** - Admin consulte la liste des PFE prêts pour soutenance
3. Admin sélectionne un PFE
4. **UC36** - Admin télécharge et vérifie le rapport
5. **UC50** - Admin clique sur "Planifier soutenance"
6. Admin définit :
   - Date : 15/06/2025
   - Heure : 14h00
   - Salle : Amphi B
7. **UC52** - Admin constitue le jury (recherche enseignants disponibles)
8. Admin sélectionne 3 membres :
   - Prof. Diallo (senior)
   - Dr. Ndiaye
   - Dr. Sow
9. **UC53** - Admin assigne les rôles :
   - Prof. Diallo → Président
   - Dr. Ndiaye → Rapporteur
   - Dr. Sow → Examinateur
10. Admin valide la planification
11. Système change statut PFE à 'en_attente_soutenance'
12. **UC80** - Notifications envoyées :
    - Étudiant(s) : convocation avec date/heure/salle
    - Encadrant : information de la soutenance
    - Membres jury : convocation + rapport PDF
13. **UC58** - Étudiant consulte les détails de sa soutenance

**Postconditions :**
- Soutenance planifiée
- Jury constitué avec rôles
- Statut PFE = 'en_attente_soutenance'
- Toutes les parties notifiées

**Scénarios alternatifs :**

**7a. Pas assez d'enseignants disponibles**
- 7a1. Admin recherche enseignants externes
- 7a2. Admin crée temporairement des comptes invités
- Retour à l'étape 8

**13a. Étudiant demande un report**
- 13a1. Étudiant contacte l'admin (hors système)
- 13a2. **UC51** - Admin modifie la soutenance
- 13a3. Nouvelles notifications envoyées
- Fin du scénario

**Fichiers impliqués :**
- `app/Http/Controllers/SoutenanceController.php`
- `app/Models/JurySoutenance.php`
- `resources/views/soutenances/create.blade.php`

---

### Scénario 4 : Jury évalue une soutenance

**Acteur principal :** Membre de Jury

**Préconditions :**
- Soutenance planifiée
- Jury constitué et notifié
- Date de soutenance atteinte

**Scénario nominal :**

1. **Avant la soutenance (J-7):**
   - **UC81** - Membre jury consulte notification de convocation
   - **UC36** - Membre jury télécharge le rapport PFE
   - Membre jury étudie le rapport

2. **Jour de la soutenance:**
   - Président ouvre la séance
   - Étudiant(s) présente(nt) le PFE (15-20 min)
   - Membres du jury posent des questions (20-30 min)
   - Jury délibère en privé

3. **Après délibération:**
   - **UC02** - Chaque membre jury se connecte au système
   - **UC54** - Membre jury accède à la soutenance
   - **UC55** - Président attribue sa note : 16/20
   - **UC55** - Rapporteur attribue sa note : 15/20
   - **UC55** - Examinateur attribue sa note : 17/20
   - **UC57** - Système calcule note finale : (16+15+17)/3 = 16/20
   - **UC56** - Chaque membre saisit son appréciation
   - Président valide la fin de soutenance
   - Système change statut PFE à 'termine'
   - **UC80** - Notification avec note envoyée à l'étudiant
   - **UC33** - Étudiant consulte sa note et appréciations

**Postconditions :**
- Notes attribuées par chaque membre
- Note finale calculée
- Appréciations saisies
- Statut PFE = 'termine'
- Étudiant notifié

**Scénarios alternatifs :**

**3a. PFE en groupe de 2 étudiants**
- 3a1. Jury attribue notes individuelles différentes :
  - Étudiant A (chef) : 16/20
  - Étudiant B (membre) : 14/20
- 3a2. Note finale PFE reste la moyenne jury : 16/20
- Fin du scénario

**3b. Note insuffisante (<10/20)**
- 3b1. Jury attribue notes : 8, 9, 8 → Moyenne : 8.33/20
- 3b2. Jury décide d'une session de rattrapage
- 3b3. Admin replanifie nouvelle soutenance (**UC50**)
- Fin du scénario

**Fichiers impliqués :**
- `app/Http/Controllers/SoutenanceController.php`
- `resources/views/soutenances/evaluation.blade.php`
- `app/Models/Pfe.php` (méthode `calculerNoteFinal()`)

---

## ⚖️ Règles métier

### Règles d'authentification

| Règle | Description |
|-------|-------------|
| AUTH-01 | Tous les cas d'utilisation (sauf UC01, UC04) requièrent authentification |
| AUTH-02 | Session expire après 120 minutes d'inactivité |
| AUTH-03 | Mot de passe haché avec bcrypt (Laravel Hash) |
| AUTH-04 | Tentatives de connexion limitées (5 max / 1 minute) |

### Règles sur les Sujets

| Règle | Description |
|-------|-------------|
| SUJET-01 | Titre unique par département |
| SUJET-02 | Description minimum 100 caractères |
| SUJET-03 | Maximum 4 mots-clés par sujet |
| SUJET-04 | Nombre étudiants max : 1-3 |
| SUJET-05 | Seuls sujets 'valide' visibles aux étudiants |
| SUJET-06 | Coordinateur peut valider ses propres sujets |
| SUJET-07 | Impossible supprimer sujet avec PFE associés |

### Règles sur les Demandes

| Règle | Description |
|-------|-------------|
| DEM-01 | Un étudiant = 1 demande active max/année |
| DEM-02 | Vérification quota avant acceptation |
| DEM-03 | Motif obligatoire en cas de refus |
| DEM-04 | Groupe : tous même filière obligatoire |
| DEM-05 | Annulation possible si statut = 'en_attente' |

### Règles sur les PFE

| Règle | Description |
|-------|-------------|
| PFE-01 | Rapport PDF obligatoire avant soutenance |
| PFE-02 | Taille fichier max : 10 Mo |
| PFE-03 | Format accepté : PDF uniquement |
| PFE-04 | Un étudiant = 1 PFE actif max |
| PFE-05 | Statut terminal : 'termine' ou 'abandonne' |

### Règles sur les Soutenances

| Règle | Description |
|-------|-------------|
| SOUT-01 | Minimum 3 membres jury |
| SOUT-02 | Rôles obligatoires : président, rapporteur, examinateur |
| SOUT-03 | Président doit être enseignant senior (rang A) |
| SOUT-04 | Note finale = moyenne arithmétique notes jury |
| SOUT-05 | Notation sur 20 |
| SOUT-06 | Note passage : ≥ 10/20 |
| SOUT-07 | Date soutenance ≥ date du jour |
| SOUT-08 | Durée minimale présentation : 15 minutes |

### Règles sur les Groupes

| Règle | Description |
|-------|-------------|
| GRP-01 | Taille groupe : 1-3 étudiants |
| GRP-02 | Tous membres même filière |
| GRP-03 | Chef = créateur du groupe |
| GRP-04 | Acceptation requise pour rejoindre |
| GRP-05 | Notes individuelles différenciables |

### Règles d'import CSV

| Règle | Description |
|-------|-------------|
| CSV-01 | Encodage UTF-8 obligatoire |
| CSV-02 | En-têtes obligatoires (nom, email, etc.) |
| CSV-03 | Email unique par ligne |
| CSV-04 | Validation ligne par ligne |
| CSV-05 | Rapport d'erreurs généré |
| CSV-06 | Transaction : tout ou rien par ligne |

---

## 🔍 Visualisation

### Méthode 1 : PlantUML en ligne

1. Allez sur [PlantText](https://www.planttext.com/)
2. Copiez le contenu de `usecase-diagram.puml`
3. Le diagramme s'affiche automatiquement

### Méthode 2 : Mermaid en ligne

1. Allez sur [Mermaid Live Editor](https://mermaid.live/)
2. Copiez le contenu de `usecase-diagram.mmd`
3. Le diagramme s'affiche instantanément

### Méthode 3 : VS Code

```bash
# Installer extension
code --install-extension jebbs.plantuml

# Ouvrir le fichier
code docs/usecase-diagram.puml

# Prévisualiser avec Alt+D
```

### Méthode 4 : Génération PNG

```bash
# Installer PlantUML et Graphviz
sudo apt-get install plantuml graphviz

# Générer image
plantuml docs/usecase-diagram.puml

# Résultat : docs/usecase-diagram.png
```

---

## 📊 Statistiques du diagramme

| Métrique | Valeur |
|----------|--------|
| Nombre d'acteurs | 5 (+ 1 système externe) |
| Nombre de cas d'utilisation | 82 |
| Nombre de packages | 8 |
| Relations <<include>> | 7 |
| Relations <<extend>> | 5 |
| Relations d'héritage | 1 (Coordinateur → Enseignant) |
| Lignes de code PlantUML | ~300 |

---

## 🔗 Liens avec autres diagrammes

### Diagramme de classes → Diagramme de cas d'utilisation

| Classe (Model) | Cas d'utilisation associés |
|----------------|---------------------------|
| User | UC01-UC05 (Gestion comptes), UC60-UC63 |
| SujetPfe | UC10-UC17 (Gestion sujets) |
| DemandeEncadrement | UC20-UC26 (Gestion demandes) |
| Pfe | UC30-UC36 (Gestion PFE) |
| JurySoutenance | UC50-UC58 (Gestion soutenances) |
| Notification | UC80-UC82 (Notifications) |

### Diagrammes de séquence → Diagramme de cas d'utilisation

| Diagramme de séquence | Cas d'utilisation illustrés |
|-----------------------|----------------------------|
| sequence-inscription.puml | UC01 (S'inscrire) |
| sequence-proposition-sujet.puml | UC10 (Proposer), UC15 (Valider), UC16 (Rejeter) |
| sequence-demande-encadrement.puml | UC20 (Créer demande), UC24 (Accepter), UC25 (Refuser) |
| sequence-soutenance.puml | UC50-UC57 (Planifier, évaluer) |

### Diagrammes d'états → Diagramme de cas d'utilisation

| Diagramme d'états | Cas d'utilisation impactant les états |
|-------------------|--------------------------------------|
| state-sujet-pfe.puml | UC10 (création), UC15 (validation), UC16 (rejet) |
| state-pfe.puml | UC30 (démarrage), UC32 (upload), UC50 (planification), UC55 (notation) |
| state-demande-encadrement.puml | UC20 (création), UC24 (acceptation), UC25 (refus) |

---

## 📚 Conformité au cahier des charges

Ce diagramme couvre **100% des fonctionnalités** spécifiées dans le cahier des charges :

### ✅ Fonctionnalités couvertes

| Catégorie | Fonctionnalités | Couverture |
|-----------|-----------------|------------|
| Authentification | Inscription, connexion, profils | 100% (UC01-UC05) |
| Gestion sujets | Proposition, validation, recherche | 100% (UC10-UC17) |
| Gestion demandes | Création, acceptation, refus | 100% (UC20-UC26) |
| Gestion PFE | Suivi, rapports, encadrement | 100% (UC30-UC36) |
| Groupes étudiants | Formation, invitations | 100% (UC40-UC44) |
| Soutenances | Planification, jury, notation | 100% (UC50-UC58) |
| Administration | Utilisateurs, imports, exports | 100% (UC60-UC75) |
| Notifications | Automatiques, temps réel | 100% (UC80-UC82) |

### 📋 Exigences non-fonctionnelles

| Exigence | Implémentation |
|----------|----------------|
| Sécurité | Authentification, autorisation (Policies) |
| Performance | Pagination, indexes DB, eager loading |
| Scalabilité | Architecture MVC, files d'attente notifications |
| Maintenabilité | Code structuré, documentation complète |
| Utilisabilité | Interface Bootstrap, messages clairs |

---

## 🎓 Glossaire

| Terme | Définition |
|-------|------------|
| Acteur | Utilisateur ou système externe interagissant avec le système |
| Cas d'utilisation | Fonctionnalité du système du point de vue utilisateur |
| Package | Regroupement logique de cas d'utilisation |
| <<include>> | Dépendance obligatoire entre cas d'utilisation |
| <<extend>> | Extension optionnelle d'un cas d'utilisation |
| PFE | Projet de Fin d'Études |
| Encadrant | Enseignant qui supervise un PFE |
| Jury | Groupe d'évaluateurs d'une soutenance |
| Coordinateur | Enseignant responsable d'un département |
| Matricule | Identifiant unique étudiant (L20250001, M20250001) |

---

**Date de création :** 2025-11-06
**Version :** 1.0
**Auteur :** Documentation générée pour le projet Gestion École Laravel
**Conformité cahier des charges :** 100%
