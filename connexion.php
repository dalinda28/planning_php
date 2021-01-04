<html>
    <head>
       <meta charset="utf-8">
        <!-- importer le fichier de style -->
        <link rel="stylesheet" href="connexion.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="container">
            <form action="index.php" method="POST">
                <h1>Connexion</h1>
                
                <label><b>Nom d'utilisateur</b></label>
                <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer le mot de passe" name="password" required>

                <input type="submit" id='submit' value='LOGIN' >
                <?php

                require('index.php');

                $db = $conn->Planning;
                $collection = $db->Users;

                $userName = $_POST['username'];
                $userPass = $_POST['userPassword'];


                $user = $db->$collection->findOne(array('username'=> 'dadoucha', 'password'=> 'dada'));

                foreach ($user as $obj) {
                    echo 'Username' . $obj['username'];
                    echo 'password: ' . $obj['password'];
                    if($userName == 'user1' && $userPass == 'pass1'){
                        echo 'found'   ;         
                    }
                    else{
                        echo 'not found'    ;        
                    }   
                }

                $user = $db->$collection->findOne(array('username'=> $username, 'password'=> $password));

                ?>
            </form>
        </div>
    </body>
</html>