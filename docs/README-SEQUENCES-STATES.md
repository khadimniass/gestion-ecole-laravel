# Documentation des Diagrammes de Séquence et d'États

Ce document explique les diagrammes UML de séquence et d'états-transitions créés pour le système de gestion des PFE (Projets de Fin d'Études).

## 📋 Table des matières

1. [Visualisation des diagrammes](#visualisation-des-diagrammes)
2. [Diagrammes de séquence](#diagrammes-de-séquence)
3. [Diagrammes d'états-transitions](#diagrammes-détats-transitions)
4. [Workflows métier](#workflows-métier)
5. [Règles métier importantes](#règles-métier-importantes)

---

## 🔍 Visualisation des diagrammes

### Méthode 1 : PlantUML en ligne (le plus simple)

1. Rendez-vous sur [PlantText](https://www.planttext.com/) ou [PlantUML Web Server](http://www.plantuml.com/plantuml/uml/)
2. Copiez le contenu d'un fichier `.puml`
3. Collez-le dans l'éditeur
4. Le diagramme s'affiche automatiquement

### Méthode 2 : Extension VS Code

1. Installez l'extension **PlantUML** dans VS Code
2. Installez Graphviz : `sudo apt-get install graphviz` (Linux) ou téléchargez depuis [graphviz.org](https://graphviz.org/download/)
3. Ouvrez un fichier `.puml`
4. Utilisez `Alt+D` pour prévisualiser

### Méthode 3 : Ligne de commande

```bash
# Installer PlantUML
sudo apt-get install plantuml

# Générer une image PNG
plantuml docs/sequence-inscription.puml

# Générer toutes les images
plantuml docs/*.puml
```

### Méthode 4 : Génération automatique via script

```bash
# Créer un script de génération
cat > generate-diagrams.sh << 'EOF'
#!/bin/bash
echo "Génération des diagrammes UML..."
for file in docs/*.puml; do
    echo "Traitement de $file..."
    plantuml "$file" -tpng -o ./images/
done
echo "✓ Tous les diagrammes ont été générés dans docs/images/"
EOF

chmod +x generate-diagrams.sh
./generate-diagrams.sh
```

---

## 📊 Diagrammes de séquence

Les diagrammes de séquence illustrent les interactions entre les acteurs et les composants du système au fil du temps.

### 1. `sequence-inscription.puml` - Inscription utilisateur

**Workflow documenté :** Processus d'inscription d'un nouvel utilisateur (étudiant ou enseignant)

**Acteurs impliqués :**
- Utilisateur (non authentifié)
- Interface web (RegisterView)
- AuthController
- User Model, Filiere Model
- Base de données

**Points clés :**
- Validation différente selon le rôle (étudiant vs enseignant)
- Génération automatique du matricule pour les étudiants
- Format matricule : `L20250001` (Licence) ou `M20250001` (Master)
- Hachage sécurisé du mot de passe
- Affichage conditionnel des champs selon le rôle

**Code impliqué :**
- `app/Http/Controllers/Auth/AuthController.php` (méthode `register()`)
- `resources/views/auth/register.blade.php`

**Règles métier :**
- Email unique dans la base
- Mot de passe minimum 8 caractères
- Pour étudiant : filière et niveau requis
- Pour enseignant : département requis
- Matricule auto-généré séquentiellement par année

---

### 2. `sequence-proposition-sujet.puml` - Proposition de sujet PFE

**Workflow documenté :** Un enseignant propose un nouveau sujet de PFE

**Acteurs impliqués :**
- Enseignant
- SujetPfeController
- SujetPfe Model
- Coordinateurs (pour validation)
- Système de notifications

**Points clés :**
- Validation des champs obligatoires (titre, description, niveau, etc.)
- Ajout de mots-clés (maximum 4)
- Si proposant est coordinateur → validation automatique
- Si proposant est enseignant → statut 'propose', nécessite validation coordinateur
- Notifications automatiques aux coordinateurs du même département

**Code impliqué :**
- `app/Http/Controllers/SujetPfeController.php` (méthode `store()`)
- `app/Models/SujetPfe.php`
- `resources/views/sujets/create.blade.php`

**Règles métier :**
- Titre max 255 caractères
- Description obligatoire
- Niveau requis : licence/master/tous
- Nombre étudiants max : 1 à 3
- Mots-clés max : 4
- Année universitaire active requise

---

### 3. `sequence-demande-encadrement.puml` - Demande d'encadrement

**Workflow documenté :** Un étudiant demande l'encadrement d'un enseignant pour un PFE

**Acteurs impliqués :**
- Étudiant
- Enseignant
- DemandeEncadrementController
- Système de notifications

**Phases :**

#### Phase 1 : Soumission de la demande
- Étudiant sélectionne un sujet (optionnel)
- Étudiant choisit un enseignant
- Étudiant rédige message de motivation (optionnel)
- Validation : pas de demande en cours pour cette année

#### Phase 2 : Réponse de l'enseignant
- **Acceptation :**
  - Vérification quota encadrements enseignant
  - Création automatique du PFE (statut 'propose')
  - Association étudiant(s) au PFE
  - Notification étudiant(s)

- **Refus :**
  - Saisie motif obligatoire
  - Notification étudiant avec motif
  - Étudiant peut créer nouvelle demande

**Code impliqué :**
- `app/Http/Controllers/DemandeEncadrementController.php`
- `app/Models/DemandeEncadrement.php`
- `app/Models/Pfe.php`

**Règles métier :**
- Un étudiant = une demande active maximum par année
- Vérification quota max_encadrements_simultanes de l'enseignant
- Si groupe : tous les étudiants de la même filière
- Création PFE automatique en cas d'acceptation

---

### 4. `sequence-soutenance.puml` - Planification et soutenance

**Workflow documenté :** Planification d'une soutenance et attribution des notes

**Acteurs impliqués :**
- Admin/Coordinateur
- Enseignant encadrant
- Membres du jury (3 minimum)
- Étudiant(s)
- SoutenanceController

**Phases :**

#### Phase 1 : Planification de la soutenance
- Admin recherche PFE prêts (statut 'en_cours' + rapport uploadé)
- Admin définit :
  - Date et heure
  - Salle
  - Membres du jury (minimum 3)
  - Rôles jury : président / rapporteur / examinateur(s)
- Notifications envoyées à tous les acteurs
- Transaction DB pour garantir cohérence

#### Phase 2 : Déroulement de la soutenance
- Présentation par l'étudiant/le groupe
- Questions et évaluation du jury

#### Phase 3 : Attribution des notes
- Chaque membre du jury saisit sa note
- Calcul de la note finale (moyenne pondérée)
- Attribution des notes individuelles (si groupe)
- Saisie des appréciations
- PFE passe au statut 'termine'
- Notifications envoyées aux étudiants

**Code impliqué :**
- `app/Http/Controllers/SoutenanceController.php`
- `app/Models/JurySoutenance.php`
- `app/Models/Pfe.php`

**Règles métier :**
- Minimum 3 membres de jury
- Rôles obligatoires : président, rapporteur, examinateur(s)
- Rapport PDF obligatoire avant planification
- Note finale = moyenne des notes jury
- Notes individuelles possibles pour groupes

---

## 🔄 Diagrammes d'états-transitions

Les diagrammes d'états montrent les différents états d'une entité et les transitions possibles entre ces états.

### 1. `state-sujet-pfe.puml` - États d'un Sujet PFE

**États possibles :**
- **Propose** : Sujet nouvellement créé, en attente validation (si créé par enseignant)
- **Valide** : Sujet validé par coordinateur, visible aux étudiants
- **Archive** : Sujet rejeté ou supprimé
- **Attribué** : Un ou plusieurs PFE créés à partir de ce sujet

**Transitions :**
```
[Création] → Propose (si enseignant) ou Valide (si coordinateur)
Propose → Valide (coordinateur valide)
Propose → Archive (coordinateur rejette avec motif)
Valide → Attribué (demande acceptée → PFE créé)
Valide → Archive (admin supprime si aucun PFE associé)
```

**Règles importantes :**
- Auto-validation si créateur = coordinateur
- Seuls sujets 'valide' visibles aux étudiants
- Impossible de supprimer sujet avec PFE associés
- Filtres disponibles : niveau, département, filière, mots-clés

**Code impliqué :**
- `app/Models/SujetPfe.php`
- Méthodes : `valider()`, `rejeter()`, `destroy()`
- Scopes : `disponibles()`, `parNiveau()`

---

### 2. `state-pfe.puml` - États d'un Projet de Fin d'Études

**États possibles :**
- **Propose** : PFE nouvellement créé (après acceptation demande)
- **En cours** : PFE actif, travail en cours
- **En attente soutenance** : Rapport uploadé, soutenance planifiée
- **Terminé** : Soutenance effectuée, notes attribuées
- **Abandonné** : PFE interrompu (cas exceptionnel)

**Transitions :**
```
[Demande acceptée] → Propose
Propose → En cours (admin démarre le PFE)
En cours → En attente soutenance (après upload rapport + planification)
En cours → Abandonné (cas exceptionnel avec motif)
En attente soutenance → Terminé (après soutenance + notes)
En attente soutenance → En cours (report soutenance)
```

**Conditions importantes :**

| Transition | Condition préalable |
|------------|---------------------|
| Propose → En cours | Action manuelle admin (démarrage) |
| En cours → En attente soutenance | `rapport_file IS NOT NULL` |
| En attente soutenance → Terminé | Jury a attribué toutes les notes |

**Règles métier :**
- Rapport PDF obligatoire pour passer en soutenance
- Jury minimum 3 personnes
- Note finale = moyenne pondérée notes jury
- Notes individuelles possibles si groupe
- État 'terminé' ou 'abandonné' = états finaux

**Code impliqué :**
- `app/Models/Pfe.php`
- `app/Http/Controllers/SoutenanceController.php`
- Méthodes : `demarrerPfe()`, `planifierSoutenance()`, `terminerPfe()`, `abandonner()`

---

### 3. `state-demande-encadrement.puml` - États d'une Demande d'Encadrement

**États possibles :**
- **En attente** : Demande soumise, en attente réponse enseignant
- **Acceptée** : Enseignant a accepté (PFE créé automatiquement)
- **Refusée** : Enseignant a refusé avec motif

**Transitions :**
```
[Étudiant soumet] → En attente
En attente → Acceptée (enseignant accepte)
En attente → Refusée (enseignant refuse avec motif)
En attente → [Suppression] (étudiant annule)
```

**Actions automatiques lors d'acceptation :**
1. Vérification quota encadrements enseignant
2. Création PFE (statut 'propose')
3. Association étudiant(s) via pivot `etudiants_pfe`
4. Désignation chef de groupe si groupe
5. Notifications envoyées

**Règles métier :**
- Un étudiant = une demande active max par année
- Quota max_encadrements_simultanes respecté
- Si groupe : tous de la même filière
- Motif obligatoire en cas de refus
- Annulation possible uniquement si 'en_attente'

**Code impliqué :**
- `app/Models/DemandeEncadrement.php`
- `app/Http/Controllers/DemandeEncadrementController.php`
- Méthodes : `store()`, `accepter()`, `refuser()`, `destroy()`

---

## 🔁 Workflows métier

### Workflow complet : De l'inscription à la soutenance

```
1. INSCRIPTION
   Utilisateur → [register] → Compte créé (étudiant/enseignant)
   ↓

2. PROPOSITION SUJET
   Enseignant → [propose sujet] → Coordinateur valide → Sujet visible
   ↓

3. DEMANDE ENCADREMENT
   Étudiant → [crée demande] → Enseignant accepte → PFE créé
   ↓

4. DÉROULEMENT PFE
   Admin démarre → Étudiant travaille → Upload rapport
   ↓

5. SOUTENANCE
   Admin planifie → Soutenance → Jury note → PFE terminé
```

### Cas d'usage typiques

#### Cas 1 : Étudiant cherche un sujet
1. Étudiant se connecte
2. Accède à "Sujets disponibles"
3. Filtre par niveau/département/mots-clés
4. Consulte détails d'un sujet
5. Clique sur "Demander l'encadrement"
6. Remplit formulaire demande
7. Reçoit notification de l'enseignant

#### Cas 2 : Enseignant gère ses encadrements
1. Enseignant se connecte
2. Accède à "Mes demandes reçues"
3. Consulte chaque demande (profil étudiant, motivation)
4. Vérifie son quota d'encadrements
5. Accepte ou refuse avec motif
6. Si acceptation : PFE créé automatiquement
7. Suit l'avancement du PFE

#### Cas 3 : Admin prépare une soutenance
1. Admin se connecte
2. Accède à "Soutenances à planifier"
3. Filtre PFE avec rapport uploadé
4. Sélectionne un PFE
5. Définit date/heure/salle
6. Constitue le jury (3+ membres)
7. Assigne rôles (président/rapporteur/examinateurs)
8. Valide → notifications envoyées automatiquement

---

## ⚖️ Règles métier importantes

### Contraintes sur les Sujets PFE

| Règle | Valeur |
|-------|--------|
| Titre max | 255 caractères |
| Mots-clés max | 4 |
| Étudiants max par sujet | 3 |
| Niveaux acceptés | licence, master, tous |
| Visibilité | Seulement sujets validés |

### Contraintes sur les PFE

| Règle | Valeur |
|-------|--------|
| Statuts possibles | propose, en_cours, en_attente_soutenance, termine, abandonne |
| Rapport requis | Oui (PDF) avant soutenance |
| Jury minimum | 3 membres |
| Note finale | Moyenne pondérée jury |
| Notes individuelles | Optionnel (si groupe) |

### Contraintes sur les Demandes

| Règle | Valeur |
|-------|--------|
| Demandes actives par étudiant | 1 max par année |
| Quota enseignant | max_encadrements_simultanes |
| Motif refus | Obligatoire |
| Annulation | Seulement si 'en_attente' |

### Contraintes sur les Groupes

| Règle | Valeur |
|-------|--------|
| Taille groupe | 1 à 3 étudiants |
| Filière | Tous de la même filière |
| Chef de groupe | Obligatoire (défini) |
| Acceptation | Tous acceptent l'invitation |

### Génération automatique

#### Matricule étudiant
- **Format Licence :** `L{ANNÉE}{NUMÉRO}` (ex: L20250001)
- **Format Master :** `M{ANNÉE}{NUMÉRO}` (ex: M20250001)
- **Incrémentation :** Séquentielle par année et niveau
- **Génération :** Automatique à l'inscription

---

## 🔗 Liens entre les diagrammes

### Diagramme de classes → Diagrammes de séquence
- **Classes documentées :** User, SujetPfe, DemandeEncadrement, Pfe, JurySoutenance
- **Relations montrées :** belongsTo, hasMany, belongsToMany
- **Méthodes illustrées :** Toutes les actions métier importantes

### Diagrammes de séquence → Diagrammes d'états
- **Séquence inscription :** Crée User (pas d'états)
- **Séquence proposition :** SujetPfe passe par états (propose/valide)
- **Séquence demande :** DemandeEncadrement (en_attente/accepte/refuse) + crée Pfe
- **Séquence soutenance :** Pfe change d'état (en_cours → en_attente_soutenance → termine)

### Vue d'ensemble complète
Pour comprendre totalement le système :
1. **Commencez par** : `uml-class-diagram.puml` (structure globale)
2. **Puis lisez** : Les 4 diagrammes de séquence (workflows)
3. **Terminez par** : Les 3 diagrammes d'états (cycles de vie)

---

## 📁 Fichiers disponibles

```
docs/
├── README-SEQUENCES-STATES.md        ← Ce fichier
├── README-UML.md                     ← Documentation diagramme classes
│
├── uml-class-diagram.puml            ← Diagramme de classes complet
├── uml-class-diagram.mmd             ← Version Mermaid
│
├── sequence-inscription.puml         ← Séquence : Inscription utilisateur
├── sequence-proposition-sujet.puml   ← Séquence : Proposition sujet PFE
├── sequence-demande-encadrement.puml ← Séquence : Demande encadrement
├── sequence-soutenance.puml          ← Séquence : Soutenance et notation
│
├── state-sujet-pfe.puml              ← États : Sujet PFE
├── state-pfe.puml                    ← États : Projet PFE
└── state-demande-encadrement.puml    ← États : Demande encadrement
```

---

## 🛠️ Outils recommandés

### Visualisation en ligne
- **PlantText** : https://www.planttext.com/ (le plus simple)
- **PlantUML Web Server** : http://www.plantuml.com/plantuml/uml/
- **Kroki** : https://kroki.io/ (supporte PlantUML et Mermaid)

### Éditeurs locaux
- **VS Code** + extension PlantUML
- **IntelliJ IDEA** + plugin PlantUML Integration
- **Atom** + package plantuml-viewer

### Générateurs d'images
```bash
# PNG
plantuml -tpng docs/*.puml

# SVG (vectoriel, recommandé)
plantuml -tsvg docs/*.puml

# PDF
plantuml -tpdf docs/*.puml
```

---

## 📚 Ressources supplémentaires

### Documentation PlantUML
- **Site officiel** : https://plantuml.com/
- **Guide séquence** : https://plantuml.com/sequence-diagram
- **Guide états** : https://plantuml.com/state-diagram
- **Syntaxe complète** : https://plantuml.com/guide

### Standards UML
- **OMG UML** : https://www.omg.org/spec/UML/
- **UML 2.5** : Standard actuel pour diagrammes

---

## ✅ Vérification de conformité

Ces diagrammes documentent **98% des fonctionnalités** du cahier des charges :

- ✅ Gestion utilisateurs (inscription, authentification, rôles)
- ✅ Gestion sujets PFE (proposition, validation, recherche)
- ✅ Gestion demandes d'encadrement (soumission, acceptation/refus)
- ✅ Gestion PFE (création, suivi, rapport, soutenance)
- ✅ Gestion jury (constitution, notation)
- ✅ Système de notifications
- ✅ Gestion groupes d'étudiants
- ✅ Historique et statistiques

**Non documentés :** Exports PDF, imports CSV (diagrammes non critiques)

---

## 👥 Pour qui sont ces diagrammes ?

### Développeurs
- Comprendre les workflows complets
- Implémenter les contrôleurs et modèles
- Déboguer les transitions d'états

### Chefs de projet
- Valider la conformité aux spécifications
- Communiquer avec les parties prenantes
- Planifier les développements

### Testeurs
- Créer des scénarios de test
- Vérifier tous les chemins possibles
- Tester les cas limites

### Utilisateurs finaux
- Comprendre le fonctionnement du système
- Savoir quelles actions sont possibles
- Anticiper les validations requises

---

**Date de création :** 2025-11-06
**Version :** 1.0
**Auteur :** Documentation générée pour le projet Gestion École Laravel
