# 📋 Validation du Cahier des Charges - Gestion PFE

## 📌 Description du Projet

Application de gestion des encadrements de Projets de Fin d'Études permettant aux enseignants de suivre l'historique de leurs encadrements.

---

## 🎯 Règles Métier Fondamentales

### Contraintes Générales

- [ ] Un enseignant peut encadrer un ou plusieurs PFE par année universitaire
- [ ] Un PFE est réalisé par 1 à 3 étudiants maximum
- [ ] Tous les étudiants d'un PFE doivent être de la même filière
- [ ] Les étudiants sont inscrits dans une filière de niveau Licence ou Master

### Caractéristiques d'un PFE

- [ ] Un PFE possède un numéro unique
- [ ] Un PFE possède un sujet
- [ ] Un PFE possède une date de début
- [ ] Un PFE possède une date de fin
- [ ] Un PFE peut avoir jusqu'à 4 mots-clés maximum

---

## 🔄 Interfaçage et Import

### Import des Données Scolarité

- [ ] Interfaçage avec l'application de gestion de la scolarité
- [ ] Import automatique de la liste des étudiants par filière
- [ ] Import des étudiants devant aller en stage

---

## 👥 Fonctionnalités par Acteur

## 1️⃣ Fonctionnalités Enseignants

### 1.1 Gestion des Sujets

- [ ] Publier des propositions de sujets PFE
- [ ] Propositions relatives au profil de l'enseignant (département)
- [ ] Modifier ses propositions de sujets
- [ ] Supprimer ses propositions de sujets (si non affectés)

### 1.2 Gestion des Demandes d'Encadrement

- [ ] Consulter les demandes d'encadrement reçues
- [ ] Accepter une demande d'encadrement
- [ ] Refuser une demande d'encadrement (avec motif)
- [ ] Voir les propositions de sujets faites par les étudiants

### 1.3 Gestion des PFE

- [ ] Consulter la liste de ses PFE en cours
- [ ] Consulter les détails d'un PFE encadré
- [ ] Modifier les informations d'un PFE
- [ ] Terminer un PFE et attribuer une note finale
- [ ] Attribuer des notes individuelles aux étudiants
- [ ] Télécharger le rapport de PFE
- [ ] Télécharger la présentation de PFE

### 1.4 Historique des Encadrements

- [ ] Enregistrer les encadrements effectués pour chaque année
- [ ] Enregistrer le PFE et la liste des étudiants
- [ ] Rechercher dans l'historique par titre
- [ ] Rechercher dans l'historique par nom d'étudiant
- [ ] Rechercher dans l'historique par matricule d'étudiant
- [ ] Rechercher dans l'historique par mots-clés
- [ ] Rechercher dans l'historique par année universitaire
- [ ] Consulter les statistiques d'encadrement

### 1.5 Dashboard Enseignant

- [ ] Voir le nombre total de PFE encadrés
- [ ] Voir le nombre de PFE en cours
- [ ] Voir le nombre de demandes en attente
- [ ] Voir la liste des derniers PFE
- [ ] Voir les notifications récentes

---

## 2️⃣ Fonctionnalités Coordinateurs

### 2.1 Validation et Gestion des Sujets

- [ ] Valider les propositions de sujets soumises par les enseignants
- [ ] Rejeter les propositions de sujets (avec motif)
- [ ] Consulter tous les sujets proposés dans son département
- [ ] Filtrer les sujets par statut (proposé, validé, affecté)

### 2.2 Gestion des Affectations

- [ ] Affecter un étudiant à un enseignant pour encadrement
- [ ] Affecter un groupe d'étudiants à un enseignant
- [ ] Modifier les affectations existantes
- [ ] Consulter toutes les affectations du département

### 2.3 Validation des Demandes Étudiants

- [ ] Valider les demandes d'encadrement des étudiants
- [ ] Valider les choix de sujets des étudiants
- [ ] Valider les propositions de sujets des étudiants

### 2.4 Gestion Administrative

- [ ] Gérer les années universitaires (créer, activer, archiver)
- [ ] Gérer les filières (créer, modifier, désactiver)
- [ ] Gérer les utilisateurs (créer, modifier, désactiver)
- [ ] Importer des listes d'étudiants
- [ ] Importer des listes d'enseignants
- [ ] Exporter les données PFE en CSV
- [ ] Exporter les données étudiants en CSV
- [ ] Exporter les historiques d'encadrement en CSV

### 2.5 Gestion des Soutenances

- [ ] Consulter la liste des soutenances
- [ ] Affecter un jury à une soutenance
- [ ] Modifier la composition d'un jury
- [ ] Planifier les soutenances

### 2.6 Dashboard Coordinateur

- [ ] Voir les statistiques globales du département
- [ ] Voir les sujets en attente de validation
- [ ] Voir les PFE récents
- [ ] Voir les demandes en attente
- [ ] Voir les statistiques par département

---

## 3️⃣ Fonctionnalités Étudiants

### 3.1 Propositions de Sujets

- [ ] Faire une proposition de sujet de stage/PFE
- [ ] Décrire le sujet proposé
- [ ] Indiquer les objectifs du sujet proposé
- [ ] Indiquer les technologies envisagées

### 3.2 Demandes d'Encadrement

- [ ] Faire une demande d'encadrement auprès d'un enseignant
- [ ] Choisir entre sujet existant ou proposition personnelle
- [ ] Joindre une lettre de motivation
- [ ] Consulter le statut de sa demande
- [ ] Annuler une demande en attente
- [ ] Recevoir des notifications sur l'état de la demande

### 3.3 Choix de Sujet

- [ ] Consulter la liste des sujets disponibles
- [ ] Filtrer les sujets par filière
- [ ] Filtrer les sujets par niveau (Licence/Master)
- [ ] Filtrer les sujets par département
- [ ] Rechercher des sujets par mots-clés
- [ ] Voir les détails complets d'un sujet
- [ ] Choisir un sujet proposé par un enseignant

### 3.4 Gestion de Groupe

- [ ] Créer un groupe d'étudiants (2-3 membres)
- [ ] Inviter d'autres étudiants dans son groupe
- [ ] Accepter/refuser une invitation de groupe
- [ ] Quitter un groupe
- [ ] Retirer un membre du groupe (chef de groupe)
- [ ] Consulter les membres de son groupe

### 3.5 Suivi du PFE

- [ ] Consulter les détails de son PFE
- [ ] Voir l'encadrant assigné
- [ ] Voir les membres du groupe
- [ ] Voir les échéances du PFE
- [ ] Consulter sa note individuelle (après soutenance)
- [ ] Consulter la note finale du PFE

### 3.6 Dashboard Étudiant

- [ ] Voir son PFE actuel (si affecté)
- [ ] Voir sa demande d'encadrement en cours
- [ ] Voir les sujets disponibles
- [ ] Voir son groupe
- [ ] Voir les notifications

---

## 4️⃣ Fonctionnalités Administrateur

### 4.1 Gestion Complète des Utilisateurs

- [ ] Créer des comptes utilisateurs (tous rôles)
- [ ] Modifier les informations utilisateurs
- [ ] Désactiver/Activer des comptes
- [ ] Supprimer des comptes (si pas de données liées)
- [ ] Réinitialiser les mots de passe
- [ ] Gérer les rôles (Admin, Coordinateur, Enseignant, Étudiant)

### 4.2 Gestion des Filières

- [ ] Créer des filières
- [ ] Modifier les filières existantes
- [ ] Activer/Désactiver des filières
- [ ] Supprimer des filières (si non utilisées)

### 4.3 Gestion des Années Universitaires

- [ ] Créer une nouvelle année universitaire
- [ ] Activer une année universitaire
- [ ] Archiver une année universitaire
- [ ] Consulter les statistiques par année

### 4.4 Import/Export de Données

- [ ] Importer des étudiants via CSV
- [ ] Importer des enseignants via CSV
- [ ] Télécharger les modèles CSV
- [ ] Consulter l'historique des imports
- [ ] Exporter tous les PFE
- [ ] Exporter tous les étudiants
- [ ] Exporter tous les encadrements

### 4.5 Supervision Globale

- [ ] Accès au dashboard administrateur complet
- [ ] Voir les statistiques globales de l'application
- [ ] Voir les statistiques par département
- [ ] Consulter tous les PFE de toutes les filières
- [ ] Consulter tous les sujets proposés
- [ ] Consulter toutes les demandes d'encadrement

---

## 🔍 Fonctionnalités de Recherche

### Recherche Générale

- [ ] Rechercher des PFE par titre
- [ ] Rechercher des PFE par mots-clés
- [ ] Rechercher des PFE par année universitaire
- [ ] Rechercher des PFE par nom d'étudiant
- [ ] Rechercher des PFE par matricule d'étudiant
- [ ] Rechercher des PFE par enseignant
- [ ] Rechercher des sujets par titre
- [ ] Rechercher des sujets par mots-clés

### Historique de Recherche

- [ ] Consulter l'historique des recherches effectuées
- [ ] Filtrer l'historique par année universitaire
- [ ] Filtrer l'historique par enseignant
- [ ] Exporter les résultats de recherche

### Statistiques de Recherche

- [ ] Voir les statistiques de PFE par année
- [ ] Voir les statistiques de PFE par filière
- [ ] Voir les statistiques d'encadrement par enseignant
- [ ] Voir les mots-clés les plus utilisés

---

## 🔐 Fonctionnalités Authentification et Profil

### Authentification

- [ ] Connexion avec email et mot de passe
- [ ] Déconnexion
- [ ] Inscription (si activée)
- [ ] Mot de passe sécurisé (hashé)

### Gestion du Profil

- [ ] Consulter son profil
- [ ] Modifier ses informations personnelles
- [ ] Modifier son mot de passe
- [ ] Voir son rôle et ses permissions
- [ ] Voir sa filière (étudiants)
- [ ] Voir son département (enseignants)

---

## 🔔 Système de Notifications

### Types de Notifications

- [ ] Notification de nouvelle demande d'encadrement
- [ ] Notification d'acceptation de demande
- [ ] Notification de refus de demande
- [ ] Notification de validation de sujet
- [ ] Notification de rejet de sujet
- [ ] Notification d'invitation à un groupe
- [ ] Notification de création de PFE
- [ ] Notification d'affectation de jury

### Gestion des Notifications

- [ ] Consulter la liste des notifications
- [ ] Marquer une notification comme lue
- [ ] Marquer toutes les notifications comme lues
- [ ] Badge compteur de notifications non lues

---

## 📊 Fonctionnalités de Reporting

### Exports CSV

- [ ] Export des PFE avec filtres
- [ ] Export des étudiants avec filtres
- [ ] Export des encadrements avec filtres
- [ ] Nom de fichier avec timestamp

### Statistiques

- [ ] Statistiques globales (dashboard)
- [ ] Statistiques par département
- [ ] Statistiques par année universitaire
- [ ] Statistiques par filière
- [ ] Statistiques par enseignant
- [ ] Graphiques et visualisations

---

## 🎨 Interface Utilisateur

### Navigation

- [ ] Menu de navigation adapté au rôle
- [ ] Breadcrumb pour la navigation
- [ ] Liens rapides vers les fonctionnalités principales
- [ ] Recherche globale

### Design

- [ ] Interface responsive (mobile, tablette, desktop)
- [ ] Design cohérent avec Bootstrap 5
- [ ] Icônes Font Awesome
- [ ] Messages de succès/erreur clairs
- [ ] Modales de confirmation pour actions critiques

### Accessibilité

- [ ] Navigation au clavier
- [ ] Labels accessibles sur les formulaires
- [ ] Messages d'erreur explicites
- [ ] Pagination sur les listes longues

---

## 🔒 Sécurité et Autorisations

### Middleware et Protections

- [ ] Middleware d'authentification
- [ ] Middleware de vérification de rôle
- [ ] Middleware de vérification de compte actif
- [ ] Middleware de vérification d'année active
- [ ] Protection CSRF sur tous les formulaires

### Policies d'Autorisation

- [ ] Policy pour les Sujets PFE
- [ ] Policy pour les PFE
- [ ] Policy pour les Demandes d'Encadrement
- [ ] Policy pour les Utilisateurs
- [ ] Policy pour les Filières
- [ ] Policy pour les Années Universitaires
- [ ] Policy pour les Notifications

### Validations

- [ ] Validation des formulaires côté serveur
- [ ] Validation des formulaires côté client (JavaScript)
- [ ] Messages d'erreur de validation clairs
- [ ] Protection contre l'injection SQL (Eloquent ORM)
- [ ] Sanitization des entrées utilisateur

---

## 📝 Fonctionnalités Supplémentaires Implémentées

### Gestion des Mots-Clés

- [ ] Ajout de mots-clés à un sujet (max 4)
- [ ] Recherche par mots-clés
- [ ] Affichage des mots-clés populaires
- [ ] Auto-complétion des mots-clés

### Gestion des Documents

- [ ] Upload du rapport de PFE
- [ ] Upload de la présentation de PFE
- [ ] Téléchargement des documents
- [ ] Stockage sécurisé des fichiers

### Validation de Filière

- [ ] Tous les étudiants d'un PFE doivent être de la même filière
- [ ] Validation lors de l'ajout d'étudiants
- [ ] Validation lors de la création de groupes
- [ ] Messages d'erreur explicites

---

## 📈 Progression Globale

### Récapitulatif

- **Total de fonctionnalités** : ~150+
- **Fonctionnalités critiques** : ~80
- **Fonctionnalités secondaires** : ~70

---

## 📌 Notes de Validation

### Points d'Attention

1. **Import Scolarité** : L'interfaçage doit être adapté selon le système de scolarité existant
2. **Performances** : Optimisation des requêtes pour les grandes listes
3. **Sécurité** : Toutes les actions critiques doivent être protégées
4. **Tests** : Tests fonctionnels recommandés avant mise en production

### Prochaines Étapes

1. [ ] Tests fonctionnels complets
2. [ ] Validation avec des données réelles
3. [ ] Formation des utilisateurs
4. [ ] Déploiement en production

---

## 📅 Date de Validation

**Date** : _______________

**Validé par** : _______________

**Signature** : _______________

---
*Fin du Cahier des Charges*
