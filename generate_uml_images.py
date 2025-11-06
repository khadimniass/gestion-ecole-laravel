#!/usr/bin/env python3
"""
Script pour générer des images PNG à partir des diagrammes PlantUML
Utilise le serveur PlantUML en ligne
"""

import os
import zlib
import base64
import requests
from pathlib import Path
import time

def plantuml_encode(plantuml_text):
    """
    Encode le texte PlantUML selon la spécification PlantUML
    """
    # Compression avec zlib
    compressed = zlib.compress(plantuml_text.encode('utf-8'))

    # Encodage personnalisé PlantUML (modification du base64)
    encoded = base64.b64encode(compressed).decode('utf-8')

    # Remplacements spécifiques à PlantUML
    encoded = encoded.replace('+', '-').replace('/', '_')

    return encoded

def generate_png_from_plantuml(puml_file_path, output_dir):
    """
    Génère une image PNG à partir d'un fichier .puml
    """
    # Lire le fichier PlantUML
    with open(puml_file_path, 'r', encoding='utf-8') as f:
        plantuml_text = f.read()

    # Encoder le contenu
    encoded = plantuml_encode(plantuml_text)

    # URL du serveur PlantUML
    plantuml_server = "http://www.plantuml.com/plantuml/png"
    url = f"{plantuml_server}/{encoded}"

    print(f"Génération de {puml_file_path.name}...")

    try:
        # Télécharger l'image
        response = requests.get(url, timeout=60)
        response.raise_for_status()

        # Nom du fichier de sortie
        output_file = output_dir / puml_file_path.name.replace('.puml', '.png')

        # Sauvegarder l'image
        with open(output_file, 'wb') as f:
            f.write(response.content)

        print(f"  ✓ Image sauvegardée : {output_file}")
        return True

    except requests.exceptions.RequestException as e:
        print(f"  ✗ Erreur lors de la génération : {e}")
        return False

def main():
    # Répertoires
    docs_dir = Path(__file__).parent / 'docs'
    images_dir = docs_dir / 'images'

    # Créer le dossier images s'il n'existe pas
    images_dir.mkdir(exist_ok=True)
    print(f"Dossier de sortie : {images_dir}\n")

    # Trouver tous les fichiers .puml
    puml_files = sorted(docs_dir.glob('*.puml'))

    if not puml_files:
        print("Aucun fichier .puml trouvé dans le dossier docs/")
        return

    print(f"Fichiers PlantUML trouvés : {len(puml_files)}\n")

    # Générer les images
    success_count = 0
    for puml_file in puml_files:
        if generate_png_from_plantuml(puml_file, images_dir):
            success_count += 1
        # Petit délai pour éviter de surcharger le serveur
        time.sleep(1)

    print(f"\n{'='*60}")
    print(f"Génération terminée : {success_count}/{len(puml_files)} images créées")
    print(f"{'='*60}")

if __name__ == '__main__':
    main()
