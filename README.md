# Portail-Web-et-gestion-de-paiement
Constitution d'un portail et d'un entrepôt de données pour la gestion de paiement par cartes bancaires.


Voici l'utilitée des différentes pages :

L’HTML : 
  Le site web se découpe en plusieurs pages : 
  Index.php : Correspond à la page d’accueil principale. Elle regroupe l’ensemble des données dans les tableaux annonce de trésorerie, remise et impayés. Elle n’est visible que par le product owner et les clients.
  AdminChoice.php: Correspond à la page d’accueil de l’admin du site. Elle lui permet de choisir entre ajouter un utilisateur et accéder à la liste des utilisateurs.
  addAccount.php: Formulaire d’ajout d’un utilisateur sur le site
  delAccount.php: Page de suppression d’utilisateur
  graph_impayes.php : Page affichant les graphiques liés aux impayés, triés par 4 mois ou 12 mois glissants mais aussi avec des dates à entrer.
  login.php : Formulaire pour accéder à son compte.
  Le CSS :
  Ces pages sont stylisées grâces au feuilles de css suivantes : 
  Style.css :  Feuille de style principale. Elle stylise en particulier la page d’accueil index.php (tableaux, onglets, boutons..)
  style-connexion.css : Feuille de style spéciale pour la page de connexion (champ de mot de passe, logo, …).
  style-graphiques :  Feuille de style spéciale pour la page des graphiques liées aux impayés (champ “date”).

Le JavaScript :
	Du javascript à été intégré dans les pages pour les fonctionnalités suivantes:
  Affichage des tableaux de détails dans les remises
  Affichage des graphiques highcharts dans la page des graphiques impayés
  Fonctions rendant la navigation dans les tableaux plus ergonomiques (mémorisation de l’onglet sélectionné dans le tableaux)

Le PHP :
  Tout cela se repose sur des bibliothèques php que nous avons créées à la main : 
  Database.php : regroupe toutes les fonctions de connections à la Base de donnée
  Export.php : regroupe toutes les fonctions qui servent à exporter les différents    tableaux et graphs.
  SQLDatas.php : regroupe toutes les fonctions qui extraient des données de la base de données.
  GenerateHTML.php : regroupe quelques fonction qui génère du code html redondant 
