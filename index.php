<?php
    try {
        //Database connection
        $manager = new MongoDB\Driver\Manager('mongodb+srv://Melinna_agdl:melinna@cluster0.rd11o.mongodb.net/test?authSource=admin&replicaSet=atlas-3vwaqm-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true');        
        $query = new MongoDB\Driver\Query([]); // getting the collection from Planning database
        $result = $manager->executeQuery('Planning.dates', $query);  
        $result = $result->toArray();

    } catch (\Throwable $e) {
        echo("Erreur connexion");
        echo $e->getMessage();    
    }
    
?>
