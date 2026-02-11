# Agents.md

À la racine de votre projet, dans un fichier `AGENTS.md`, copiez le texte suivant. Votre outil d'IA intégré va considérer le fichier avant de générer le code.

```md
# HTML

Pour le code HTML:

 * Doit être stylisé avec Tailwind CSS 4, utiliser UNIQUEMENT cette balise script exacte: `<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>`
 * Doit être responsive et s'adapter à tous les types d'écrans
 * Doit respecter les bonnes pratiques d'accessibilité selon le WCAG 2.1
 * Doit supporter le light et dark theme selon les paramètres du navigateur de l'utilisateur
 * Chaque balise HTML doit être écrite sur une seule ligne, sans retour à la ligne ni indentation à l'intérieur de la balise
 * IMPORTANT: Le code doit être indenté avec exactement 4 espaces par niveau de profondeur (pas 2, pas de tabulations)
 * Ne pas faire de CSS ni JavaScript
 * Garder la balise PHP en haut de la page si elle est présente
```

## Claude

Si vous utilisez Claude, vous devez créer un fichier `CLAUDE.md` dans le même répertoire. Ajoutez-y seulement le texte suivant: 
```md
@AGENTS.md
```