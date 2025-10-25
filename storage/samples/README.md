# 📁 Fichiers d'Exemple pour Import

Ce dossier contient des fichiers CSV d'exemple pour tester les fonctionnalités d'import de l'application.

## 📋 Fichiers Disponibles

### 1. `etudiants_import_exemple.csv`
Fichier d'exemple pour importer des étudiants dans l'application.

**Colonnes requises** :
- `nom` : Nom de famille de l'étudiant
- `prenom` : Prénom de l'étudiant
- `email` : Adresse email (doit être unique)
- `matricule` : Matricule de l'étudiant (doit être unique)
- `filiere_code` : Code de la filière (ex: INFO, MATH)
- `niveau_etude` : Niveau (L1, L2, L3, M1, M2)
- `telephone` : Numéro de téléphone (optionnel)

**Exemple** :
```csv
nom,prenom,email,matricule,filiere_code,niveau_etude,telephone
Sow,Fatou,fatou.sow@etudiant.test,L2024010,INFO,L3,771234567
```

**Notes importantes** :
- Le mot de passe par défaut sera : `password`
- La filière doit exister dans la base de données
- Les emails et matricules doivent être uniques

---

### 2. `enseignants_import_exemple.csv`
Fichier d'exemple pour importer des enseignants.

**Colonnes requises** :
- `nom` : Nom de famille
- `prenom` : Prénom
- `email` : Adresse email (doit être unique)
- `departement` : Département (Informatique, Mathématiques, etc.)
- `specialite` : Spécialité de l'enseignant
- `telephone` : Numéro de téléphone (optionnel)

**Exemple** :
```csv
nom,prenom,email,departement,specialite,telephone
Diop,Mamadou,mamadou.diop@prof.test,Informatique,Réseaux et Sécurité,775551234
```

**Notes importantes** :
- Le mot de passe par défaut sera : `password`
- Le rôle sera automatiquement : `enseignant`
- Le compte sera actif par défaut

---

## 🚀 Comment Utiliser ces Fichiers

### Via l'Interface Web

1. **Se connecter en tant qu'Admin ou Coordinateur**
   ```
   Email : admin@gestion-pfe.test
   Mot de passe : password
   ```

2. **Aller dans le menu Import**
   - Admin → Import de données

3. **Importer des étudiants**
   - Cliquer sur "Importer des étudiants"
   - Sélectionner `etudiants_import_exemple.csv`
   - Cliquer sur "Importer"

4. **Importer des enseignants**
   - Cliquer sur "Importer des enseignants"
   - Sélectionner `enseignants_import_exemple.csv`
   - Cliquer sur "Importer"

---

## 📝 Créer Votre Propre Fichier CSV

### Pour Excel / LibreOffice Calc

1. Ouvrir un nouveau tableur
2. Créer les en-têtes de colonnes (première ligne)
3. Remplir les données
4. Enregistrer en format **CSV (UTF-8)**
   - Excel : "Enregistrer sous" → CSV UTF-8
   - LibreOffice : "Enregistrer sous" → Texte CSV (.csv)

### Pour Google Sheets

1. Créer une nouvelle feuille
2. Remplir les données avec les en-têtes
3. Fichier → Télécharger → Valeurs séparées par des virgules (.csv)

### Format Important

⚠️ **Attention** : Le fichier CSV doit respecter ces règles :
- Encodage : **UTF-8**
- Séparateur : **virgule (,)**
- Première ligne : **en-têtes des colonnes**
- Pas de ligne vide
- Pas de caractères spéciaux dans les noms de colonnes

---

## 🔍 Vérification Avant Import

### Checklist

- [ ] Le fichier est au format CSV
- [ ] L'encodage est UTF-8
- [ ] Les en-têtes correspondent exactement aux noms requis
- [ ] Tous les champs obligatoires sont remplis
- [ ] Les emails sont uniques
- [ ] Les matricules sont uniques (pour étudiants)
- [ ] Les codes de filière existent dans la base de données

### Tester avec un Petit Fichier

Avant d'importer beaucoup de données :
1. Créer un fichier avec 2-3 lignes seulement
2. Tester l'import
3. Vérifier que les données sont correctes
4. Ensuite importer le fichier complet

---

## ⚠️ Gestion des Erreurs

### Erreurs Communes

**"Email déjà utilisé"**
- Vérifier que l'email n'existe pas déjà dans la base
- Changer l'email dans le CSV

**"Filière introuvable"**
- Vérifier le code de filière (ex: INFO, MATH)
- Créer la filière dans Admin → Filières

**"Format de fichier incorrect"**
- Vérifier l'encodage UTF-8
- Vérifier que le séparateur est une virgule
- Vérifier les en-têtes de colonnes

**"Matricule déjà existant"**
- Chaque étudiant doit avoir un matricule unique
- Modifier le matricule en doublon

---

## 📊 Données de Test Fournies

### Étudiants
- **15 étudiants** au total
- **10 Licence 3** en Informatique
- **5 Master 2** en Informatique
- Tous avec téléphone

### Enseignants
- **8 enseignants** au total
- **5 en Informatique** (spécialités variées)
- **2 en Mathématiques**
- **1 en Physique**

---

## 💡 Conseils

### Pour une Import Réussi

1. **Commencer petit** : Tester avec 2-3 entrées
2. **Vérifier les prérequis** : Filières, années universitaires doivent exister
3. **Backup** : Faire une sauvegarde de la base avant import massif
4. **Logs** : Consulter les logs en cas d'erreur (`storage/logs/laravel.log`)

### Après l'Import

1. Vérifier dans Admin → Utilisateurs que tous les comptes sont créés
2. Tester la connexion avec un compte importé
3. Changer le mot de passe si nécessaire
4. Envoyer les informations de connexion aux utilisateurs

---

## 📞 Support

Pour toute question sur les imports :
- Consulter la documentation : `GUIDE_UTILISATION.md`
- Vérifier les logs : `storage/logs/laravel.log`
- Contacter l'administrateur système

---

> 🤖 Documentation générée par Claude Code
