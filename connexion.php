<?php 
//Initialisation des ficheirs de config de la page web
    require_once 'config/init.conf.php';
    require_once 'config/bdd.conf.php';
    require_once 'includes/fonction.inc.php';
    require_once 'includes/connexion.inc.php';
    
    /*@var $bdd PDO */
    //print_r2($_COOKIE);
    //echo cryptPassword($_POST['password']);
    
    if($is_connect == TRUE) {
        $_SESSION['notifications']['message'] = "Vous êtes déjà connecté.";
        $_SESSION['notifications']['result'] = true;
        header("Location: index.php");
        exit();
    }

    
        /*@var $bdd PDO */
    if(isset($_POST['submit'])){
         //echo 'test';
        $sql_insert_count = "SELECT COUNT(*) as total FROM utilisateur "
                ."WHERE email = :email "
                ."AND password = :password";
        //Preparation de la requete
        $sth=$bdd->prepare($sql_insert_count);    
         
        //Securisation des parametres
        $sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(':password', cryptPassword($_POST['password']), PDO::PARAM_STR);
        

        //Execution de la requete
        $result = $sth->execute();
        
        //Obtenir le resultat de la requete 
        $nb_result = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($nb_result['total']>0){
            //Preparation de la variable $sid
            
            $sid = sid($_POST['email']);
            $_COOKIE['sid'] = $sid;
            //print_r($sid);
            //Ajout du sid dans la BDD
            $sql_update = "UPDATE utilisateur "
                    . "SET sid = :sid "
                    . "WHERE email = :email;";
            
            $sth_update = $bdd->prepare($sql_update);
            //Securisation des variables
            $sth_update->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $sth_update->bindValue(':sid', $sid, PDO::PARAM_STR);
            
            $result_update = $sth_update->execute();
            //var_dump($result_update);
            //print_r2($sid);
            setcookie('sid', $sid, time() + 86400);
            //print_r2($_COOKIE['sid']);
            
            //Notofication
            $notification = "";
            if($result == TRUE){
                $notification = '<b>Félicitation</b> Vous êtes connecté';
                $result_notification = TRUE;
            } else {
                $notification = "Mauvais email / mot de passe";
                $result_notification = FALSE;
            }
            
            $_SESSION['notification']['message']=$notification;
            $_SESSION['notification']['result']=$result_notification;
            //header('Location: article.php');
            //echo $notification;
            
            $result_notification == TRUE ? header("Location : index.php") : header("Location : connexion.php");
            exit();       
        }
        if(iset($_SESSION['notification'])){
            $color_notfication = $_SESSION['notifcation']['result'] == TRUE ? 'succes' : 'danger';
        }
    }
   
     
//affichage header/menu
    include_once('includes/header.inc.php');
    include_once('includes/menu.inc.php');
    
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Connexion</h1>
            <form action="connexion.php" method="post" enctype="multipart/form-data" id="form_adduser">
                
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
                <button type="submit" class='btn btn-primary' name="submit" value="ajouter"> Se connecter</button>
            </form>
        </div>
    </div>
</div>

<!--Ajout Pied De Page-->
<?php include_once('includes/footer.inc.php'); ?>