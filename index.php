<?php
    /*
        index.php est le CONTRÔLEUR de notre application de type MVC (modulaire).
        
        Toutes les requêtes de notre application sans aucune exception devront passer par ce fichier.

        Le coeur du contrôleur sera sa structure décisionnelle qui traite un paramètre que l'on va nommer commande.
        C'est la valeur de ce paramètre commande qui déterminera les actions posées par le contrôleur.

        IMPORTANT : LE CONTRÔLEUR NE CONTIENT NI REQUÊTE SQL, NI HTML/CSS/JS, seulement du PHP.

        Le SQL va dans le modèle et strictement dans le modèle. 
        Le HTML va dans les vues et strictement dans les vues.

    */
    //démarrer la session
    session_start();
    //réception du paramètre commande, qui peut arriver en GET ou en POST 
    //(et donc nous utiliserons $_REQUEST)
    if(isset($_REQUEST["commande"]))
    {
        $commande = $_REQUEST["commande"];
    }
    else
    {
        //assigner une commande par défaut -- typiquement la commande qui mène à votre page d'accueil
        $commande = "Accueil";
    }

    //inclure le modele
    require_once("modele.php");

    //structure décisionnelle du contrôleur
    switch($commande)
    {
       
         //afficher la page d'accueil ou de la list des articles
        case "Accueil":
        case "AffichageArticles":
                //afficher la liste des articles
                //obtenir le modèle dont j'ai besoin (les articles)
                $donnees["articles"] = obtenir_articles();
                $donnees["titre"] = "Liste des articles";
    
                //afficher la ou les vues qu'on veut afficher 
                require_once("vues/Entete.php");
                require_once("vues/AffichageArticles.php");
                require_once("vues/PiedDePage.php");
    
                break; 
        //la page de login  
        case "FormLogin":
            require_once("vues/Entete.php");
            require_once("vues/FormulaireLogin.php");
            require_once("vues/PiedDePage.php");
            break;

        // case "password":
        //     require_once("EncryptionPassword.php");
        //     break;

        // Validtion du formulaire login
        case "ValidationLogin":
            if(isset($_REQUEST["user"], $_REQUEST["pass"]))
            {
                
                $test = login($_REQUEST["user"], $_REQUEST["pass"]);
                
                if($test)
                {
                    //combinaison valide
                    $_SESSION["usager"] = $_REQUEST["user"];
                    header("Location: index.php");
                }
                else
                {
                    $messageErreur = "Mauvaise combinaison username / password.";
                    require_once("vues/Entete.php");
                    require_once("vues/FormulaireLogin.php");
                    require_once("vues/PiedDePage.php");
                }
            }
            break;  
        case "Logout":
            // Initialisation de la session.
            // Détruit toutes les variables de session
            $_SESSION = array();

            // Si vous voulez détruire complètement la session, effacez également
            // le cookie de session.
            // Note : cela détruira la session et pas seulement les données de session !
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finalement, on détruit la session.
            session_destroy();

            //redirection vers la page d'accueil
            header("Location: index.php");
            break;  
        case "RechercheArticles":
            
            if(isset($_REQUEST["recherche"]) && trim($_REQUEST["recherche"]) != "")
            {
                     
                //effectuer une recherche dans les titres et les textes des articles et mettre le résultat dans $donnees["articles"]
                $donnees["articles"] = recherche_article(trim($_REQUEST["recherche"]));
                //afficher la ou les vues qu'on veut afficher 
                require_once("vues/Entete.php");
                require_once("vues/AffichageArticles.php");
                require_once("vues/PiedDePage.php");
        
             }
            else
            {    
                $donnees["messageErreur"] = "Veuillez entrer une recherche.";
                //afficher la liste des articles par défaut
                //obtenir le modèle dont j'ai besoin (les articles)
                $donnees["articles"] = obtenir_articles();
                $donnees["titre"] = "Liste des articles";
                //afficher la ou les vues qu'on veut afficher 
                require_once("vues/Entete.php");
                require_once("vues/AffichageArticles.php");
                require_once("vues/PiedDePage.php");
            }
          
            break;
        case "FormModificationArticle":
            //si l'usager est authentifié
            if(isset($_SESSION["usager"]))
            {   
                //obtenir les données de l'équipe
                $donnees["article"] = obtenir_article_par_id($_REQUEST["idArticle"]);
                $donnees["titre"] = "Modification de l'article";
               //afficher le formulaire de modification d'article
                require_once("vues/Entete.php");
                require_once("vues/FormModificationArticle.php");
                require_once("vues/PiedDePage.php");

                break;
            }
            else
                header("Location: index.php"); 
        case "ModifierArticleParId":
            //si l'usager est authentifié
            if(isset($_SESSION["usager"]))
            {
                if(isset($_REQUEST["titre"]) && isset($_REQUEST["texte"]))
                {
                    if(trim($_REQUEST["titre"]) != "" && trim($_REQUEST["texte"]) != "")
                    {
                        //procéder à la modification
                        $test = modification_article($_REQUEST["idArticle"],trim($_REQUEST["titre"]), trim($_REQUEST["texte"]));
                        if($test)
                            header("Location: index.php?commande=AffichageArticles&message=Modification réussie"); 
                        else
                            header("Location: index.php?commande=AffichageArticles");
                            
                    }
                    else
                    {   
                         header("Location: index.php?commande=FormModificationArticle&messageErreur=Tous les champs sont obligatoires!&idArticle=".$_REQUEST["idArticle"]);
                        
                    }
                }
            }
            
            break;
        case "SuppressionArticles":
            //si l'usager est authentifié
            if(isset($_SESSION["usager"]))
            {  
                if(isset($_REQUEST["idArticle"]) && is_numeric($_REQUEST["idArticle"]))
                {  
                   $idArticle = $_REQUEST["idArticle"];
                   $test = suppression_article_par_id($idArticle);
                   if($test)
                       echo '<script>alert("Suppression réussie!");location.href="index.php?commande=AffichageArticles"</script>';
                       
                   else
                       echo "<script>alert('Suppression échouée!')</script>";
                  
                   //header("Location: index.php?commande=AffichageArticles");
                }
            } 
            else 
                header("Location: index.php");   
            break;
        case "FormCreationArticle":
                //si l'usager est authentifié
                if(isset($_SESSION["usager"]))
                {
                    //afficher le formulaire d'ajout d'équipe
                    require_once("vues/Entete.php");
                    require_once("vues/FormCreationArticle.php");
                    require_once("vues/PiedDePage.php");
                    break;
                }
                else
                    header("Location: index.php");  
        case "CreationArticle":
            //si l'usager est authentifié
            if(isset($_SESSION["usager"]))
            {
                if(isset($_REQUEST["titre"]) && isset($_REQUEST["texte"]))
                {
                    if(trim($_REQUEST["titre"]) != "" && trim($_REQUEST["texte"]) != "")
                    {
                        
                        //procéder à l'insertion
                        $test = creation_article($_SESSION["usager"],trim($_REQUEST["titre"]), trim($_REQUEST["texte"]));
                        if($test)
                            header("Location: index.php?commande=AffichageArticles");    
                    }
                    else
                    {
                        $donnees["messageErreur"] = "Il faut entrer des valeurs dans tous les champs.";
                        require_once("vues/Entete.php");
                        require_once("vues/FormCreationArticle.php");
                        require_once("vues/PiedDePage.php");
                    }
                }
            }  
            else
                header("Location: index.php");     
            break;
        default:
            //action non traitée, commande invalide -- redirection
            header("Location: index.php");
    }


?>