<?php 
    
    session_start();
    
    require_once 'config/init.conf.php';
    require_once 'config/bdd.conf.php';
    require_once 'includes/connexion.inc.php';
        
    include_once('includes/header.inc.php');
    include_once('includes/menu.inc.php');
    include_once('includes/fonction.inc.php');
    
    if(isset($_SESSION['notification'])){
        $color_notification = $_SESSION['notification']['result'] == TRUE ? 'success' : 'danger';
    }
    
?>

<!-- Page Content --> 
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Mon blog</h1>
            <?php echo "helloworld, I make some includes ;D";?>
        </div>
        
        <?php if(isset($_SESSION['notification'])){ ?>
        <div class="alert alert-<?= $color_notification?> alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php $_SESSION['notification']['message'] ?>
            
            <?php unset($_SESSION['notification']) ?>
        </div>
        <?php }?>
    </div>

        <?php
        //Calcul de l'index
        $page_courante = !empty( $_GET['p']) ?  $_GET['p']: 1;
        //$page_courante=$_GET['p'];
        $index = pagination($page_courante, _nb_articles_par_page);
        
        $nb_article = nb_total_article_publie($bdd);
        $nb_page = ceil($nb_article / _nb_articles_par_page);
        //echo $page_courante;
        //echo _nb_articles_par_page;
        //print_r2($index);
        //print_r2($nb_page);
        //echo "np page".$nb_page;
        //print_r2($_GET['p']);
        
        //Requete de selection des articles 
            $select_articles ="SELECT "
                    ."id_article, "
                    ."titre, "
                    ."texte, "
                    ."DATE_FORMAT(date, '%d/%m/%Y') as date, "
                    ."publie "
                    ."FROM article "
                    ."WHERE publie=:publie "
                    ."LIMIT :index, :nb_articles_par_page;";
            /*@var $bdd PDO */
            //Preparation de la requete a execute
            $sth = $bdd->prepare($select_articles);
            //Securisation des parametres
            $sth->bindValue(':publie', 1, PDO::PARAM_BOOL);
            $sth->bindValue(':nb_articles_par_page', 2, PDO::PARAM_INT);
            $sth->bindValue(':index', $index, PDO::PARAM_INT);


            //execution de la requete
            $sth->execute();
            //association des enregistrements
            $tab_article = $sth->fetchAll(PDO::FETCH_ASSOC);
            //affichage du tableau de données
            //print_r2($tab_article);
            //echo $tab_article[0]['titre'];
        ?>
        <div class="row">
            <?php
                    foreach ($tab_article as $key => $value) {                        
            ?>
            <div class="col-md-6">
                <div class="card mt-4">
                    <img class="card-img-top" src="img/<?= $value['id_article']?>.jpeg" alt="">
                    <div class="card-body">
                        <h4 class="card-title"><?= $value['titre']?></h4>
                        <p class="card-text"><?= $value['texte']?></p>
                        <a href="#" class="btn btn-primary"> Crée le : <?= $value['date']?> </a>
<!--                        <a href="'article.php?action=modifier&id='<?$value['id']?>" class="btn btn-warning">Modifier</a>-->
                        <a href="article.php?action=modifier&id=<?=$value['id_article']?>" class="btn btn-warning">Modifier</a>
                      
                </div>
            </div>
        </div> 
        <?php
            }
        ?>
    </div>
    <div class="row">
        <nav aria-label="Page navigation exeample" class="mx-auto mt-4">
            <ul class="pagination">
    <?php
        for ($i=1; $i <=$nb_article; $i++) {
           // echo"<a href='?p='>PAGE</a>";            
    ?>
        <li class="page-item">
            <a class="page-link" href="?p=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
        <?php
        } 
        ?>
        </ul>
    </nav>
    </div>
</div>
<?php include_once('includes/footer.inc.php'); ?>