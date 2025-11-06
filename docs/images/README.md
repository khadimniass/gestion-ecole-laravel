# Images des Diagrammes UML

Ce dossier contient les images PNG générées à partir des diagrammes PlantUML.

## 📊 Images Disponibles

Une fois générées, vous trouverez ici :

1. **uml-class-diagram.png** - Diagramme de classes complet (12 entités)
2. **usecase-diagram.png** - Diagramme de cas d'utilisation (82 UC, 5 acteurs)
3. **sequence-inscription.png** - Séquence d'inscription utilisateur
4. **sequence-proposition-sujet.png** - Séquence de proposition de sujet
5. **sequence-demande-encadrement.png** - Séquence de demande d'encadrement
6. **sequence-soutenance.png** - Séquence de soutenance et notation
7. **state-sujet-pfe.png** - États d'un sujet PFE
8. **state-pfe.png** - États d'un PFE
9. **state-demande-encadrement.png** - États d'une demande

## 🚀 Générer les Images

### Option 1 : Script Automatique (Recommandé)

```bash
# Depuis la racine du projet
./generate-diagrams.sh
```

### Option 2 : Commande Manuelle

```bash
# Installer PlantUML si nécessaire
sudo apt-get install plantuml graphviz

# Générer toutes les images
plantuml docs/*.puml -o images/ -tpng
```

### Option 3 : En Ligne (PlantText)

1. Allez sur https://www.planttext.com/
2. Copiez le contenu d'un fichier `.puml` depuis `docs/`
3. Cliquez droit sur le diagramme → "Enregistrer l'image sous..."
4. Sauvegardez dans ce dossier `docs/images/`

## 📖 Documentation Complète

Consultez le guide détaillé : [docs/README-GENERATION-IMAGES.md](../README-GENERATION-IMAGES.md)

## 🔒 Git

Par défaut, les images ne sont **pas** commitées (trop volumineuses).

Pour commiter les images :
```bash
# Supprimer .gitignore dans ce dossier
rm docs/images/.gitignore

# Ajouter les images
git add docs/images/*.png
git commit -m "Docs: Ajouter images PNG des diagrammes UML"
```

## 📏 Tailles Estimées

- **Total** : ~3-4 MB pour les 9 images
- **Diagramme de classes** : ~800 KB
- **Cas d'utilisation** : ~600 KB
- **Séquences** : ~400 KB chacun
- **États** : ~300 KB chacun

## ✅ Vérification

Après génération, vérifiez :

```bash
# Compter les images (devrait afficher 9)
ls *.png | wc -l

# Vérifier qu'aucune n'est vide
find . -name "*.png" -size 0
```

---

**Note** : Si vous ne pouvez pas générer les images localement, utilisez PlantText en ligne pour créer les images manuellement.
