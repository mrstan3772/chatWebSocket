# chatWebSocket

Dans le cadre d'un projet commun en classe qui fut réalisé par groupe ( 2 ou 3 élèves), nous avons décidé de choisir parmi une liste de plusieurs missions de mettre en oeuvre un chat en ligne. Celui-ci a pour objectif d'être utilisé afin de mettre en relation des étudiants, entreprises et professeurs dans la perspective d'établir un véritable réseau d'aide à la recherche de stage.

Il est par exemple possible de communiquer par messagerie instantanée du texte, émoji, joindre des fichiers(documents, images, vidéos...) mais également de partager des URL qui seront intégrés directement dans le cours de la conversation. Il sera ainsi possible de lancer une vidéo directement depuis le chat avec son URL ou encore y accéder en cliquant sur le texte brute rendu en lien accessible accompagné d'un descriptif. 

Le chat est uniquement mis à disposition des membres étant inscrits. Une fois connecter l'utilisateur est visible par l'ensemble des autres membres qui sont connectés par la même occasion (pour l'instant pas de discussions hors ligne). Un compteur pour indiquer le nombre d'internautes connectés ainsi que le nom, l'avatar et l'id de ces derniers est également visibles de tous.

Un chat global est présent sur la page mais des disscussions privées peuvent être tenues entre chaque utilisateur dès lors que ces derniers cliques sur un profil visible dans le tableau des membres connectés.

## Prérequis

Afin de pouvoir faire fonctionner ce projet, il faut :
- **PHP 7.0.X | 7.1.X**
- Télécharger composer pour installer les dépendances PHP (lien : https://getcomposer.org/)
- Télécharger le deamon Memcached (lien Windows : https://commaster.net/content/installing-memcached-windows | lien Linux : https://memcached.org/)
- Télécharger le driver PHP de Microsoft a la version 4.3 pour SQL Server. Utiliser uniquement le module pour une connexion PDO en fonction de votre version PHP et selon que le serveur apache soit en mode TS ou NTS (lien : https://www.microsoft.com/en-us/download/details.aspx?id=55642)
- Télécharger le driver memcache pour PHP 7 et version supérieur, il s'agit ici d'un driver non officiel car le développement est interrompus depuis la version 5.6 de PHP (lien : https://www.google.com/url?q=https://github.com/nono303/PHP7-memcache-dll&sa=D&source=hangouts&ust=1526720218599000&usg=AFQjCNGMpHC4a6RZlaDxGSP-oivQGR21pA)

## Installation

### Les dépendances

Ouvrir le terminal(cmd). 

Utiliser la commande `composer install` depuis le répertoir **"inc"** du projet(chat). Ne pas répondre aux questions si vous en voyez durant l'installation(appuyer sur la touche entrée pour passer). Une fois cette étape faite vous aurez tout le nécessaire ainsi que Symfony en version 3.4.

### Les extensions

[![Driver PHP](https://zupimages.net/up/18/20/wtsy.png)](https://vimeo.com/270703992 "Driver PHP - Cliquer pour regarder!")

### Service Memcached pour Windows

[![Memcached Windows](https://zupimages.net/up/18/20/ty0w.png)](https://vimeo.com/270697711 "Memcached Windows - Cliquer pour regarder !")

## Déploiement

### Configuration des fichiers nécessaire au démarrage et à la connexion au serveur WebSocket

Une fois à la racine du repertoire du projet, rendez vous dans le dossier **"inc"** puis faite un clique droit sur le fichier **"app.ini"** et cliquer sur modifier. Voici une démonstration :

![alt text](https://zupimages.net/up/18/20/9o9w.png)

![alt text](https://zupimages.net/up/18/20/6jea.png)

![alt text](https://zupimages.net/up/18/20/pqxh.png)

Une fois cette démarche effectué faire les modifications suivante : 

![alt text](https://zupimages.net/up/18/20/k3eh.png)

*Remarque :* Ne surtout pas oublier de mettre les informations de connexion à la base de données entre `''`.

Il faudra par la suite modifier le contenu des fichiers d'exécution du serveur WebSocket et du deamon memcached.Pour celà veuillez vous rendre à la racine du dossier contenant le projet(chat) puis faite un clique droit sur le fichier **"call.bat"** et cliquer sur modifier : 

![alt text](https://zupimages.net/up/18/20/3y74.png)

![alt text](https://zupimages.net/up/18/20/3n9h.png)

Il faudra par la suite changer les chemins pour les adapter aux chemins ou se situe le repertoire du projet(chat). Ceux-ci doivent pointer vers les scripts batch **"memcached.bat"** et **webSocketServer.bat** comme ci-dessous :

![alt text](https://zupimages.net/up/18/20/tsfv.png)

Une fois les changements terminés, enregistrer les modifications.

Ensuite pour modifier le script d'exécution du serveur WebSocket veuillez vous rendre à la racine du projet(chat) puis faite un clique droit sur le fichier **"webSocketServer.bat"** et cliquer sur modifier (même démarche que précedement).

Il faudra également changer le chemin pour l'adapter au chemin ou se situe votre repertoire contenant le projet(chat) :
![alt text](https://zupimages.net/up/18/20/3ch8.png)

Une fois les changements terminés, enregistrer les modifications.

La dernière étape de la configuration consiste à se rendre dans le répertoire **"cdn"** puis ouvrir le fichier **"chat.js"** et modifier la ligne de code suivante : 

```javascript
window.ws = $.websocket("ws://<ip>:<port>", {
```

Remplacer l'ip et le port de connexion du serveur WebSocket afin que ceux-ci soient identiques à ceux présent dans le fichier **"app.ini"**.

*Remarque :* Si vous êtes derrière un routeur alors mettez l'adresse publique qui redirige vers l'adresse privée(qui doit être dans le fichier **"app.ini"**). Assurez vous que toutes les redirections(serveur HTTP, WebSocket, Memcached) sont actives avec le protocole TCP et sur les bons ports. 

### Création des tables dans la base de données (SQL Server)

Désormais nous allons nous pencher sur la création des tables requises pour pouvoir enregistrer les informations utilisateur. Il suffira simplement d'ouvrir le répertoire **"database"** et importer les scripts suivants (vous pouvez utiliser SQL Server Management Studio dit également SSMS pour vous simplifier la tâche) :  

- **"CHAT.category.Table.sql"**
- **"CHAT.users.Table.sql"**
- **"CHAT.wsMessages.Table.sql"**
- **"CHAT.wsPrivateMessages.Table.sql"**

Une fois l'importation finalisé, nous allons devoir remplir la table **"category"** avec 3 champs. Placez-vous dans la base de données ou vous avez créé les tables puis exéctuer le script sql suivant : 

```sql
INSERT INTO CHAT.category VALUES('Étudiant')

INSERT INTO CHAT.category VALUES('Professeur')

INSERT INTO CHAT.category VALUES('Entreprise')
```

*Remarque :* Avant ou pendant leur importation(avec SSMS), pour que ceux-ci puisse s'exécuter il va falloir modifier la ligne suivante `USE [databaseName]` pour chaque script avec le nom de la base de données dans laquelle vous souhaitez stocker les informations.

Enfin lancer les serveurs en cliquant sur **call.bat**.

## Structure de l'application

[![SessionProvider](http://socketo.me/assets/img/RatchetSessions.png)](http://socketo.me/docs/sessions "SessionProvider - Cliquer pour consulter la documentation sur les sessions et WebSocket !")

## Construit avec

[Composer](https://getcomposer.org/ "Télécharger et installer composer") - Composer est un gestionnaire de dépendances libre écrit en PHP

[Symfony](https://symfony.com/doc/3.4/setup.html "Télécharger et installer Symfony") - Framework MVC libre écrit en PHP (Utiliser la version 3.4)

[Memcached](https://memcached.org/) - Memcached est un système d'usage général servant à gérer la mémoire cache distribuée. Dans notre cas on s'en sert comme un service de gestionnaire de session. Cela nous permet de partager des sessions entre plusieurs serveurs(WebSocket et Apache).

## Contribution

Veuillez lire [CONTRIBUTING.md](CONTRIBUTING.md) pour plus de détails sur notre code de conduite et sur le processus de soumission des demandes d'extraction.

## Version

Version : 1.0.0.rc1

Nous utilisons SemVer pour le versioning. Pour plus de détail, reportez-vous au lien en cliquant [ici](https://semver.org/).

## Auteurs

**Wei JIE** - *Étudiant* - [Ailuro](https://github.com/weijie98)

**Stanley LOUIS JEAN** - *Étudiant* - [MrStan](https://github.com/mrstan3772)

## Licence

[![CC0 1.0 universel (CC0 1.0)](https://camo.githubusercontent.com/da896acd40e1f4f275c2da6e1d830b2865803fc8/68747470733a2f2f692e6372656174697665636f6d6d6f6e732e6f72672f702f7a65726f2f312e302f38387833312e706e67)](https://creativecommons.org/publicdomain/zero/1.0/deed.fr "CC0 1.0 universel (CC0 1.0) Transfert dans le Domaine Public - Cliquer pour comprendre ce logo !")

## Remerciements

Chat réalisé à partir du tutoriel : 
https://subinsb.com/live-group-chat-with-php-jquery-websocket/
