<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Mon Blog</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <?php
                    if($is_connect==FALSE){
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="connexion.php">Login</a>
                </li>
                <?php
                    }else{
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="deconnexion.php">Deconnexion</a>
                </li>
                    <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="adduser.php">S'inscrire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="article.php?action=créer">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>