<?php
    
    //Initialisation de la connexion
    session_start();
    
    //Initialisation des ficheirs de config de la page web
    require_once 'config/init.conf.php';
    require_once 'config/bdd.conf.php';
    require_once 'includes/fonction.inc.php';
    require_once 'includes/connexion.inc.php';
    
    /*@var $bdd PDO */
    
    //On verifiea que le bouton submit a ete utilise
    if(isset($_POST['submit'])){
        $notification = "";
        
        //On insert dans la bdd les donnees du formulaire
        $sql_insert = "INSERT INTO utilisateur"
                ."(nom, prenom, email, password)"
                ."VALUE(:nom, :prenom, :email, :password)";
        //Preparation de la requete
        $sth=$bdd->prepare($sql_insert);
        
         //Securisation des parametres
        $sth->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $sth->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(':password', cryptPassword($_POST['password']), PDO::PARAM_STR); 
        
        
        //Execution de la requête SQL
        $result = $sth->execute();
        
        //Test d'ajout dans la base de données et ffichage par notification
        if($result == TRUE){
            $notification = '<b>Félicitation</b> votre utilisateur a été inséré dans la base de données';
            $result_notification = TRUE;
        } else {
            $notification = "Erreur d'insertion dans la base de données";
            $result_notification = FALSE;
        }
         
        
        $_SESSION['notification']['message']=$notification;
        $_SESSION['notification']['result']=$result_notification;
        header('Location: index.php');
        exit();
        
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    //affichage header/menu
    include_once('includes/header.inc.php');
    include_once('includes/menu.inc.php');
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Ajouter un utilisateur</h1>
            <form action="adduser.php" method="post" enctype="multipart/form-data" id="form_adduser">
                
                <!--Ajout nom-->
                <div class="form-group">
                    <label for="nom" class="col-form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="" required>
                </div>
                
                <!--Ajout prenom-->
                 <div class="form-group">
                    <label for="prenom" class="col-form-label">Prenom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" value="" required>
                </div>
                
                <!--Ajout email-->
                 <div class="form-group">
                    <label for="email" class="col-form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="exemple@exemple.com" value="" required>
                </div>
                
                <!--Ajout password-->
                 <div class="form-group">
                    <label for="password" class="col-form-label">Mot De Passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" value="" required>
                </div>
                
                <!--Ajout bouton envoie-->
                <button type="submit" class='btn btn-primary' name="submit" value="ajouter"> Ajouter l'utilisateur</button>
            </form>
        </div>
    </div>
</div>

<!--Ajout Pied De Page-->
<?php include_once('includes/footer.inc.php'); ?>