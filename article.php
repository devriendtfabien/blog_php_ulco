<?php

    session_start();
    
    require_once 'config/init.conf.php';
    require_once 'config/bdd.conf.php';
    require_once 'includes/connexion.inc.php';
    
    /*@var $bdd PDO */
    //On verifiea que le bouton submit a ete utilise
    if(isset($_POST['submit'])){
        //print_r2($_POST);
        //print_r2($_FILES);
        
        $notification = "";
        
        //Condition pour le retour de valeur de publie
        $publie = (isset($_post['publie'])) ? 1 : 0;
        
        //obtenir la date actuelle
        $date_du_jour = date("Y-m-d");
        
        //Si on ajoute
        if($_POST['submit']=='ajouter'){
                  //On insert dans la bdd les donnees du formulaire
            $sql_insert = "INSERT INTO article"
                ."(titre, texte, publie, date)"
                ."VALUE(:titre, :texte, :publie, :date)";
            //Preparation de la requete
            $sth=$bdd->prepare($sql_insert);
        
            //Securisation des parametres
            $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
            $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
            $sth->bindValue(':publie', $_POST['publie'], PDO::PARAM_INT);
            $sth->bindValue(':date', $date_du_jour, PDO::PARAM_STR);  
            
        }else{
            
            //Sinon on modifie
            $sql_update="UPDATE article SET "
                    . "titre = :titre, "
                    . "texte = :texte, "
                    . "publie = :publie "
                    . "WHERE id_article=:id_article;";
            
            //Preparation de la requete
            $sth=$bdd->prepare($sql_update);
            
            //Securisation des parametres
            $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
            $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
            $sth->bindValue(':publie', $publie, PDO::PARAM_INT);
            $sth->bindValue(':id_article', $_POST['id_article'], PDO::PARAM_INT);
                        
        }    
                
        $result = $sth->execute();
        
        
        if($result == TRUE){
            $notification = '<b>Félicitation</b> votre article a été inséré dans la base de données';
            $result_notification = TRUE;
        } else {
            $notification = "Erreur d'insertion dans la base de données";
            $result_notification = FALSE;
        }
        if($_POST['submit']=='ajouter'){ 
            $id_article = $bdd->lastInsertId();        

            if($_FILES['image']['error'] == 0){
                $extension = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
                $extension = strtolower($extension);

                $result_img = move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $id_article.'.'.$extension);
                $notification .= $result_img == TRUE ? '' : '<br/><b>Attention</b> une erreur est survenue lors du déplacement de l\'image';
            }
        }
        
        $_SESSION['notification']['message']=$notification;
        $_SESSION['notification']['result']=$result_notification;
        header('Location: index.php');
        exit();
        
    }
    
    include_once('includes/header.inc.php');
    include_once('includes/menu.inc.php');
    
    $action=$_GET['action'];
    
    if ($action == 'ajouter') {
        $tab_article = array(
            'id_article'=>'',
            'titre'=>'',
            'texte'=>'',
            'publie'=>'',
            'date'=>'',
        );
    }else{
        $id_article=$_GET['id'];
        $sql_select = "SELECT "
                . "id_article, "
                . "titre, "
                . "texte, "
                . "date "
                . "publie "
                . "FROM article "
                . "WHERE id_article=:id_article;";
        
        /* @var$bdd PDO */
        
        $sth=$bdd->prepare($sql_select);
        $sth->bindValue(':id_article', $id_article, PDO::PARAM_STR);
        $sth->execute();
        $tab_article = $sth->fetch(PDO::FETCH_ASSOC);                
    }
    
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5"><?= ucfirst($action) ?> un article</h1>
            <form action="article.php" method="post" enctype="multipart/form-data" id="form_article">
                <input type="hidden" value="<?=$tab_article['id_article']?>" name="id_article"/>
                <div class="form-group">
                    <label for="titre" class="col-form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de votre article" value="<?=$tab_article['titre']?>" required>
                </div>
                <div class="form-group">
                    <label for="texte">Texte</label>
                    <textarea class="form-control" id="texte" name="texte" rows="3" required><?=$tab_article['texte']?></textarea>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" id="image" name="image" class="custom-file-input">
                        <label class="custom-file-label" for="image">Choisir un fichier</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label for="publie" class="form-check-label">
                            <input class="form-check-input" name="publie" type="checkbox" value="1"<?php if($tab_article['publie']=='1'){?>checked<?php }?>>Publié? 
                        </label>
                    </div>
                </div>
                <button type="submit" class='btn btn-primary' name="submit" value="<?= $action ?>"> <?= ucfirst($action) ?> l'article </button>
            </form>
        </div>
    </div>
</div>
<?php include_once('includes/footer.inc.php'); ?>