<?php
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['username'])) {
    header('Location: connexion.php');
    die();
}

//Database connection
$manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');    

//Si la page a été chargée après un changement d'année via le select: $currYear désigne l'année que le planning va afficher
if (isset($_POST["isFormSend"])){
    $currYear=(int)$_POST["year"];
}
//Par défaut afficher le planning de 2021
else {
    $currYear=2021;
}

//Si la page a été chargée après la validation du planning, on actualise les valeurs de la bdd
if (isset($_POST["updateTable"])) {
    $year=(int)$_POST["year"]; 
    $currYear=$year;

    for ($i = 1; $i <= 52; $i++) {
        $currUser=$_POST["eplucheur$i"];
        try {
            // update
            $updates = new MongoDB\Driver\BulkWrite();
            $updates->update(
                        ['year' => $year, 'week' => $i],
                        ['$set' => ['user' => "$currUser"]],
                        ['multi' => true, 'upsert' => true]
                        );
            $result = $manager->executeBulkWrite('Planning.Weeks', $updates);   

        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" 
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" 
        crossorigin="anonymous"> 
        
    <title>Planning MongoDB Mel & Dadou</title>
</head>
<style>
.container {
	width: 950px;
	margin: 0 auto;
}
.Melinna {
	background-color: #FF9900;
}
.Dalinda {
	background-color: #00CCFF;
}
.Jean {
	background-color: #00FF33;
}
.Youssef {
	background-color: #FFFF00;
}
.Patrick {
	background-color: #FF3000;
}
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light row pl-5 pr-5">
        <p class="col-11">Bienvenue <strong><?php echo $_SESSION['username']; ?></strong> dans votre tableau de bord !</p>
        
        <a type="button" class="btn btn-success col-1 p-2" href="deconnexion.php">Déconnexion</a>
    </nav>
    <div class="container">
        <div>
            <h1 align="center">Planning des corvées d'épluchage</h1>

        </div>
        <form action="" name="form_year" method="post" align="center"> 
            <label for="year">Année :</label>
            <select id="selectYear" name="year">
                <option value="2019" name="year"<?php if ($currYear==2019):?> selected="selected" <?php endif;?>>2019</option>
                <option value="2020" name="year"<?php if ($currYear==2020):?> selected="selected" <?php endif;?>>2020</option>
                <option value="2021" name="year"<?php if ($currYear==2021):?> selected="selected" <?php endif;?>>2021</option>
            </select>
            <input type="hidden" name="isFormSend" value="">
        </form>
        <form action="" method="post" align="center">
            <input type="hidden" id="currYear" name="year" value="<?php echo $currYear?>">
            <table border="1" align="center" style="margin-top: 30px;">
                <?php
                    function firstDayOfWeek($week, $year){
                        $timeStampPremierJanvier = strtotime($year . '-01-01');
                        $jourPremierJanvier = date('w', $timeStampPremierJanvier);
                    
                        //Recherche du N° de semaine du 1er janvier
                        $numSemainePremierJanvier = date('W', $timeStampPremierJanvier);
                    
                        //Nombre à ajouter en fonction du numéro précédent
                        $decallage = ($numSemainePremierJanvier == 1) ? $week - 1 : $week;
                        //Timestamp du jour dans la semaine recherchée
                        $timeStampDate = strtotime('+' . $decallage . ' weeks', $timeStampPremierJanvier);
                        //Recherche du lundi de la semaine en fonction de la ligne précédente
                        $jourDebutSemaine = ($jourPremierJanvier == 1) ? date('d-m-Y', $timeStampDate) : date('d-m-Y', strtotime('last monday', $timeStampDate));
                    
                        //-- nombre à ajouter en fonction du numéro précédent ------------
                        $decallage = ($numSemainePremierJanvier == 1) ? $week - 1 : $week;
                        //-- timestamp du jour dans la semaine recherchée ----------------
                        $timeStampDate = strtotime('+' . $decallage . ' weeks', $timeStampPremierJanvier);
                        //-- recherche du lundi de la semaine en fonction de la ligne précédente ---------
                        $jourDebutSemaine = ($jourPremierJanvier == 1) ? date('d-m-Y', $timeStampDate) : date('d-m-Y', strtotime('last monday', $timeStampDate));
                        
                        return $jourDebutSemaine;
                    }

                    //On va remplir un array contenant tous les utilisateurs
                    $users_array = getAllUsers();

                    function getAllUsers(){
                        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
                        try {
                            $filter = [];
                            $option = [];
                            $read = new MongoDB\Driver\Query($filter, $option);
                            $cursor1 = $manager->executeQuery('Planning.Users', $read);
                        } 
                        catch (MongoDB\Driver\Exception\Exception $e) {
                            echo "Probleme! : " . $e->getMessage();
                            exit();
                        }
                        foreach ($cursor1 as $user) {
                            $users_array[]= $user->username;
                        }
                        return $users_array;
                    }

                    //On prélève toutes les infos de l'année sélectionnée (semaine=>éplucheur) et on les stocke dans un array
                    $date_array=getData($currYear);       
                    
                    function getData($year){
                        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
                        try {
                            $filter = ["year" => $year];
                            $option = [];
                            $read = new MongoDB\Driver\Query($filter, $option);
                            $cursor2 = $manager->executeQuery('Planning.Weeks', $read);
                        } 
                        catch (MongoDB\Driver\Exception\Exception $e) {
                            echo "Probleme! : " . $e->getMessage();
                            exit();
                        }

                        foreach ($cursor2 as $date) {
                            $date_array[$date->week] = $date->user;
                        }
                        return $date_array;
                    }

                    $week=0;
                    //On construit le tableau (planning) avec ses cellules
                    for ($i = 1; $i <= 13; $i++) {
                        echo "
                        <tr>
                        ";        
                        for ($j = 1; $j <= 4; $j++) {
                            $week = $week+1;
                            echo "<td>";
                            echo firstDayOfWeek($week, $currYear);
                            $currUser = $date_array[$week];
                            echo "</td>
                                <td> 
                                <select class=$currUser name='eplucheur".$week."'>";
                                echo "<option selected ='selected' value='personne' name='eplucheur".$week."'>personne</option>";                                foreach ($users_array as $user){
                                    echo "<option ";
                                    if ($user == $currUser){
                                        echo "selected ='selected' ";
                                    }
                                    echo "value='$user' name='eplucheur".$week."'>$user</option>";
                                }	                        
                            echo "</select> </td> ";
                        }
                        echo "</tr>";          
                    }
                
                ?>
            </table>

            <br>
            <button type="submit" name="updateTable">Valider le planning</button>
        </form>

        <div>
            <h2>Statistiques par ordre croissant</h2>
            <?php
                function statUser($user){
                    global $currYear;  

                    $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
                    try {
                        $filter = ["user" => $user, "year" => $currYear];
                        $option = [];
                        $read = new MongoDB\Driver\Query($filter, $option);
                        $cursor3 = $manager->executeQuery('Planning.Weeks', $read);
                    } 
                    catch (MongoDB\Driver\Exception\Exception $e) {
                        echo "Probleme! : " . $e->getMessage();
                        exit();
                    }
                    return iterator_count($cursor3);
                }
                
                echo "<ol>";
                foreach ($users_array as $user){
                    echo "<li> ".$user." : ".statUser($user)." </li><br>";
                }
                echo "</ol>";
            ?>
        </div>
        
    </div>
</body>
<script>
    window.addEventListener("load", changeYear);

    document.getElementById("selectYear").addEventListener("change", reload);

    function changeYear(){
        select = document.getElementById("selectYear");
		choice = select.selectedIndex;
        elmtSelected = select.options[choice].value;
        
        currYear = document.getElementById("currYear");
        currYear.value = elmtSelected;   
    }

    function reload(){
        document.forms['form_year'].submit();
    }
</script>
</html>