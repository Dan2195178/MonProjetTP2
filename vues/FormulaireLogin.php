<form method="POST" class="FormLogin">
        <label for="user">Username :</label>
         <input type="text" name="user"/><br>
         <label for="pass">Password :</label>
         <input type="password" name="pass"/><br>
        <input type="submit" name="login" value="Login"/>
        <input type="hidden" name="commande" value="ValidationLogin"/>
</form>
<?php
    if(isset($messageErreur))
        echo "<p>$messageErreur</p>";
?>