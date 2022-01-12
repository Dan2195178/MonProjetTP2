<?php
if(isset($_SESSION["usager"]))
{
?>
 &nbsp;&nbsp;<a href="index.php?commande=FormCreationArticle">  Rédiger un article &#10144;</a> 
<?php
}
?>  
          
<h1>Liste des articles</h1>

<form method="POST" action="index.php">
     Entrez votre recherche : <input type="text" name="recherche" size= 50;/>
      <input type="submit" value="Rechercher"/>
      <input type="hidden" name="commande" value="RechercheArticles"/>
 <?php
    if(isset($donnees["messageErreur"]))
        echo "<span>" . $donnees["messageErreur"] . "</span>";
?>  
</form>

        <ul>
           
            <?php

            //aller chercher dans $donnees ce qui nous intéresse
           if($donnees["articles"]!= false)
           {
            $articles = $donnees["articles"];
            
                while($rangee = mysqli_fetch_assoc($articles))
                {
                    //à chaque tour de boucle, $rangee vaut la nouvelle équipe
                    echo "<li>";
                    echo "<ul><li>TITRE: " . htmlspecialchars($rangee["titre"]);
                    if(isset($_SESSION["usager"]) && $_SESSION["usager"] == $rangee["idAuteur"]) 
                    {
                    echo "<a href='index.php?commande=FormModificationArticle&idArticle=" . $rangee["id"] . "'> &#9998; MODIFIER CET ARTICLE </a>";
                    echo "<a href='index.php?commande=SuppressionArticles&idArticle=" . $rangee["id"] . "'> &nbsp;&nbsp; || &nbsp;&nbsp;&#10007; SUPPRIMER CET ARTICLE </a>";
                    }
                    echo "</li><li>TEXTE: " . htmlspecialchars($rangee["texte"]) . "</li><li>AUTEUR: " . htmlspecialchars($rangee["NomAuteur"])."</ul>";
                    echo "</li><hr>";
                }
            }
            else
            {
                echo "<p>Aucun résultat correspondant!</p>"; 
            }
            ?>
        </ul>
        
        
