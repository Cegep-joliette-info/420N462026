# GD

GD est une librairie de traitement d'image, elle est très bien intégrée à PHP. Il existe aussi ImageMagick qui est plus complexe mais possède plus d'options. Gmagick semble être entre les deux. Il existe plus de librairies comme libvips, mais nous allons garder les librairies officielles de PHP.

GD est déjà installé dans l'image Docker WebServer. Il existe [plusieurs fonctions](https://www.php.net/manual/en/book.image.php) pour GD, je vais montrer les principales.

```php
$img = @imagecreatefromjpeg('img/photo.jpg');
```

Charge l'image background en mémoire. Si vous utilisez bmp, png ou autre, il existe les fonctions correspondantes. Le @ désactive les erreurs, donc si GD n'est pas installé ou si l'image n'existe pas, $img sera false. Vous avez donc besoin de faire un seul if au lieu d'un if et d'un try/catch.

```php
list($width, $height) = getimagesize('img/photo.jpg');
$width = imagesx($img);
$height = imagesy($img);
```

La première ligne permet de connaitre la taille d'une image qui est dans un fichier, tandis que les 2 suivantes servent pour une image en mémoire.

```php
$newimg = imagecreatetruecolor(500, 500);
```

Cette ligne crée une nouvelle image de 500px de large par 500px de hauteur.

```php
imagecopyresampled($newimg, $img, 0, 0, 10, 10, 500, 500, 980, 730);
```

Cette ligne redimensionne une image et permet de "crop" l'image. Préférer `imagecopyresampled` à `imagecopyresized` : la première utilise une interpolation bicubique (résultat lisse), tandis que la seconde prend simplement le pixel le plus proche (résultat pixelisé, utile pour du pixel art).

On lui donne une image vide de la bonne taille et l'image à modifier. Ensuite le x, y du coin en haut à gauche de la nouvelle image. Ensuite le x, y du coin en haut à gauche de l'ancienne image, la copie va débuter à ce pixel. Ensuite la largeur et hauteur de la copie (dimension de la nouvelle image habituellement). Finalement la largeur et hauteur à prendre de l'ancienne image.
Donc dans l'exemple précédent (photo.jpg : 1000×750px), dans l'ancienne image on part du pixel 10x10 jusqu'à une taille de 980x730, on enlève donc 10 pixels de tous les côtés. On redimensionne cette copie dans la nouvelle image pour qu'elle atteigne une résolution de 500x500 pixels, on commence à "coller" à partir du pixel 0x0.

```php
$rgb = imagecolorat($newlogo, 0, 0);
$r = ($rgb >> 16) & 0xFF;
$g = ($rgb >> 8) & 0xFF;
$b = $rgb & 0xFF;
```

Permet de connaître la couleur du pixel en haut à gauche de l'image. Il retourne un entier qui va de 0 à 2²⁴ (16 777 215). Il faut donc faire du bitwise pour avoir les RGB séparés. Chaque variable `$r`, `$g` et `$b` aura une valeur entre 0 et 255 (2⁸ − 1).

```php
imagecolortransparent($img, $topleftpixel);
```

La couleur du 2e paramètre sera transparente dans l'image donnée. GD n'est pas très bon pour ça...

```php
imagecopymerge($img, $logo, 10, 10, 0, 0, 100, 100, 50);

// Ou si le logo a de la transparence (ex. PNG)
imagecopyresampled($img, $logo, 10, 10, 0, 0, 100, 100, imagesx($logo), imagesy($logo));

// Ou si le logo est déjà à la bonne taille
imagecopy($img, $logo, 10, 10, 0, 0, imagesx($logo), imagesy($logo));
```

`imagecopymerge` met le logo à partir de 10x10 et le redimensionne à 100x100, il met aussi une opacité de 50%. Par contre il n'est pas bon s'il y a de la transparence.

```php
imagefttext($img, $fontsize, 0, 0, $fontsize, 0, 'Paul.ttf', 'Toto');
```

Cette fonction ajoute du texte à l'image donnée. On lui donne l'image puis la taille du texte en points (1 pt = 1.33 px, mais pas toujours). Ensuite l'angle du texte, 0 c'est "normal", 90 va écrire du haut vers le bas. Ensuite le x, y du coin gauche-baseline de la zone de texte. Suivi de la couleur en entier, puis du fichier ttf et du texte à écrire.

```php
list($x1, $y1, $x2, $y2) = imageftbbox($fontsize, 0, $font, $text);

// Variante avec les 4 coins complets (bas-gauche, bas-droit, haut-droit, haut-gauche)
[$x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4] = imageftbbox($fontsize, 0, $font, $text);
```

Cette fonction permet d'avoir les coins en haut à gauche et en bas à droite d'une zone de texte, ça aide à placer au bon endroit le texte avec la fonction imagefttext.

```php
imagejpeg($newimg, 'img/final.jpg');
```

Cette ligne enregistre l'image générée sur le disque avec le nom donné.

```php
header("Content-Type: image/jpeg");
imagejpeg($newimg);
```

Finalement les lignes suivantes permettent d'afficher une image générée dynamiquement.

Réflexion: Est-ce mieux d'enregistrer l'image sur le disque ou de la générer dynamiquement? Considérer l'espace disque et la puissance de CPU nécessaire à chaque génération d'image.

---

## Exemples pratiques

### 1. Générer une miniature

```php
$src  = imagecreatefromjpeg('img/photo.jpg');
$srcW = imagesx($src);
$srcH = imagesy($src);

$thumb = imagecreatetruecolor(300, 300);
imagecopyresampled($thumb, $src, 0, 0, 0, 0, 300, 300, $srcW, $srcH);

imagejpeg($thumb, 'img/thumb.jpg');
imagedestroy($src);
imagedestroy($thumb);
```

`imagedestroy` libère la mémoire occupée par l'image. Utile dans les scripts qui traitent plusieurs images en boucle. À ignorer si c'est la dernière ligne du script, car PHP libère automatiquement la mémoire à la fin de l'exécution.

---

### 2. Watermark texte

```php
$img    = imagecreatefromjpeg('img/photo.jpg');
$couleur = imagecolorallocatealpha($img, 0, 0, 0, 80); // noir semi-transparent

imagefttext($img, 14, 0, 10, 30, $couleur, 'fonts/Paul.ttf', '© Mon Site');

imagejpeg($img, 'img/photo_watermark.jpg');
imagedestroy($img);
```

`imagecolorallocate` alloue une couleur utilisable dans une image. Ici `imagecolorallocatealpha` ajoute un 4e paramètre : l'opacité de 0 (opaque) à 127 (transparent).

---

### 3. Watermark logo

```php
$img   = imagecreatefromjpeg('img/photo.jpg');
$logo  = imagecreatefrompng('img/logo.png');
$logoW = imagesx($logo);
$logoH = imagesy($logo);

// Placer le logo en bas à droite avec une marge de 10px
$x = imagesx($img) - 100 - 10;
$y = imagesy($img) - 100 - 10;
imagecopyresampled($img, $logo, $x, $y, 0, 0, 100, 100, $logoW, $logoH);

imagejpeg($img, 'img/photo_logo.jpg');
imagedestroy($img);
imagedestroy($logo);
```

---

### 4. Générer un captcha

```php
$img       = imagecreatetruecolor(200, 60);
$bg        = imagecolorallocate($img, 240, 240, 240);
$textColor = imagecolorallocate($img, 30, 30, 30);
imagefill($img, 0, 0, $bg);

// Pixels de bruit aléatoires
for ($i = 0; $i < 500; $i++) {
    $bruit = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
    imagesetpixel($img, rand(0, 200), rand(0, 60), $bruit);
}

$code = strtoupper(substr(md5(rand()), 0, 6));
imagefttext($img, 20, rand(-10, 10), 20, 40, $textColor, 'fonts/Paul.ttf', $code);
$_SESSION['captcha'] = $code;

header('Content-Type: image/png');
imagepng($img);
imagedestroy($img);
```