#Projet site web

- Les mots de passe dans la base de données remplies sont par défaut égaux à "1234" ou à "123456789!@".

- Le fichier id_patient_count.txt peut refuser de s'ouvrir (erreur dans le script). Un chmod 755 a été réalisé mais le statut semble se réinitialiser parfois, il sera peut être nécessaire de le refaire.

- Pour une raison inconnue, le fichier de BDD vide ne garde pas en mémoire, lors de l'export, l'auto-incrémentation pour ID_essai (table_essai) et la valeur par défaut CURRENT_TIME_STAMP pour ID_User (table utilisateur). Les ajouter depuis l'interface de phpmyadmin est une bonne option.
