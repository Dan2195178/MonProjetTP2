<?php
    /*
        modele.php est le fichier qui représente notre MODÈLE dans notre architecture MVC. 
        C'est donc dans ce fichier que nous retrouverons TOUTES nos requêtes SQL sans AUCUNE EXCEPTION. 
        C'est aussi ici que se trouvera la connexion à la base de données et les informations nécessaires 
        à celle-ci (username, hostname, password, nom de la base, etc...)
    
    */
   
    define("SERVER", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DBNAME", "BaseBlog");

    
    function connectDB()
    {
        //se connecter à la base de données
        $c = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME);

        if(!$c)
            trigger_error("Erreur de connexion : " . mysqli_connect_error());
        
        //s'assurer que la connection traite du UTF8
        mysqli_query($c, "SET NAMES 'utf8'");

        return $c;
    }

    $connexion = connectDB();

    function obtenir_articles()
    {
        global $connexion;
        $requete = "SELECT id, idAuteur, titre, texte, CONCAT(usagers.prenom, ' ', usagers.nom) AS NomAuteur FROM articles JOIN usagers ON username = idAuteur ORDER BY id DESC";
        //exécuter la requête avec mysqli... 
        $resultats = mysqli_query($connexion, $requete);
        //retourner le résultat, les rangées dans le cas d'un SELECT ou true ou false dans le cas d'un INSERT/DELETE/UPDATE
        return $resultats;
    }

    function obtenir_article_par_id($idArticle)
    {
        global $connexion;
        $requete = "SELECT * FROM articles WHERE id = $idArticle";
        $resultat = mysqli_query($connexion, $requete);
        return $resultat;
    }
   
    function modification_article($idArticle,$titre,$texte)
    {
        global $connexion;

        if ($reqPrep = mysqli_prepare($connexion, "UPDATE articles SET titre = ? , texte = ? WHERE id = ?"))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 'ssi', $titre, $texte, $idArticle);   
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);
            //est-ce que la modification a fonctionné
            if(mysqli_affected_rows($connexion) > 0)
            {
                return true;
            }
        }

    }

    function suppression_article_par_id($idArticle)
    {
        global $connexion;
        // rédiger la requête et la mettre dans une string
        $requete = "DELETE FROM articles WHERE id = $idArticle";
        // appel de mysqli_query qui retourne true ou false dans le cas de la supression  
        $resultat = mysqli_query($connexion, $requete);
        return $resultat;

        // if($resultat)
        // {
        //     if(mysqli_affected_rows($connexion) > 0)               
        //         header("Location: ListeProfesseurs.php?message=Suppression réussie.");
        //     else
        //         header("Location: ListeProfesseurs.php");
        // }
        // else
        // {
        //     header("Location: ListeProfesseurs.php?message=La suppression n'a pas fonctionné.");            
        // }
        
    }

    
    function creation_article($idAuteur,$titre,$texte)
    {
        global $connexion;

        if($reqPrep = mysqli_prepare($connexion, "INSERT INTO articles(idAuteur, titre, texte) VALUES (?, ?, ?)"))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 'sss', $idAuteur, $titre, $texte);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);

            //est-ce que l'insertion a fonctionné
            if(mysqli_affected_rows($connexion) > 0)
            {
                return true;
            }
            else
            {
                die("Erreur lors de l'insertion." . mysqli_error($connexion));
            }
        }
    }

    function recherche_article($recherche)
    {
        global $connexion;
        
        $recherche = "%" . $recherche . "%";
         
        $requete = "SELECT id, idAuteur, titre, texte, CONCAT(usagers.prenom, ' ', usagers.nom) AS NomAuteur FROM articles JOIN usagers ON username = idAuteur WHERE titre LIKE ? OR texte LIKE ? ORDER BY id DESC";
     
        //exécution de la requête
        $reqPrep = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($reqPrep, 'ss', $recherche, $recherche);
        mysqli_stmt_execute($reqPrep);
        $resultats = mysqli_stmt_get_result($reqPrep);

        if(mysqli_num_rows($resultats) > 0)
        {
            $NombreArticles = mysqli_num_rows($resultats);
            return $resultats;
        } 
        else
            return false;
       
    }

    function login($username, $password)
    {
        global $connexion;

        if($reqPrep = mysqli_prepare($connexion, "SELECT password FROM usagers WHERE binary username=?"))
        {
            //lier les paramètres
            mysqli_stmt_bind_param($reqPrep, 's', $username);
            //exécuter la requête
            mysqli_stmt_execute($reqPrep);
            //obtenir le résultat (utilisable par la suite avec mysqli_fetch_array)
            $resultats = mysqli_stmt_get_result($reqPrep);

            if(mysqli_num_rows($resultats) > 0)
            {
                $rangee = mysqli_fetch_assoc($resultats);
                $motDePasseEncrypte = $rangee["password"];
                if(password_verify($password, $motDePasseEncrypte))
                    return true;
                else    
                    return false;
            }
            else
                return false;
        }
    }
    
   


