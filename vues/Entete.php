<html>
    <head>
        <meta charset='utf-8'>
        <title><?php if(isset($donnees["titre"])) echo $donnees["titre"];?></title>
         <style>
             input {
                 margin: 5px 5px;
             }
             .FormLogin {
                 margin-top: 20px;
             }
         </style>
    </head>
    <body>
        
        <ul>
          
        <?php
            if(!isset($_SESSION["usager"]))
            {
        ?>
            <li><a href="index.php?commande=FormLogin">&#10132;S'authentifier<a>
              
        <?php        
            }
            else
            {
        ?>        
            <li><a href="index.php?commande=Logout">&#11013;Logout<a>
            
            
        <?php
            }
        ?>    
       