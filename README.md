# Webhook (ENG)
Script to retrieve events related to Github issues

# Listing of events retrieved by the script

   - Creating the issue
   - Modification of the title and the description of the outcome
   - Closing the issue
   - Addition and modification of labels
   - Adding comments
   - Editing comments
   - Commit related to the outcome
  
   # Set up
  SQL
  
  Execute the [SQL file](https://github.com/Heiintz/WebHook-Github/blob/master/webhook_comments.sql) to create the database structure.
  
  GitHub

  
     - Go to the "Settings" tab of the repository
    
     - Click on "Webhooks" then on "Add webhook" (GitHub will ask for the password of your account)
    
     - Fill the Payload URL field => Put the URL of your WebHook.php file
    
     - Choose Content type => application / x-www-form-urlencoded
    
     - Choose a passphrase in SHA1
    
     - Then you have the opportunity to choose 3 radio: Basic with François we put "Send me everything" which corresponds to click on "Let me select individual event" and check everything


# Webhook (FR)
Script permettant de récupérer les évènements liés aux issues de Github

# Listing des évènements récupérés par le script

  - Création de l'issue 
  - Modification du titre et de la description de l'issue
  - Fermeture de l'issue
  - Ajout et modification des labels
  - Ajout de commentaires
  - Modification des commentaires
  - Commit liés à l'issue
  
  # Mise en place
  SQL
  
  Executer le [fichier SQL](https://github.com/Heiintz/WebHook-Github/blob/master/webhook_comments.sql) afin de créer la structure de la base de données.
  
  GitHub

  
    - Se rendre dans l'onglet "Settings"  du repository
    
    - Cliquer sur "Webhooks" puis sur "Add webhook" (GitHub demandera le mot de passe de votre compte)
    
    - Remplir le champ Payload URL => Mettre l'URL de votre fichier WebHook.php
    
    - Choisir Content type => application/x-www-form-urlencoded
    
    - Choisir une passphrase en SHA1
    
    - Ensuite, vous avez la possibilité de choisir 3 radio : De base avec François on a mis "Send me everything" ce qui correspond de cliquer sur "Let me select individual event" et de tout cocher 
