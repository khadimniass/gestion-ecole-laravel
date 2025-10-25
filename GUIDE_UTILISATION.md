# 📖 Guide d'Utilisation - Application Gestion PFE

## 🎯 Objectif du Guide

Ce guide vous permet de tester **toutes les fonctionnalités** de l'application de gestion des PFE, point par point selon le cahier des charges.

---

## 🔑 Comptes de Test par Défaut

Après avoir exécuté les seeders (`php artisan db:seed`), vous aurez accès à ces comptes :

### 👤 Administrateur
- **Email** : `admin@gestion-pfe.test`
- **Mot de passe** : `password`
- **Rôle** : Administrateur système

### 👨‍🏫 Coordinateur
- **Email** : `coord.info@gestion-pfe.test`
- **Mot de passe** : `password`
- **Rôle** : Coordinateur
- **Département** : Informatique

### 👨‍🏫 Enseignants
- **Email** : `prof.dupont@gestion-pfe.test`
- **Mot de passe** : `password`
- **Département** : Informatique
- **Spécialité** : Intelligence Artificielle

- **Email** : `prof.martin@gestion-pfe.test`
- **Mot de passe** : `password`
- **Département** : Informatique
- **Spécialité** : Développement Web

### 👨‍🎓 Étudiants
- **Email** : `khadim.niass@etudiant.test`
- **Mot de passe** : `password`
- **Matricule** : `L2023001`
- **Filière** : Licence Informatique

- **Email** : `aissatou.ba@etudiant.test`
- **Mot de passe** : `password`
- **Matricule** : `L2023002`
- **Filière** : Licence Informatique

- **Email** : `moussa.diallo@etudiant.test`
- **Mot de passe** : `password`
- **Matricule** : `M2023001`
- **Filière** : Master Informatique

---

## 🚀 Démarrage de l'Application

### Prérequis
```bash
# Installer les dépendances
composer install

# Copier le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer la base de données
php artisan migrate

# Peupler avec les données de test
php artisan db:seed

# Créer le lien symbolique pour le stockage
php artisan storage:link

# Lancer le serveur
php artisan serve
```

### Accès à l'application
- **URL** : http://127.0.0.1:8000
- **Page de connexion** : http://127.0.0.1:8000/login

---

## 📋 Tests des Fonctionnalités

## 1️⃣ TESTS ADMINISTRATEUR

### Se connecter en tant qu'Admin
1. Aller sur http://127.0.0.1:8000/login
2. Email : `admin@gestion-pfe.test`
3. Mot de passe : `password`
4. Cliquer sur "Se connecter"
5. ✅ Vérifier : Redirection vers le dashboard admin

---

### 1.1 Gestion des Utilisateurs

#### ✅ Créer un utilisateur
1. Menu : **Admin** → **Gestion des utilisateurs**
2. Cliquer sur **"+ Nouvel utilisateur"**
3. Remplir le formulaire :
   - Nom : `Test Enseignant`
   - Email : `test.prof@gestion-pfe.test`
   - Rôle : `Enseignant`
   - Département : `Informatique`
   - Mot de passe : `password`
4. Soumettre
5. ✅ Vérifier : Message de succès + utilisateur dans la liste

#### ✅ Modifier un utilisateur
1. Dans la liste, cliquer sur l'icône **✏️ Modifier**
2. Changer le nom ou l'email
3. Enregistrer
4. ✅ Vérifier : Modifications sauvegardées

#### ✅ Désactiver un utilisateur
1. Dans la liste, trouver un utilisateur
2. Cliquer sur **"Désactiver"** ou modifier et cocher "Compte inactif"
3. ✅ Vérifier : L'utilisateur ne peut plus se connecter

#### ✅ Voir les détails d'un utilisateur
1. Cliquer sur l'icône **👁️ Voir**
2. ✅ Vérifier : Affichage des informations complètes

---

### 1.2 Gestion des Filières

#### ✅ Créer une filière
1. Menu : **Admin** → **Filières**
2. Cliquer sur **"+ Nouvelle filière"**
3. Remplir :
   - Code : `GI`
   - Nom : `Génie Informatique`
   - Département : `Informatique`
   - Niveau : `Licence`
4. Soumettre
5. ✅ Vérifier : Filière créée avec succès

#### ✅ Modifier une filière
1. Cliquer sur **✏️ Modifier**
2. Changer le nom
3. Enregistrer
4. ✅ Vérifier : Modification effective

#### ✅ Désactiver une filière
1. Modifier la filière
2. Décocher "Filière active"
3. Enregistrer
4. ✅ Vérifier : Filière grisée/masquée

---

### 1.3 Gestion des Années Universitaires

#### ✅ Créer une année universitaire
1. Menu : **Admin** → **Années universitaires**
2. Cliquer sur **"+ Nouvelle année"**
3. Remplir :
   - Année : `2024-2025`
   - Date début : `01/09/2024`
   - Date fin : `30/06/2025`
4. ✅ Vérifier : Année créée

#### ✅ Activer une année universitaire
1. Trouver l'année dans la liste
2. Cliquer sur **"Activer"**
3. ✅ Vérifier : Badge "Active" affiché
4. ✅ Vérifier : Une seule année active à la fois

#### ✅ Archiver une année
1. Sur une année terminée
2. Cliquer sur **"Archiver"**
3. ✅ Vérifier : Année archivée, non modifiable

---

### 1.4 Import de Données

#### ✅ Importer des étudiants
1. Menu : **Admin** → **Import de données**
2. Cliquer sur **"Importer des étudiants"**
3. Télécharger le modèle CSV
4. Remplir le fichier avec des données (voir `storage/samples/etudiants_import.csv`)
5. Uploader le fichier
6. ✅ Vérifier : Étudiants importés avec succès
7. ✅ Vérifier : Comptes créés avec mot de passe par défaut

**Format du CSV** :
```csv
nom,prenom,email,matricule,filiere_code,niveau_etude,telephone
Sow,Fatou,fatou.sow@etudiant.test,L2024010,INFO,L3,771234567
```

#### ✅ Importer des enseignants
1. Cliquer sur **"Importer des enseignants"**
2. Télécharger le modèle
3. Remplir (voir `storage/samples/enseignants_import.csv`)
4. Uploader
5. ✅ Vérifier : Enseignants importés

**Format du CSV** :
```csv
nom,prenom,email,departement,specialite,telephone
Diop,Mamadou,mamadou.diop@prof.test,Informatique,Réseaux,775551234
```

---

### 1.5 Export de Données

#### ✅ Exporter les PFE
1. Menu : **Admin** → **Export**
2. Cliquer sur **"Exporter les PFE"**
3. Sélectionner les filtres (année, statut)
4. Cliquer sur **"Exporter CSV"**
5. ✅ Vérifier : Fichier CSV téléchargé
6. ✅ Vérifier : Données correctes dans le fichier

#### ✅ Exporter les étudiants
1. Cliquer sur **"Exporter les étudiants"**
2. Filtrer par filière si besoin
3. Exporter
4. ✅ Vérifier : CSV avec tous les étudiants

#### ✅ Exporter les encadrements
1. Cliquer sur **"Exporter les encadrements"**
2. Filtrer par année
3. Exporter
4. ✅ Vérifier : Historique complet des encadrements

---

### 1.6 Gestion des Soutenances

#### ✅ Consulter les soutenances
1. Menu : **Admin** → **Soutenances**
2. ✅ Vérifier : Liste des PFE prêts pour soutenance

#### ✅ Affecter un jury
1. Cliquer sur **"Éditer jury"** sur un PFE
2. Sélectionner 3 enseignants :
   - Président
   - Rapporteur
   - Examinateur
3. Enregistrer
4. ✅ Vérifier : Jury affecté
5. ✅ Vérifier : Notifications envoyées aux membres du jury

---

## 2️⃣ TESTS COORDINATEUR

### Se connecter en tant que Coordinateur
1. Se déconnecter (si connecté en admin)
2. Email : `coord.info@gestion-pfe.test`
3. Mot de passe : `password`
4. ✅ Vérifier : Dashboard coordinateur

---

### 2.1 Validation des Sujets

#### ✅ Consulter les sujets en attente
1. Menu : **Sujets** → **Validation des sujets**
2. ✅ Vérifier : Liste des sujets avec statut "proposé"

#### ✅ Valider un sujet
1. Cliquer sur l'icône **👁️** pour voir les détails
2. Vérifier le contenu du sujet
3. Cliquer sur **✓ Valider**
4. Confirmer
5. ✅ Vérifier : Sujet validé
6. ✅ Vérifier : Notification envoyée à l'enseignant proposant

#### ✅ Rejeter un sujet
1. Sur un sujet en attente
2. Cliquer sur **✗ Rejeter**
3. Saisir le motif : "Sujet trop vague, manque de précision technique"
4. Confirmer
5. ✅ Vérifier : Sujet rejeté
6. ✅ Vérifier : Notification avec motif envoyée

---

### 2.2 Affectation d'Étudiants

#### ✅ Affecter un étudiant à un enseignant
1. Menu : **Gestion** → **Affectations**
2. Cliquer sur **"+ Nouvelle affectation"**
3. Sélectionner :
   - Étudiant : Khadim Niass
   - Enseignant : Prof. Dupont
   - Sujet : (choisir dans la liste)
4. Valider
5. ✅ Vérifier : PFE créé automatiquement
6. ✅ Vérifier : Notifications envoyées

#### ✅ Affecter un groupe d'étudiants
1. Même processus
2. Sélectionner plusieurs étudiants
3. ✅ Vérifier : Tous de la même filière (validation automatique)
4. ✅ Vérifier : PFE créé avec tous les membres

---

### 2.3 Statistiques du Département

#### ✅ Consulter le dashboard
1. Menu : **Dashboard**
2. ✅ Vérifier :
   - Nombre de PFE en cours
   - Nombre de sujets en attente
   - Nombre de demandes d'encadrement
   - Statistiques par département
   - Graphiques de répartition

---

## 3️⃣ TESTS ENSEIGNANT

### Se connecter en tant qu'Enseignant
1. Email : `prof.dupont@gestion-pfe.test`
2. Mot de passe : `password`
3. ✅ Vérifier : Dashboard enseignant

---

### 3.1 Proposition de Sujets

#### ✅ Créer un sujet
1. Menu : **Sujets** → **Mes sujets** → **"+ Nouveau sujet"**
2. Remplir le formulaire :
   - Titre : `Développement d'une application mobile de gestion des tâches`
   - Description : `Créer une application React Native pour gérer les tâches quotidiennes...`
   - Objectifs : `Maîtriser React Native, API REST, Base de données locale`
   - Technologies : `React Native, Node.js, MongoDB`
   - Filière : `Licence Informatique`
   - Niveau requis : `Licence`
   - Nombre d'étudiants : `2`
   - Mots-clés : `mobile`, `react-native`, `api`, `mongodb`
3. Soumettre
4. ✅ Vérifier : Sujet créé avec statut "proposé"
5. ✅ Vérifier : Notification envoyée au coordinateur

#### ✅ Modifier son sujet (avant validation)
1. Dans **Mes sujets**, cliquer sur **✏️ Modifier**
2. Changer la description
3. Enregistrer
4. ✅ Vérifier : Modifications sauvegardées

#### ✅ Supprimer son sujet (non affecté)
1. Sur un sujet non validé/non affecté
2. Cliquer sur **🗑️ Supprimer**
3. Confirmer
4. ✅ Vérifier : Sujet supprimé

---

### 3.2 Gestion des Demandes d'Encadrement

#### ✅ Consulter les demandes reçues
1. Menu : **Demandes d'encadrement**
2. ✅ Vérifier : Liste des demandes avec :
   - Nom de l'étudiant
   - Sujet proposé/choisi
   - Date de la demande
   - Statut (en attente, accepté, refusé)

#### ✅ Voir les détails d'une demande
1. Cliquer sur **👁️ Voir**
2. ✅ Vérifier : Affichage de :
   - Informations de l'étudiant
   - Lettre de motivation
   - Sujet proposé ou choisi
   - Groupe (si applicable)

#### ✅ Accepter une demande
1. Sur une demande en attente
2. Cliquer sur **✓ Accepter**
3. Confirmer
4. ✅ Vérifier :
   - PFE créé automatiquement
   - Étudiant affecté au PFE
   - Sujet marqué comme "affecté"
   - Notification envoyée à l'étudiant

#### ✅ Refuser une demande
1. Sur une demande en attente
2. Cliquer sur **✗ Refuser**
3. Saisir le motif : "Charge de travail complète pour cette année"
4. Confirmer
5. ✅ Vérifier :
   - Demande refusée
   - Notification avec motif envoyée à l'étudiant

---

### 3.3 Gestion des PFE Encadrés

#### ✅ Consulter mes PFE
1. Menu : **Mes PFE**
2. ✅ Vérifier : Liste de tous les PFE encadrés (en cours + terminés)
3. ✅ Vérifier : Filtres par statut et année

#### ✅ Voir les détails d'un PFE
1. Cliquer sur un PFE
2. ✅ Vérifier : Affichage de :
   - Numéro PFE
   - Titre du sujet
   - Liste des étudiants
   - Dates (début, fin prévue)
   - Statut
   - Documents (rapport, présentation)
   - Notes (si terminé)

#### ✅ Modifier un PFE
1. Cliquer sur **✏️ Modifier**
2. Changer les dates ou observations
3. Enregistrer
4. ✅ Vérifier : Modifications sauvegardées

#### ✅ Terminer un PFE et attribuer les notes
1. Sur un PFE en cours
2. Cliquer sur **"Terminer et noter"**
3. Remplir :
   - Note finale du PFE : `15.5/20`
   - Notes individuelles :
     - Étudiant 1 : `16/20`
     - Étudiant 2 : `15/20`
   - Appréciations
4. Soumettre
5. ✅ Vérifier :
   - PFE marqué comme "terminé"
   - Notes enregistrées
   - Historique mis à jour

---

### 3.4 Historique des Encadrements

#### ✅ Consulter l'historique complet
1. Menu : **Historique**
2. ✅ Vérifier : Tous les PFE passés et actuels

#### ✅ Rechercher par titre
1. Dans la barre de recherche, taper : `application mobile`
2. ✅ Vérifier : Résultats filtrés par titre

#### ✅ Rechercher par nom d'étudiant
1. Taper : `Niass`
2. ✅ Vérifier : PFE avec cet étudiant

#### ✅ Rechercher par matricule
1. Taper : `L2023001`
2. ✅ Vérifier : PFE de l'étudiant

#### ✅ Rechercher par mots-clés
1. Taper : `mobile`
2. ✅ Vérifier : PFE ayant ce mot-clé

#### ✅ Filtrer par année universitaire
1. Sélectionner : `2023-2024`
2. ✅ Vérifier : Seulement les PFE de cette année

#### ✅ Voir les statistiques
1. Cliquer sur **"Statistiques"**
2. ✅ Vérifier :
   - Nombre total d'encadrements
   - Répartition par année
   - Répartition par filière
   - Mots-clés populaires

---

## 4️⃣ TESTS ÉTUDIANT

### Se connecter en tant qu'Étudiant
1. Email : `khadim.niass@etudiant.test`
2. Mot de passe : `password`
3. ✅ Vérifier : Dashboard étudiant

---

### 4.1 Consultation des Sujets Disponibles

#### ✅ Voir la liste des sujets
1. Menu : **Sujets disponibles**
2. ✅ Vérifier : Seulement les sujets validés et visibles
3. ✅ Vérifier : Filtres par département, niveau, filière

#### ✅ Rechercher un sujet
1. Dans la barre de recherche : `web`
2. ✅ Vérifier : Sujets contenant "web"

#### ✅ Filtrer par mots-clés
1. Cliquer sur un mot-clé populaire
2. ✅ Vérifier : Sujets avec ce mot-clé

#### ✅ Voir les détails d'un sujet
1. Cliquer sur un sujet
2. ✅ Vérifier :
   - Titre complet
   - Description détaillée
   - Objectifs
   - Technologies
   - Enseignant proposant
   - Nombre d'étudiants requis

---

### 4.2 Proposition de Sujet Personnel

#### ✅ Proposer son propre sujet
1. Menu : **Demandes** → **Nouvelle demande**
2. Choisir : **"Proposer un nouveau sujet"**
3. Remplir :
   - Titre : `Système de recommandation de films basé sur l'IA`
   - Description : `Créer un système utilisant le machine learning pour recommander des films...`
   - Technologies : `Python, TensorFlow, React`
4. Sélectionner un enseignant : `Prof. Dupont`
5. Lettre de motivation : `Je suis passionné par l'IA et j'ai déjà suivi des cours en ligne...`
6. Soumettre
7. ✅ Vérifier :
   - Demande créée
   - Statut "en attente"
   - Notification envoyée à l'enseignant

---

### 4.3 Demande d'Encadrement

#### ✅ Demander l'encadrement pour un sujet existant
1. **Nouvelle demande**
2. Choisir : **"Choisir un sujet existant"**
3. Sélectionner un sujet dans la liste
4. Sélectionner l'enseignant
5. Lettre de motivation
6. Soumettre
7. ✅ Vérifier : Demande envoyée

#### ✅ Consulter le statut de sa demande
1. Menu : **Mes demandes**
2. ✅ Vérifier :
   - Statut (en attente / accepté / refusé)
   - Date d'envoi
   - Enseignant contacté

#### ✅ Annuler une demande en attente
1. Sur une demande "en attente"
2. Cliquer sur **🗑️ Annuler**
3. Confirmer
4. ✅ Vérifier : Demande supprimée

---

### 4.4 Gestion de Groupe

#### ✅ Créer un groupe
1. Menu : **Mon groupe** → **"Créer un groupe"**
2. Nommer le groupe : `Groupe PFE Mobile 2024`
3. ✅ Vérifier : Groupe créé, vous êtes le chef

#### ✅ Inviter des membres
1. Dans **Mon groupe**
2. Cliquer sur **"Inviter un membre"**
3. Sélectionner un étudiant : `Aissatou Ba`
4. ✅ Vérifier :
   - Seulement les étudiants de la même filière sont listés
   - Invitation envoyée
   - Notification reçue par l'étudiant invité

**Test de validation de filière** :
1. Essayer d'inviter un étudiant d'une autre filière
2. ✅ Vérifier : Message d'erreur "Même filière requise"

#### ✅ Accepter une invitation (2ème compte)
1. Se connecter avec `aissatou.ba@etudiant.test`
2. Voir la notification d'invitation
3. Cliquer sur **"Accepter"**
4. ✅ Vérifier :
   - Membre ajouté au groupe
   - Notification envoyée au chef de groupe

#### ✅ Refuser une invitation
1. Sur une invitation reçue
2. Cliquer sur **"Refuser"**
3. ✅ Vérifier : Invitation rejetée

#### ✅ Retirer un membre (chef de groupe)
1. Se reconnecter en tant que chef
2. Dans la liste des membres
3. Cliquer sur **🗑️ Retirer**
4. Confirmer
5. ✅ Vérifier : Membre retiré

#### ✅ Quitter un groupe (membre)
1. En tant que membre (pas chef)
2. Cliquer sur **"Quitter le groupe"**
3. Confirmer
4. ✅ Vérifier : Vous n'êtes plus dans le groupe

---

### 4.5 Suivi de PFE

#### ✅ Voir son PFE actuel
1. Menu : **Mon PFE**
2. ✅ Vérifier :
   - Informations complètes du PFE
   - Encadrant
   - Membres du groupe
   - Dates importantes
   - Statut

#### ✅ Voir sa note (après soutenance)
1. Sur un PFE terminé
2. ✅ Vérifier :
   - Note finale du PFE
   - Note individuelle
   - Appréciation de l'encadrant

---

## 5️⃣ TESTS RECHERCHE ET HISTORIQUE

### Tests de Recherche Globale

#### ✅ Recherche par titre de PFE
1. Menu : **Recherche**
2. Type : **"PFE"**
3. Saisir : `application`
4. ✅ Vérifier : Liste des PFE avec "application" dans le titre

#### ✅ Recherche par mots-clés
1. Type : **"Par mots-clés"**
2. Saisir : `web mobile`
3. ✅ Vérifier : PFE ayant ces mots-clés

#### ✅ Recherche par étudiant
1. Type : **"Par étudiant"**
2. Nom : `Niass`
3. ✅ Vérifier : Tous les PFE de cet étudiant

#### ✅ Recherche par matricule
1. Saisir : `L2023001`
2. ✅ Vérifier : PFE de l'étudiant

#### ✅ Filtrer par année
1. Année : `2023-2024`
2. ✅ Vérifier : Résultats de cette année uniquement

---

### Tests de l'Historique

#### ✅ Consulter l'historique global
1. Menu : **Historique**
2. ✅ Vérifier : Liste complète des PFE terminés

#### ✅ Filtrer l'historique par enseignant
1. Enseignant : `Prof. Dupont`
2. ✅ Vérifier : Ses encadrements uniquement

#### ✅ Filtrer par année universitaire
1. Année : `2022-2023`
2. ✅ Vérifier : PFE de cette année

---

### Tests des Statistiques

#### ✅ Voir les statistiques globales
1. Menu : **Statistiques**
2. ✅ Vérifier :
   - Total de PFE par année
   - Répartition par filière
   - Nombre d'encadrements par enseignant
   - Graphiques de tendances

#### ✅ Mots-clés les plus utilisés
1. Section **"Mots-clés populaires"**
2. ✅ Vérifier :
   - Liste des mots-clés
   - Nombre d'utilisations
   - Visualisation en nuage

---

## 6️⃣ TESTS SYSTÈME DE NOTIFICATIONS

### Tests des Notifications

#### ✅ Recevoir une notification
1. Effectuer une action qui génère une notification (ex: demande d'encadrement)
2. ✅ Vérifier : Badge rouge sur l'icône de notification

#### ✅ Consulter les notifications
1. Cliquer sur l'icône 🔔
2. ✅ Vérifier : Liste des notifications récentes

#### ✅ Marquer comme lue
1. Cliquer sur une notification
2. ✅ Vérifier :
   - Notification marquée comme lue
   - Badge mis à jour

#### ✅ Marquer toutes comme lues
1. Cliquer sur **"Marquer tout comme lu"**
2. ✅ Vérifier : Toutes les notifications marquées

---

## 7️⃣ TESTS PROFIL UTILISATEUR

### Tests du Profil

#### ✅ Consulter son profil
1. Menu utilisateur → **"Mon profil"**
2. ✅ Vérifier : Toutes les informations affichées

#### ✅ Modifier ses informations
1. Cliquer sur **"Modifier"**
2. Changer :
   - Nom
   - Téléphone
3. Enregistrer
4. ✅ Vérifier : Modifications sauvegardées

#### ✅ Changer son mot de passe
1. Section **"Changer le mot de passe"**
2. Saisir :
   - Ancien mot de passe : `password`
   - Nouveau : `nouveau123`
   - Confirmation : `nouveau123`
3. Enregistrer
4. ✅ Vérifier :
   - Mot de passe changé
   - Peut se reconnecter avec le nouveau

---

## 8️⃣ TESTS DE SÉCURITÉ

### Tests d'Accès et Permissions

#### ✅ Étudiant ne peut pas accéder à l'admin
1. Connecté en tant qu'étudiant
2. Tenter d'accéder : http://127.0.0.1:8000/admin/users
3. ✅ Vérifier : Erreur 403 ou redirection

#### ✅ Enseignant ne peut modifier que ses propres sujets
1. Connecté en enseignant
2. Tenter de modifier le sujet d'un autre
3. ✅ Vérifier : Accès refusé

#### ✅ Protection CSRF
1. Inspecter un formulaire
2. ✅ Vérifier : Présence du token CSRF caché

#### ✅ Validation des filières
1. En tant qu'étudiant, créer un groupe
2. Inviter un étudiant d'une autre filière
3. ✅ Vérifier : Erreur "Même filière requise"

---

## 9️⃣ TESTS D'INTERFACE

### Tests Responsive

#### ✅ Vue mobile
1. Ouvrir DevTools (F12)
2. Activer le mode responsive
3. Tester sur iPhone, iPad
4. ✅ Vérifier : Interface adaptée

#### ✅ Navigation au clavier
1. Utiliser Tab pour naviguer
2. ✅ Vérifier : Ordre logique de navigation

#### ✅ Messages de succès/erreur
1. Effectuer une action réussie
2. ✅ Vérifier : Message vert en haut
3. Faire une erreur (ex: formulaire incomplet)
4. ✅ Vérifier : Message rouge avec détails

---

## 🔟 TESTS DE PERFORMANCE

### Tests de Chargement

#### ✅ Liste avec pagination
1. Aller sur une page avec beaucoup de données
2. ✅ Vérifier :
   - Chargement < 2 secondes
   - Pagination fonctionnelle
   - Navigation entre pages fluide

#### ✅ Recherche rapide
1. Effectuer une recherche
2. ✅ Vérifier : Résultats affichés rapidement

---

## 📊 Checklist Finale de Validation

### Avant de livrer au client

- [ ] Tous les comptes de test fonctionnent
- [ ] Toutes les fonctionnalités admin testées
- [ ] Toutes les fonctionnalités coordinateur testées
- [ ] Toutes les fonctionnalités enseignant testées
- [ ] Toutes les fonctionnalités étudiant testées
- [ ] Recherche et historique testés
- [ ] Notifications fonctionnent
- [ ] Imports CSV fonctionnent
- [ ] Exports CSV fonctionnent
- [ ] Sécurité vérifiée
- [ ] Interface responsive
- [ ] Aucune erreur dans les logs
- [ ] Base de données peuplée avec données de démo

---

## 🆘 Dépannage

### Problèmes Courants

**Erreur 500 - Internal Server Error**
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vérifier les permissions
chmod -R 775 storage bootstrap/cache
```

**Erreur "Class not found"**
```bash
# Régénérer l'autoload
composer dump-autoload
```

**Erreur de base de données**
```bash
# Vérifier .env
cat .env | grep DB_

# Recréer la base
php artisan migrate:fresh --seed
```

**Images/fichiers ne s'affichent pas**
```bash
# Recréer le lien symbolique
php artisan storage:link
```

---

## 📞 Support

Pour toute question ou problème :
- 📧 Email : support@gestion-pfe.test
- 📖 Documentation : `/docs`
- 🐛 Bugs : GitHub Issues

---

> 🤖 Guide généré par Claude Code
>
> Version : 1.0
> Date : 2024
