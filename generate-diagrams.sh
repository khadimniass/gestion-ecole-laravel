#!/bin/bash
###############################################################################
# Script de Génération des Diagrammes UML en PNG
# Usage: ./generate-diagrams.sh
###############################################################################

set -e  # Arrêter en cas d'erreur

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Banner
echo -e "${BLUE}"
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║       Génération des Diagrammes UML - Gestion PFE        ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Vérifier si plantuml est installé
echo -e "${YELLOW}🔍 Vérification des dépendances...${NC}"

if ! command -v plantuml &> /dev/null; then
    echo -e "${RED}❌ PlantUML n'est pas installé.${NC}"
    echo ""
    echo "Installation requise :"
    echo "  Ubuntu/Debian: sudo apt-get install plantuml graphviz"
    echo "  macOS (Homebrew): brew install plantuml"
    echo "  Windows (Chocolatey): choco install plantuml"
    echo ""
    echo "Ou consultez : docs/README-GENERATION-IMAGES.md"
    exit 1
fi

if ! command -v dot &> /dev/null; then
    echo -e "${YELLOW}⚠️  Graphviz n'est pas installé (recommandé).${NC}"
    echo "  Installation : sudo apt-get install graphviz"
    echo ""
fi

echo -e "${GREEN}✓ PlantUML trouvé : $(plantuml -version | head -1)${NC}"

# Créer le dossier images s'il n'existe pas
echo -e "${YELLOW}📁 Création du dossier images...${NC}"
mkdir -p docs/images
echo -e "${GREEN}✓ Dossier docs/images/ prêt${NC}"

# Compter les fichiers .puml
PUML_COUNT=$(ls docs/*.puml 2>/dev/null | wc -l)

if [ "$PUML_COUNT" -eq 0 ]; then
    echo -e "${RED}❌ Aucun fichier .puml trouvé dans docs/${NC}"
    exit 1
fi

echo -e "${BLUE}📊 Fichiers PlantUML trouvés : $PUML_COUNT${NC}"
echo ""

# Générer les images
echo -e "${YELLOW}🚀 Génération des images PNG en cours...${NC}"
echo ""

# Générer avec plantuml
plantuml docs/*.puml -o images/ -tpng -progress -charset UTF-8

# Vérifier le résultat
if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✅ Génération réussie !${NC}"
    echo ""

    # Lister les fichiers créés
    echo -e "${BLUE}📁 Images créées dans docs/images/ :${NC}"

    for file in docs/images/*.png; do
        if [ -f "$file" ]; then
            size=$(du -h "$file" | cut -f1)
            filename=$(basename "$file")
            echo -e "  ${GREEN}✓${NC} $filename ${YELLOW}($size)${NC}"
        fi
    done

    # Statistiques
    PNG_COUNT=$(ls docs/images/*.png 2>/dev/null | wc -l)
    TOTAL_SIZE=$(du -sh docs/images/ 2>/dev/null | cut -f1)

    echo ""
    echo -e "${BLUE}═══════════════════════════════════════════════${NC}"
    echo -e "${GREEN}📊 Statistiques :${NC}"
    echo -e "  - Images générées : ${GREEN}$PNG_COUNT${NC}"
    echo -e "  - Taille totale : ${GREEN}$TOTAL_SIZE${NC}"
    echo -e "${BLUE}═══════════════════════════════════════════════${NC}"

    # Vérifier si toutes les images ont été créées
    if [ "$PNG_COUNT" -eq "$PUML_COUNT" ]; then
        echo -e "${GREEN}✅ Toutes les images ont été générées avec succès !${NC}"
    else
        echo -e "${YELLOW}⚠️  $((PUML_COUNT - PNG_COUNT)) image(s) manquante(s)${NC}"
    fi

    echo ""
    echo -e "${BLUE}💡 Conseil :${NC} Visualisez les images avec :"
    echo "  xdg-open docs/images/usecase-diagram.png"

else
    echo ""
    echo -e "${RED}❌ Erreur lors de la génération${NC}"
    echo ""
    echo "Consultez le guide complet : docs/README-GENERATION-IMAGES.md"
    exit 1
fi

echo ""
echo -e "${GREEN}🎉 Terminé !${NC}"
