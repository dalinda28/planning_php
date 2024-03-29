<?php
    session_start();
    if (isset($_POST['username']) and isset($_POST['password'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        
        //Connexion à la base de données
        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
        try {      
            $filter = ['username' => $username];
            $option = [];
            $read = new MongoDB\Driver\Query($filter, $option);
            $cursor = $manager->executeQuery('Planning.Users', $read);
        } 
        catch (MongoDB\Driver\Exception\Exception $e) {
            echo "Probleme! : " . $e->getMessage();
            exit();
        }
   
        $currUser=[];
        foreach ($cursor as $user) {
            $userExist = $user ? true : false;
            $currUser=$user;
        }

        if (!$userExist) {
            $res1 = "Ce username n'existe pas";
        }  
        else {
            $password_hashed = $currUser->password;
            if (!password_verify($password, $password_hashed)) {
                $res1 = "Mot de passe incorrect";
            }
            else {
            $_SESSION["id"] = $currUser->_id;
            $_SESSION["username"] = $currUser->username;     

            header('Location: index.php');       
            die();
            }
        }
    }

?>
<html>
    <head>
       <meta charset="utf-8">
        <!-- importer le fichier de style -->
        <link rel="stylesheet" href="connexion.css" media="screen" type="text/css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" 
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" 
        crossorigin="anonymous"> 
    </head>
    <body>
        <div id="container">
            <form action="" method="POST">
                <h1>Connexion</h1>

                <label><b>Nom d'utilisateur</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" >
                
                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" >

                <input type="submit" id='submit' value='Se connecter'>
                <?php echo "<span style= color:red >$res1</span><br/><br/>"; ?>

            </form>
            <form action="inscription.php" method="POST">
                <h3>Pas de compte ?</h3>
                <input type="submit" id='submit' value="S'inscrire" name="inscription">
            </form>
        </div>
    </body>
</html>
