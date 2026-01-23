# Configuration Docker pour PHP - Guide étudiant

Le contenu de ce dossier a été rédigé par l'enseignant et amélioré/reformaté par Github Copilot.

## Prérequis

- Docker Desktop installé sur votre machine
- Un éditeur de code (VSCode ou PHPStorm)

## Installation

1. **Copier les fichiers de configuration**
   
   Copiez le contenu du dossier `fichiers` dans votre projet PHP. Votre structure devrait ressembler à:
   ```
   mon-projet/
   ├── docker-compose.yml
   ├── docker/
   │   ├── DockerfilePHP
   │   ├── nginx.conf
   │   ├── xdebug.ini
   │   └── error_reporting.ini
   ├── index.php
   └── ...
   ```

2. **Démarrer les conteneurs**
   
   Ouvrez un terminal à la racine de votre projet et exécutez:
   ```bash
   docker-compose up -d
   ```

   Le -d signifie "détaché", les conteneurs tourneront en arrière-plan. Sans cette option, les logs s'afficheraient dans le terminal et le processus serait bloqué.

3. **Vérifier l'installation**
   
   Créez un fichier `index.php` à la racine avec:
   ```php
   <?php
   phpinfo();
   ```
   
   Visitez http://localhost dans votre navigateur. Vous devriez voir les informations PHP.

## Accès aux services

- **Application web**: http://localhost
- **phpMyAdmin**: http://localhost:8080
  - Utilisateur: `root`
  - Mot de passe: `root`
- **Base de données**:
  - Host: `localhost` (depuis votre machine) ou `db` (depuis PHP)
  - Port: `3306`
  - Base de données: `test`
  - Utilisateur: `test`
  - Mot de passe: `test`

## Configuration du débogueur (Xdebug)

### Sur Linux

Si Xdebug ne fonctionne pas, éditez le fichier `docker/xdebug.ini` et modifiez:
```ini
; Commenter cette ligne:
; xdebug.client_host=host.docker.internal

; Décommenter cette ligne:
xdebug.client_host=172.17.0.1
```

Puis redémarrez les conteneurs:
```bash
docker-compose restart php
```

### Configuration PHPStorm

1. **Démarrer Docker**
   - Cliquez sur l'icône de flèche double dans le fichier `docker-compose.yml`
   - Ou utilisez le terminal: `docker-compose up -d`

2. **Configurer le serveur (optionnel)**
   - Allez dans `File > Settings > PHP > Servers`
   - Créez un nouveau serveur avec:
     - Name: `Docker`
     - Host: `localhost`
     - Port: `80`
     - Debugger: `Xdebug`
   - Cochez "Use path mappings"
   - Mappez le dossier racine de votre projet vers `/var/www/html`

3. **Configurer l'interpréteur PHP**
   - Allez dans `File > Settings > PHP`
   - Cliquez sur `...` à côté de "CLI Interpreter"
   - Ajoutez un nouvel interpréteur "From Docker, Vagrant..."
   - Choisissez "Docker Compose"
   - Service: `php`
   - De retour aux `Settings > PHP`, sélectionnez le `PHP language level` avec la bonne version et l'interpréteur que vous venez de créer.

4. **Activer l'écoute du débogueur**
    - Clickez sur l'icône d'insecte en haut au centre (il y aura une icône "wifi" verte lorsque activé) ou allez dans le menu `Run > Start Listening for PHP Debug Connections`
    - Placez des points d'arrêt dans votre code (cliquez à gauche du numéro de ligne)
    - Rafraîchissez votre page web

    - Si ça ne fonctionne pas, vérifiez Xdebug avec `Run > Web Server Debug Validation`

### Configuration VSCode

*Section non testée - à valider*

1. **Installer l'extension**
   - Installez l'extension "PHP Debug" de Xdebug

2. **Créer la configuration**
   
   Créez un fichier `.vscode/launch.json` à la racine de votre projet:
   ```json
   {
     "version": "0.2.0",
     "configurations": [
       {
         "name": "Listen for Xdebug",
         "type": "php",
         "request": "launch",
         "port": 9003,
         "pathMappings": {
           "/var/www/html": "${workspaceFolder}"
         },
         "log": true
       }
     ]
   }
   ```

3. **Utiliser le débogueur**
   - Appuyez sur `F5` ou allez dans le menu `Run > Start Debugging`
   - Placez des points d'arrêt dans votre code (cliquez à gauche du numéro de ligne)
   - Rafraîchissez votre page web

## Commandes Docker utiles

```bash
# Démarrer les conteneurs
docker-compose up -d

# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Voir les logs d'un service spécifique
docker-compose logs -f php

# Redémarrer un service
docker-compose restart php

# Accéder au conteneur PHP en ligne de commande
docker-compose exec php bash

# Voir les conteneurs en cours d'exécution
docker-compose ps
```

## Dépannage

### Le port 80 est déjà utilisé

Modifiez le fichier `docker-compose.yml`, section `web > ports`:
```yaml
ports:
  - "8000:80"  # Changez 80 par 8000 (ou un autre port libre)
```
Accédez ensuite à http://localhost:8000

### Le port 3306 est déjà utilisé

Si vous avez déjà MySQL/MariaDB installé localement:
```yaml
db:
  ports:
    - "3307:3306"  # Utilisez 3307 au lieu de 3306
```

### Xdebug ne fonctionne pas

1. Vérifiez que Xdebug est activé:
   ```bash
   docker-compose exec php php -v
   ```
   Vous devriez voir "with Xdebug" dans la sortie.

2. Consultez les logs Xdebug:
   ```bash
   docker-compose exec php cat /tmp/xdebug.log
   ```

3. Sur Linux, modifiez `xdebug.client_host` comme indiqué plus haut.

### Les changements de code ne sont pas pris en compte

- Pour PHP: rafraîchissez simplement la page (F5)
- Pour les configurations Docker: redémarrez les conteneurs
  ```bash
  docker-compose restart
  ```

### Erreur de permissions

Sur Linux, si vous avez des problèmes de permissions:
```bash
sudo chown -R $USER:$USER .
```

### Les changement à la configuration Docker ne sont pas pris en compte

Après avoir modifié les fichiers Docker (Dockerfile, docker-compose.yml, etc.), sur PHPStorm, arrêtez les conteneurs et repartez-les, ne faites pas juste le "reset".

## Conseils

- Utilisez toujours `docker-compose down` avant d'éteindre votre ordinateur
- Les données de la base de données sont persistantes (volume Docker)
- Pour tout remettre à zéro: `docker-compose down -v` (supprime les données de la BD)
- Consultez les logs en cas d'erreur: `docker-compose logs -f`