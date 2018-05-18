# chatWebSocket

Dans le cadre d'un projet commun en classe qui fut réalisé par groupe ( 2 ou 3 élèves), nous avons décidé de choisir parmi une liste de plusieurs missions de mettre en oeuvre un chat en ligne. Celui-ci a pour objectif d'être utilisé afin de mettre en relation des étudiants, entreprises et professeurs dans la perspective d'établir un véritable réseau d'aide à la recherche de stage.

Il est par exemple possible de communiquer par messagerie instantanée du texte, émoji, joindre des fichiers(documents, images, vidéos...) mais également de partager des URL qui seront intégrés directement dans le cours de la conversation. Il sera ainsi possible de lancer une vidéo directement depuis le chat avec son URL ou encore y accéder en cliquant sur le texte brute rendu en lien accessible accompagné d'un descriptif. 

Le chat est uniquement mis à disposition des membres étant inscrits. Une fois connecter l'utilisateur est visible par l'ensemble des autres membres qui sont connectés par la même occasion (pour l'instant pas de discussions hors ligne). Un compteur pour indiquer le nombre d'internautes connectés ainsi que le nom, l'avatar et l'id de ces derniers est également visibles de tous.

Un chat global est présent sur la page mais des disscussions privées peuvent être tenues entre chaque utilisateur dès lors que ces derniers cliques sur un profil visible dans le tableau des membres connectés.

## Prérequis

Afin de pouvoir faire fonctionner ce projet, il faut 

- Télécharger Deamon Memcached (lien Windows : https://commaster.net/content/installing-memcached-windows | lien Linux : https://memcached.org/)
- Télécharger le driver PHP de Microsoft a la version 4.3 pour SQL serve. Utiliser uniquement la version PDO en fonction de votre version PHP et selon que le serveur apache soit en mode TS ou NTS (lien : https://www.microsoft.com/en-us/download/details.aspx?id=55642)
- Télécharger le driver memcache pour PHP 7 et version supérieur, il s'agit ici d'un driver non officiel car le développement est interrompus depuis la version 5.6 de PHP (lien : https://www.google.com/url?q=https://github.com/nono303/PHP7-memcache-dll&sa=D&source=hangouts&ust=1526720218599000&usg=AFQjCNGMpHC4a6RZlaDxGSP-oivQGR21pA)

## Installation


## Déploiement

Une fois à la racine du repertoire du projet rendez vous dans le dossier **"inc"** puis faite un clique droit sur le fichier **"app.ini"** et cliquer sur modifier.  Une fois cette opération effectué suivre la démarche suivante : 
![alt text](https://zupimages.net/up/18/20/9o9w.png)
![alt text](https://zupimages.net/up/18/20/6jea.png)
![alt text](https://zupimages.net/up/18/20/pqxh.png)
![alt text](https://zupimages.net/up/18/20/shyr.png)

Il faudra modifier les fichiers d'exécutions du serveur webSocket et du deamon memcached veuillez vous rendre à la racine du projet puis faite un clique droit sur le fichier **"call.bat"** et cliquer sur modifier : 
![alt text](https://zupimages.net/up/18/20/3y74.png)
![alt text](https://zupimages.net/up/18/20/3n9h.png)
![alt text](https://zupimages.net/up/18/20/tsfv.png)
Il faudra par la suite changer le chemin pour l'adapter au chemin ou se situe votre repertoire. 
Une fois les changements terminés enregistrer les modifications.

Ensuite modifier le fichier d'execution du serveur serveur webSocket veuillez vous rendre à la racine du projet puis faite un clique droit sur le fichier **"webSocketServer.bat"** et cliquer sur modifier (même démarche que précedement):
![alt text](https://zupimages.net/up/18/20/3ch8.png)
Il faudra également changer le chemin pour l'adapter au chemin ou se situe votre repertoire. 
Une fois les changements terminés enregistrer les modifications.

Enfin lancer les serveurs en cliquant sur **call.bat** !

##Construit avec
![Composer](https://getcomposer.org/ "Télécharger et installer composer") - Composer est un gestionnaire de dépendances libre écrit en PHP
![Symfony](https://symfony.com/doc/3.4/setup.html "Télécharger et installer Symfony") - Framework MVC libre écrit en PHP (Utiliser la version 3.4)

Memcached est un système d'usage général servant à gérer la mémoire cache distribuée. On s'en sert comme gestionnaire de session.
Cela nous permets partager des sessions entre plusieurs serveur.

