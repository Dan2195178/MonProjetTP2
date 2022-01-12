<?php
    $article = $donnees["article"]; 
    //afficher dynamiquement des informations de l'article spécifiée 
     if($rangee = mysqli_fetch_assoc($article))
    {
        // Prendre le titre et le texte de la BD et les met respectivement dans le variable $titre et $texte
        $titre =  htmlspecialchars($rangee["titre"]);
        $texte = htmlspecialchars($rangee["texte"]);
        $idArticle = $_REQUEST["idArticle"];
    }  
    
     
?>
<h1>Formulaire de modifier d'un article</h1>
<form method="POST" action="index.php">
    <label for="titre">Titre de l'article :</label><br><br>
     <textarea rows="1" cols="80" type="text" name="titre" > <?= $titre ?> </textarea><br><br>
     <label for="titre">Texte de l'article :</label> <br><br>
     <textarea rows="15" cols="80" name="texte"> <?= $texte ?></textarea><br><br>
    <input type="hidden" name="idArticle"  value=<?= $idArticle ?>>
    <input type="hidden" name="commande" value="ModifierArticleParId" ><br>
    <input type="submit" value="Sauvegarder"/>
</form>
<?php
    if(isset($_REQUEST["messageErreur"]))
        echo "<p>" . $_REQUEST["messageErreur"] . "</p>";
?>  