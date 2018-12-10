<?php
    //Fonction de hashage de mot de passe en SHA1
    function cryptPassword($mdp){
        $mdp_crypt = sha1($mdp);
        return $mdp_crypt;
        }
    
    //Fonction de création de sid avec la concaténantion de l'email & du timestamp hashé en md5   
    function sid($email){
        $sid = md5($email . time());
        return $sid;
    }
    
    //Fonction d'obtention d'index
    function pagination($page_courante, $nb_articles_par_page){
        $index = ($page_courante-1) * $nb_articles_par_page;
        return $index;
    }
    
    //Fonction obtention nombre d'articles publies
    
    function nb_total_article_publie($bdd){
        /* @var $bdd PDO */
        $sql = "SELECT COUNT(*) as nb_total_articles_publie "
                . "FROM article "
                . "WHERE publie=1";
        
        $sth=$bdd->prepare($sql);
        $sth->execute();
        $tab_result=$sth->fetch(PDO::FETCH_ASSOC);
        return $tab_result['nb_total_articles_publie'];
    }
    
    function nb_total_article_recherche($recherche, $bdd){
        /* @var $bdd PDO */
        $sql = "SELECT COUNT(*) as nb_total_articles_publie "
                . "FROM article "
                . "WHERE (titre LIKE :recherche OR text LIKE :recherche) "
                . "AND publie=1 ";
        
        $sth=$bdd->prepare($sql);
        $sth=bindValue(':recherche', '%' .$recherche. '%', PDO::PARAM_STR);
        $sth->execute();
        $tab_result=$sth->fetch(PDO::FETCH_ASSOC);
        return $tab_result['nb_total_articles_publie'];
    }
    
    