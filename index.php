<?php
        //Database connection
        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
        try {
            //Find an account with the $username
            $filter = ['username' => $username];
            $option = [];
            $read = new MongoDB\Driver\Query($filter, $option);
            $cursor = $manager->executeQuery('Planning.Users', $read);
            $cursor = $cursor->toArray();
        } 
        catch (MongoDB\Driver\Exception\Exception $e) {
            echo "Probleme! : " . $e->getMessage();
            exit();
        }
        echo "<pre>";
        
        foreach ( $cursor as $id => $value )
                {
                    echo "$id: ";
                    var_dump( $value );
                }
        echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Planning MongoDB Mel & Dadou</title>
</head>
<body>
    <div class="container">
        <div>
            <h1 align="center">Planning des corvées  d'épluchage</h1>
        </div>
        <form action="" method="post" align="center">
            <label for="year">Année :</label>
                <select name="year">
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                </select>
            
            <button type="submit" name="editYear">Changer d'année </button>

            <table border="1" align="center" style="margin-top: 30px;">
            <?php
                  
            ?>
            </table>

            <br>
            <button type="submit" name="updateTable">Valider le planning</button>
        </form>

        <div align="center">
            <h3>Statistiques par ordre croissant</h3>
            <?php
            // stats ordering system
            $usersStatsBis = array(
                "dadoucha" => $statDalinda,
                "melou" => $statmel,
            );
            
            asort($usersStatsBis);
            foreach($usersStatsBis as $i=>$userStats){
                echo $i." : ".$userStats."<br>";
            }
        ?>

        <?php
            $userName = $_POST['username'];
            $userPass = $_POST['password'];
    
            $user = $db->$collection->findOne(array('_id' => '5ff334baa626a0441d509a15',
                                                    'name'=>'dada' , 
                                                    'firstname'=>'dadou',
                                                    'username'=> 'dadoucha', 
                                                    'password'=> 'dada'));
    
            foreach ($user as $obj) {
                echo 'Username' . $obj['username'];
                echo 'password: ' . $obj['password'];
                if($userName == 'dadoucha' && $userPass == 'dada'){
                    echo 'found'  ;          
                }
                else{
                    echo 'not found'     ;       
                }
    
            }
        ?>
        </div>


    </div>
</body>
</html>
