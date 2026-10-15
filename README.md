# EEMI M1 — Cours Symfony

Projet support du cours Symfony (M1 Lead dev IA).

Stack : **Symfony 8.1**, **PHP 8.4**, Doctrine ORM + Migrations, Twig, AssetMapper (Stimulus / Turbo), base de données **MySQL**.

---

## 1. Prérequis

Installez ces outils avant de commencer :

| Outil | Version | Vérification |
|-------|---------|--------------|
| [PHP](https://www.php.net/downloads) | **8.4 ou plus** | `php -v` |
| [Composer](https://getcomposer.org/download/) | 2.x | `composer -V` |
| [Symfony CLI](https://symfony.com/download) | dernière version | `symfony -V` |
| MySQL / MariaDB | via [MAMP](https://www.mamp.info/) (Mac/Windows), [XAMPP](https://www.apachefriends.org/) ou [WAMP](https://www.wampserver.com/) | — |
| [Git](https://git-scm.com/downloads) | — | `git --version` |

Vérifiez que votre PHP contient toutes les extensions nécessaires :

```bash
symfony check:requirements
```

> L'extension `pdo_mysql` doit être activée (dans `php.ini`, décommentez `extension=pdo_mysql` si besoin).

---

## 2. Récupérer le projet

```bash
git clone https://github.com/yoanncoualan/EEMI_2627_M1_Symfony.git
cd EEMI_2627_M1_Symfony
```

## 3. Installer les dépendances

```bash
composer install
```

Cette commande télécharge le dossier `vendor/` et installe aussi les assets JavaScript (`importmap:install`).

## 4. Configurer la base de données

Créez un fichier **`.env.local`** à la racine du projet (il n'est pas versionné, chacun a le sien) et ajoutez votre connexion MySQL :

```dotenv
# MAMP (port 8889, utilisateur root / mot de passe root)
DATABASE_URL="mysql://root:root@127.0.0.1:8889/eemi_m1_cours_symfony?charset=utf8mb4"

# XAMPP / WAMP (port 3306, utilisateur root / sans mot de passe)
# DATABASE_URL="mysql://root:@127.0.0.1:3306/eemi_m1_cours_symfony?charset=utf8mb4"
```

Gardez **une seule** ligne `DATABASE_URL` active et adaptez le port, l'utilisateur et le mot de passe à votre installation.

> ⚠️ Ne modifiez pas le fichier `.env` : il est commun à tout le monde. Vos réglages perso vont dans `.env.local`.

Démarrez votre serveur MySQL (MAMP / XAMPP / WAMP), puis créez la base et ses tables :

```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate --no-interaction
php bin/console importmap:install
```

## 5. Lancer le projet

```bash
symfony serve
```

Ouvrez ensuite <https://127.0.0.1:8000> dans votre navigateur.

Pages disponibles :

- `/product` — liste et ajout de produits
- `/categorie` — liste et ajout de catégories

Pour arrêter le serveur : `symfony server:stop`.

---

## Récupérer les mises à jour du cours

À chaque nouvelle séance :

```bash
git pull
composer install
symfony console doctrine:migrations:migrate --no-interaction
```

> Si vous avez modifié des fichiers de votre côté, `git pull` peut échouer. Mettez vos changements de côté avec `git stash` avant, ou travaillez sur votre propre branche (`git checkout -b mon-travail`).

---

## Problèmes fréquents

| Erreur | Solution |
|--------|----------|
| `could not find driver` | Activez l'extension `pdo_mysql` dans votre `php.ini`. |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL n'est pas démarré, ou le port dans `DATABASE_URL` est faux (8889 pour MAMP, 3306 pour XAMPP/WAMP). |
| `SQLSTATE[HY000] [1045] Access denied` | Mauvais utilisateur / mot de passe dans `DATABASE_URL`. |
| `Your requirements could not be resolved` (composer) | Votre version de PHP est trop ancienne : il faut PHP 8.4+. Vérifiez avec `php -v`. |
| `symfony: command not found` | Installez la Symfony CLI, ou utilisez `php bin/console` à la place de `symfony console` et `php -S localhost:8000 -t public` à la place de `symfony serve`. |
| Page sans style / JS cassé | Lancez `symfony console importmap:install`. |
| Comportement bizarre après un `git pull` | Videz le cache : `symfony console cache:clear`. |

Pour diagnostiquer une erreur : lisez `var/log/dev.log` ou ouvrez le profiler sur <https://127.0.0.1:8000/_profiler>.