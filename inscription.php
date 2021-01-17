<?php
$name = $_POST["name"];
$username = $_POST["username"];
$password = $_POST["password"];

if (empty($name) && empty($username) && empty($password)) {
    $res2 = "";
} 
else {
    if (empty($name) || empty($username) || empty($password)) {
        $res2 = "Veuillez remplir tous les champs";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        
        //Database connection
        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');
        try {
            //Check if the username is not already used
            $filter = ['username' => $username];
            $option = [];
            $read = new MongoDB\Driver\Query($filter, $option);
            $cursor = $manager->executeQuery('Planning.Users', $read);
        } catch (MongoDB\Driver\Exception\Exception $e) {
            echo "Probleme! : " . $e->getMessage();
            exit();
        }

        foreach ($cursor as $user) {
            $userExist = $user ? true : false;
        }

        if ($userExist) {
           $res2 = "Ce username est déjà utilisé";
        } else {
            $newUser = [
                "name" => $name,
                "username" => $username,
                "password" => $password_hashed,
            ];
            try {
                $single_insert = new MongoDB\Driver\BulkWrite();
                $single_insert->insert($newUser);
        
                $manager->executeBulkWrite('Planning.Users', $single_insert) ;
                $res2 = "Compte créé avec succès";
            } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
                echo $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="connexion.css" media="screen" type="text/css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" 
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" 
        crossorigin="anonymous"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <div id="container" class="container">

        <form action="" method="POST">

            <h1>Inscription</h1>
            <label><b>Nom</b></label>
            <input type="text" placeholder="Entrer votre nom" name="name" value="patrick">
            
            <label><b>Nom d'utilisateur</b></label>
            <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" value="Patrick">
            
            <label><b>Mot de passe</b></label>
            <input type="password" placeholder="Entrer le mot de passe" name="password" >

            <input type="submit" id='signin' value="s'inscrire">
            <?php echo "<span style= color:blue >".$res2."</span><br/><br/>"?>
        </form>
        <br/><br/>
        <form action="connexion.php" method="POST">
            <h3> Retourner à la page de connexion ?</h3><br/>
            <input type='submit' id='' value='Retour'>
        </form>
            
    </div>
    </div>  
    
    
</body>
</html>