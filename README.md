# Projet site web : création d'une plateforme d'essais cliniques

## Description du Projet

Le projet vise à créer une plateforme d'essais cliniques permettant la gestion des essais pour les médecins, les patients, les entreprises pharmaceutiques, et les administrateurs.

Voici le récapitulatif des fonctionnalités dont dispose chaque type d'utilisateur sur le site web crée :

Patient :
  -Peut modifier ses informations personnelles depuis une interface intuitive
  -Peut consulter la liste des essais en phase de recrutement (ceux auxquels il a déjà participé par les passé sont cachés)
  -Peut faire une demande de participation à un essai en phase de recrutement
  -Peut consulter les informations des essais auxquels il est inscrit et donner ses impressions
  -Peut consulter l’historique global du site web depuis le menu dédié
  -Peut se déconnecter facilement depuis l’interface
Médecin :
  -Peut visualiser ses informations personnelles 
  -Peut consulter la liste des essais en phase de recrutement (ceux auxquels il a déjà participé par les passé sont cachés)
  -Peut faire une demande de participation à un essai en phase de recrutement
  -Peut consulter les informations générales des essais auxquels il est inscrit/en attente d’inscription
  -Peut consulter les informations patients des essais auxquels il est inscrit + les résultats si l’essai est fini
  -Peut modifier les informations des patients inscrit à un de ses essais
  -Peut consulter l’historique global du site web depuis le menu dédié
  -Peut se déconnecter facilement depuis l’interface
Entreprise :
  -Peut créer un essai
  -Peut regarder la liste de ses essais
  -Peut voir les détails d’un essai
  -Peut demander un médecin référent en fonction de sa spécialité
  -Peut accepter la demande d’un médecin référent ou le laisser en attente
  -Peut démarrer un essai que si il y a au moins un médecin référent et un patient minimum
  -Peut terminer un essai
  -Peut changer de phase 
  -Peut se déconnecter facilement depuis l’interface
Administrateur :
  -Peut visualiser ses informations personnelles mais ne peut les modifier 
  -Peut consulter la liste des essais quelque soit leur phases.
  -Peut accéder à la liste complète des utilisateurs du site
  -Peut accéder à une liste exhaustive des comptes médecins inscrits. 
  -Peut accéder à une liste exhaustive des comptes entreprises inscrits. 
  -Peut également Ban/Unban les utilisateurs à partir de ces différentes listes. 
  -Est responsable des demandes de validations de comptes.
  -Peut depuis cette liste de demandes en rejeter ou accepter. 
  -Peut se déconnecter facilement depuis l’interface

## Structure du projet

Les utilisateurs sont représentés sous forme d’objet (Patient, Medecin, Entreprise) --> Meilleure manipulation des variables et meilleur contrôle des méthodes

1 page html principale  + un fichier de fonction d’affichage  + le fichier de la classe  par utilisateur

Fichiers communs d’affichage ou de gestion de sécurité pour tous les utilisateurs + fichier de la classe Utilisateur pour les méthodes communes (connexion, inscription, déconnexion...)
Total : 30 fichiers php + 10 fichiers CSS.

## Utilisation du site web

- Les mots de passe dans la base de données remplies sont par défaut égaux à "1234" ou à "123456789!@".

- Le fichier id_patient_count.txt peut refuser de s'ouvrir (erreur dans le script). Un chmod 777 a été réalisé mais le statut semble se réinitialiser parfois, il sera peut être nécessaire de le refaire.

- Pour une raison inconnue, le fichier de BDD vide ne garde pas en mémoire, lors de l'export, l'auto-incrémentation pour ID_essai (table_essai) et la valeur par défaut CURRENT_TIME_STAMP pour ID_User (table utilisateur). Les ajouter depuis l'interface de phpmyadmin est une bonne option.

- Il est conseillé d'ouvrir une session en local (localhost) du site web via XAMPP pour tester les fonctionnalités du site web.

## Auteurs

Le projet à été réalisé à 4 : Simon Rouget, Simon De Montardy, Edmond Berne, Corentin Meyvaert
