# Guide de Génération des Images UML

Ce guide vous explique comment générer des images PNG à partir des diagrammes PlantUML.

## 🎯 Pourquoi générer les images ?

Les images PNG permettent de :
- Visualiser rapidement les diagrammes sans outils spéciaux
- Inclure les diagrammes dans des présentations PowerPoint
- Intégrer les diagrammes dans des rapports Word/PDF
- Partager facilement avec des personnes sans PlantUML

---

## 🔧 Méthodes de Génération

### Méthode 1 : Via PlantText (En ligne - Le plus simple)

**Avantages** : Aucune installation, immédiat
**Inconvénient** : Peut ne pas fonctionner pour les très gros diagrammes

#### Étapes :

1. **Accédez à PlantText**
   Ouvrez votre navigateur : https://www.planttext.com/

2. **Copiez le contenu d'un diagramme**
   ```bash
   # Dans le terminal
   cat docs/usecase-diagram.puml
   # Puis copiez tout le contenu
   ```

3. **Collez dans PlantText**
   - Collez le code dans la zone de texte à gauche
   - Le diagramme s'affiche automatiquement à droite

4. **Téléchargez l'image**
   - Clic droit sur le diagram → "Enregistrer l'image sous..."
   - Sauvegardez dans `docs/images/` avec le nom approprié

5. **Répétez pour les 9 diagrammes** :
   - `uml-class-diagram.png`
   - `usecase-diagram.png`
   - `sequence-inscription.png`
   - `sequence-proposition-sujet.png`
   - `sequence-demande-encadrement.png`
   - `sequence-soutenance.png`
   - `state-sujet-pfe.png`
   - `state-pfe.png`
   - `state-demande-encadrement.png`

---

### Méthode 2 : Installation Locale (Recommandé pour Ubuntu/Linux)

**Avantages** : Rapide, automatisé, fonctionne pour tous les diagrammes
**Inconvénient** : Nécessite installation

#### Étapes :

1. **Installer PlantUML et Graphviz**
   ```bash
   sudo apt-get update
   sudo apt-get install -y plantuml graphviz
   ```

2. **Générer toutes les images automatiquement**
   ```bash
   cd /chemin/vers/gestion-ecole-laravel
   plantuml docs/*.puml -o images/
   ```

3. **Vérifier les images créées**
   ```bash
   ls -lh docs/images/*.png
   ```

**Résultat attendu** : 9 fichiers PNG créés dans `docs/images/`

---

### Méthode 3 : Avec Docker (Multi-plateforme)

**Avantages** : Fonctionne sur Windows/Mac/Linux, pas besoin d'installer Java
**Inconvénient** : Nécessite Docker

#### Étapes :

1. **Installer Docker Desktop**
   - Windows/Mac : https://www.docker.com/products/docker-desktop
   - Linux : `sudo apt-get install docker.io`

2. **Utiliser l'image PlantUML officielle**
   ```bash
   cd /chemin/vers/gestion-ecole-laravel

   # Générer toutes les images
   docker run --rm -v $(pwd)/docs:/data \
     plantuml/plantuml:latest \
     -tpng -o /data/images "/data/*.puml"
   ```

3. **Vérifier les images**
   ```bash
   ls -lh docs/images/*.png
   ```

---

### Méthode 4 : Extension VS Code (Interface graphique)

**Avantages** : Interface visuelle, prévisualisation en temps réel
**Inconvénient** : Nécessite VS Code et Java

#### Étapes :

1. **Installer l'extension PlantUML**
   ```bash
   code --install-extension jebbs.plantuml
   ```

2. **Installer Java et Graphviz**
   ```bash
   sudo apt-get install default-jre graphviz
   ```

3. **Ouvrir un fichier .puml**
   ```bash
   code docs/usecase-diagram.puml
   ```

4. **Prévisualiser le diagramme**
   - Appuyez sur `Alt + D` pour voir la prévisualisation
   - Ou clic droit → "Preview Current Diagram"

5. **Exporter en PNG**
   - Clic droit sur la prévisualisation → "Export Diagram"
   - Choisir le format PNG
   - Sauvegarder dans `docs/images/`

6. **Répéter pour tous les fichiers .puml**

---

### Méthode 5 : Serveur PlantUML Local

**Avantages** : Interface web locale, rapide
**Inconvénient** : Nécessite Java

#### Étapes :

1. **Télécharger PlantUML JAR**
   ```bash
   wget https://github.com/plantuml/plantuml/releases/download/v1.2024.7/plantuml-1.2024.7.jar -O plantuml.jar
   ```

2. **Démarrer le serveur**
   ```bash
   java -jar plantuml.jar -tpng docs/*.puml -o images/
   ```

3. **Vérifier les images générées**
   ```bash
   ls -lh docs/images/
   ```

---

## 📋 Script Automatisé (Linux/Mac)

Utilisez le script fourni `generate-diagrams.sh` :

```bash
#!/bin/bash
# Génération automatique des diagrammes UML

echo "🚀 Génération des diagrammes UML en PNG..."

# Vérifier si plantuml est installé
if ! command -v plantuml &> /dev/null; then
    echo "❌ PlantUML n'est pas installé."
    echo "   Installation : sudo apt-get install plantuml graphviz"
    exit 1
fi

# Créer le dossier images s'il n'existe pas
mkdir -p docs/images

# Générer toutes les images
echo "📊 Génération en cours..."
plantuml docs/*.puml -o images/ -tpng

# Vérifier le résultat
if [ $? -eq 0 ]; then
    echo "✅ Génération réussie !"
    echo ""
    echo "📁 Images créées dans docs/images/ :"
    ls -lh docs/images/*.png | awk '{print "  -", $9, "(" $5 ")"}'
else
    echo "❌ Erreur lors de la génération"
    exit 1
fi
```

**Utilisation** :
```bash
chmod +x generate-diagrams.sh
./generate-diagrams.sh
```

---

## 🖼️ Diagrammes à Générer

Voici la liste complète des 9 diagrammes :

### 1. Diagramme de Classes
- **Fichier source** : `docs/uml-class-diagram.puml`
- **Image cible** : `docs/images/uml-class-diagram.png`
- **Description** : Structure complète des entités (12 classes, 3 pivots, 8 enums)

### 2. Diagramme de Cas d'Utilisation
- **Fichier source** : `docs/usecase-diagram.puml`
- **Image cible** : `docs/images/usecase-diagram.png`
- **Description** : 82 cas d'utilisation, 5 acteurs, 8 packages

### 3. Séquence - Inscription
- **Fichier source** : `docs/sequence-inscription.puml`
- **Image cible** : `docs/images/sequence-inscription.png`
- **Description** : Processus d'inscription utilisateur avec génération matricule

### 4. Séquence - Proposition Sujet
- **Fichier source** : `docs/sequence-proposition-sujet.puml`
- **Image cible** : `docs/images/sequence-proposition-sujet.png`
- **Description** : Workflow de proposition et validation de sujet PFE

### 5. Séquence - Demande Encadrement
- **Fichier source** : `docs/sequence-demande-encadrement.puml`
- **Image cible** : `docs/images/sequence-demande-encadrement.png`
- **Description** : Processus de demande d'encadrement étudiant→enseignant

### 6. Séquence - Soutenance
- **Fichier source** : `docs/sequence-soutenance.puml`
- **Image cible** : `docs/images/sequence-soutenance.png`
- **Description** : Planification soutenance et attribution notes

### 7. États - Sujet PFE
- **Fichier source** : `docs/state-sujet-pfe.puml`
- **Image cible** : `docs/images/state-sujet-pfe.png`
- **Description** : Cycle de vie d'un sujet (propose→valide→attribué)

### 8. États - PFE
- **Fichier source** : `docs/state-pfe.puml`
- **Image cible** : `docs/images/state-pfe.png`
- **Description** : Cycle de vie d'un PFE (propose→en_cours→soutenance→terminé)

### 9. États - Demande Encadrement
- **Fichier source** : `docs/state-demande-encadrement.puml`
- **Image cible** : `docs/images/state-demande-encadrement.png`
- **Description** : États d'une demande (en_attente→accepte/refuse)

---

## 🎨 Formats de Sortie Disponibles

PlantUML supporte plusieurs formats :

| Format | Extension | Commande | Usage |
|--------|-----------|----------|-------|
| PNG | `.png` | `-tpng` | Présentations, web |
| SVG | `.svg` | `-tsvg` | Vectoriel, zoom sans perte |
| PDF | `.pdf` | `-tpdf` | Documents académiques |
| EPS | `.eps` | `-teps` | Publications LaTeX |
| ASCII Art | `.txt` | `-ttxt` | Documentation code |

**Exemple pour SVG** :
```bash
plantuml docs/*.puml -o images/ -tsvg
```

---

## 🔍 Résolution des Problèmes

### Problème 1 : "PlantUML not found"
**Solution** :
```bash
# Ubuntu/Debian
sudo apt-get install plantuml

# macOS (Homebrew)
brew install plantuml

# Windows (Chocolatey)
choco install plantuml
```

### Problème 2 : "Graphviz not installed"
**Solution** :
```bash
# Ubuntu/Debian
sudo apt-get install graphviz

# macOS
brew install graphviz

# Windows
choco install graphviz
```

### Problème 3 : "Java not found"
**Solution** :
```bash
# Ubuntu/Debian
sudo apt-get install default-jre

# macOS
brew install openjdk

# Windows
# Télécharger depuis https://adoptium.net/
```

### Problème 4 : "Diagramme trop grand"
**Solutions** :
1. Générer en SVG (vectoriel, pas de limite) :
   ```bash
   plantuml -tsvg docs/usecase-diagram.puml
   ```

2. Augmenter la taille maximale :
   ```bash
   PLANTUML_LIMIT_SIZE=16384 plantuml docs/*.puml
   ```

3. Diviser le diagramme en plusieurs parties plus petites

### Problème 5 : "Police de caractères manquante"
**Solution** :
```bash
# Installer les polices
sudo apt-get install fonts-dejavu fonts-liberation
```

---

## 📐 Qualité et Taille des Images

### Paramètres de qualité

Pour contrôler la qualité des images PNG :

```bash
# Qualité standard (par défaut)
plantuml docs/*.puml

# Haute résolution (pour impression)
PLANTUML_LIMIT_SIZE=16384 plantuml docs/*.puml
```

### Tailles attendues

| Diagramme | Taille estimée |
|-----------|----------------|
| Classes | ~800 KB |
| Cas d'utilisation | ~600 KB |
| Séquences | ~400 KB chacun |
| États | ~300 KB chacun |

---

## 🚀 Génération Automatique via Git Hook (Optionnel)

Pour regénérer automatiquement les images à chaque commit :

1. **Créer le hook pre-commit**
   ```bash
   nano .git/hooks/pre-commit
   ```

2. **Ajouter le script**
   ```bash
   #!/bin/bash
   # Regénérer les images UML avant chaque commit

   if command -v plantuml &> /dev/null; then
       echo "Régénération des diagrammes UML..."
       plantuml docs/*.puml -o images/ -tpng -quiet
       git add docs/images/*.png
   fi
   ```

3. **Rendre exécutable**
   ```bash
   chmod +x .git/hooks/pre-commit
   ```

---

## ✅ Vérification

Une fois les images générées, vérifiez :

```bash
# Lister les images
ls -lh docs/images/*.png

# Compter les images (devrait afficher 9)
ls docs/images/*.png | wc -l

# Vérifier qu'aucune image n'est vide
find docs/images/ -name "*.png" -size 0 -print
```

**Résultat attendu** : 9 fichiers PNG de taille > 0

---

## 📚 Ressources Supplémentaires

- **PlantUML Official** : https://plantuml.com/
- **PlantText (en ligne)** : https://www.planttext.com/
- **Mermaid Live (alternative)** : https://mermaid.live/
- **Documentation PlantUML** : https://plantuml.com/guide
- **Extension VS Code** : https://marketplace.visualstudio.com/items?itemName=jebbs.plantuml

---

## 🆘 Support

Si vous rencontrez des problèmes :

1. Vérifiez que Java 8+ est installé : `java -version`
2. Vérifiez que Graphviz est installé : `dot -V`
3. Essayez la méthode en ligne (PlantText) pour tester
4. Consultez les logs d'erreur de PlantUML

---

**Date de création** : 2025-11-06
**Version** : 1.0
**Auteur** : Documentation projet Gestion École Laravel
