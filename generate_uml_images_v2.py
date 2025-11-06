#!/usr/bin/env python3
"""
Script pour générer des images PNG à partir des diagrammes PlantUML
Utilise le package Python plantuml
"""

import os
from pathlib import Path
from plantuml import PlantUML

def generate_png_from_plantuml(puml_file_path, output_dir):
    """
    Génère une image PNG à partir d'un fichier .puml
    """
    # Créer l'instance PlantUML
    plantuml = PlantUML(url='http://www.plantuml.com/plantuml/img/')

    # Lire le fichier PlantUML
    with open(puml_file_path, 'r', encoding='utf-8') as f:
        plantuml_text = f.read()

    # Nom du fichier de sortie
    output_file = output_dir / puml_file_path.name.replace('.puml', '.png')

    print(f"Génération de {puml_file_path.name}...")

    try:
        # Générer et sauvegarder l'image
        plantuml.processes_file(str(puml_file_path), outfile=str(output_file))

        # Vérifier si le fichier a été créé
        if output_file.exists() and output_file.stat().st_size > 0:
            print(f"  ✓ Image sauvegardée : {output_file} ({output_file.stat().st_size} bytes)")
            return True
        else:
            print(f"  ✗ Échec : fichier non créé ou vide")
            return False

    except Exception as e:
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

    print(f"\n{'='*60}")
    print(f"Génération terminée : {success_count}/{len(puml_files)} images créées")
    print(f"{'='*60}")

    # Lister les fichiers créés
    print("\nFichiers créés :")
    for png_file in sorted(images_dir.glob('*.png')):
        size_kb = png_file.stat().st_size / 1024
        print(f"  - {png_file.name} ({size_kb:.1f} KB)")

if __name__ == '__main__':
    main()
