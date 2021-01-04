<?php
        //Database connection
        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
        try {
        $filter = [];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        $cursor = $manager->executeQuery("Planning.Users", $read);
        } catch (MongoDB\Driver\Exception\Exception $e) {
            echo "Probleme! : " . $e->getMessage();
            exit();
        }
        echo "<pre>";
        
        foreach ($cursor as $Users) {
            var_dump($Users);
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
            
            <button type="submit" name="editYear">Année</button>

            <table border="1" align="center" style="margin-top: 30px;">

            </table>

            <br>
            <button type="submit" name="updateTable">Valider le planning</button>
        </form>

        <div align="center">
            <h3>Statistiques par ordre croissant</h3>
        </div>
    </div>
</body>
</html>
