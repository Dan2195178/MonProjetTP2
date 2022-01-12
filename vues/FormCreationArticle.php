<h1>Formulaire d'ajout d'un article</h1>
<form method="POST" action="index.php">
    <label for="titre">Titre de l'article :</label><br><br>
    <textarea rows="1" cols="80" type="text" name="titre" ></textarea><br><br>
    <label for="titre">Texte de l'article :</label> <br><br>
    <textarea rows="15" cols="80" name="texte"> </textarea><br><br>
    <input type="hidden" name="commande" value="CreationArticle"/>
    <input type="submit" value="Sauvegarder"/>
</form>
<?php
    if(isset($donnees["messageErreur"]))
        echo "<p>" . $donnees["messageErreur"] . "</p>";
?>  