# Projet Site Web : Création d'une Plateforme d'Essais Cliniques

## Description du Projet

Ce projet vise à développer une plateforme dédiée à la gestion des essais cliniques. L'outil est conçu pour répondre aux besoins de quatre types d'utilisateurs : patients, médecins, entreprises pharmaceutiques, et administrateurs.  

### Fonctionnalités par Type d'Utilisateur  

#### **Patient**
- Modifier ses informations personnelles via une interface intuitive.  
- Consulter la liste des essais en phase de recrutement (les essais auxquels il a participé par le passé sont masqués).  
- Faire une demande de participation à un essai en phase de recrutement.  
- Consulter les informations des essais auxquels il est inscrit et partager ses impressions.  
- Accéder à l’historique global du site via un menu dédié.  
- Se déconnecter facilement depuis l’interface.  

#### **Médecin**
- Visualiser ses informations personnelles.  
- Consulter la liste des essais en phase de recrutement (les essais auxquels il a participé par le passé sont masqués).  
- Faire une demande de participation à un essai en phase de recrutement.  
- Consulter les informations générales des essais auxquels il est inscrit ou en attente d’inscription.  
- Consulter les informations des patients associés à ses essais ainsi que les résultats si l’essai est terminé.  
- Modifier les informations des patients inscrits à un de ses essais.  
- Accéder à l’historique global du site via un menu dédié.  
- Se déconnecter facilement depuis l’interface.  

#### **Entreprise**
- Créer un essai.  
- Consulter la liste de ses essais.  
- Voir les détails d’un essai.  
- Demander un médecin référent en fonction de sa spécialité.  
- Accepter ou laisser en attente une demande d’un médecin référent.  
- Démarrer un essai si au moins un médecin référent et un patient minimum sont assignés.  
- Terminer un essai.  
- Changer la phase d’un essai.  
- Se déconnecter facilement depuis l’interface.  

#### **Administrateur**
- Visualiser ses informations personnelles (non modifiables).  
- Consulter la liste des essais, quelle que soit leur phase.  
- Accéder à la liste complète des utilisateurs du site.  
- Visualiser des listes exhaustives des comptes médecins et entreprises inscrits.  
- Bannir ou débannir les utilisateurs via ces listes.  
- Gérer les demandes de validation de comptes : accepter ou rejeter ces demandes.  
- Se déconnecter facilement depuis l’interface.  

---

## Structure du Projet

- Les utilisateurs sont modélisés sous forme d'objets (Patient, Médecin, Entreprise) pour faciliter la gestion des variables et des méthodes.  
- La structure des fichiers inclut :
  - Une page HTML principale.  
  - Un fichier de fonctions d’affichage par utilisateur.  
  - Un fichier de classe par type d’utilisateur.  
  - Des fichiers communs pour l’affichage ou la gestion de sécurité (classe utilisateur pour les méthodes communes comme connexion, inscription, déconnexion).  
- Nombre total de fichiers :  
  - 30 fichiers PHP.  
  - 10 fichiers CSS.  

---

## Utilisation du Site Web

1. **Mots de passe par défaut** :  
   - Les mots de passe dans la base de données initiale sont définis à :  
     - `"1234"`  
     - ou `"123456789!@"`.  

2. **Problème d'ouverture du fichier `id_patient_count.txt`** :  
   - Si une erreur survient, il peut être nécessaire de refaire un `chmod 777`. Le statut semble parfois se réinitialiser.  

3. **Problème de configuration de la base de données** :  
   - Le fichier de base de données vide ne conserve pas certaines configurations lors de l’export :  
     - L’auto-incrémentation pour `ID_essai` (table `table_essai`).  
     - La valeur par défaut `CURRENT_TIME_STAMP` pour `ID_User` (table `utilisateur`).  
   - Ces paramètres peuvent être corrigés via l’interface de phpMyAdmin.  

4. **Conseils pour tester le site web** :  
   - Utilisez un serveur local (ex. : XAMPP) pour ouvrir le site en local (`localhost`).  

---

## Auteurs

Ce projet a été réalisé par une équipe de quatre personnes :  
- **Simon Rouget**  
- **Simon De Montardy**  
- **Edmond Berne**  
- **Corentin Meyvaert**

