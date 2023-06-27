<?php
    require_once "database/database.php";

    $data = file_get_contents('http://ergast.com/api/f1/current.json');
    $jsonObject = json_decode($data);
    $jsonArray = $jsonObject->MRData->RaceTable->Races;
    $select = $con->prepare("SELECT idRace FROM race");
    $select -> execute();
    $select -> fetchAll(PDO::FETCH_OBJ);

    foreach ($jsonArray as $jsonItem){
        $time = $jsonItem->time;
        $time = substr($time, 0,5);

        $timefirst = $jsonItem->FirstPractice->time;
        $timefirst = substr($timefirst,0,5);

        $timesecond = $jsonItem->SecondPractice->time;
        $timesecond = substr($timesecond,0,5);

        if (isset($jsonItem->ThirdPractice)){
            $thirdPractice = $jsonItem->ThirdPractice->date;

            $timethird = $jsonItem->ThirdPractice->time;
            $timethird = substr($timethird, 0,5);

            $sprint = "0000-00-00";
            $timesprint = "00:00";
        }else{
            $sprint = $jsonItem->Sprint->date;

            $timesprint = $jsonItem->Sprint->time;
            $timesprint = substr($timesprint, 0, 5);

            $thirdPractice = "0000-00-00";
            $timethird = "00:00";

        }
        $timequaly = $jsonItem->Qualifying->time;
        $timequaly = substr($timequaly, 0,5);

        // $stmt = $con->prepare("UPDATE `f1_db`.`race` SET `season` = '2023', `round` = '50', `raceName` = 'g', `circuitid` = 'g', 
        // `circuitName` = 'g', `country` = 'g', `race_date` = '2022-12-11', `race_time` = '12:10:00', `FirstPractice_date` = '2022-12-21', 
        // `FirstPractice_time` = '06:00:00', `SecondPractice_date` = '2002-12-11', `SecondPractice_time` = '12:01:00', `ThirdPractice_date` = '2002-11-12', 
        // `ThirdPractice_time` = '11:00', `Qualifying_date` = '2002-11-12', `Qualifying_time` = '12:10:00', `Sprint_date` = '0000-10-00', 
        // `Sprint_time` = '01:00' WHERE (`idRace` = '25')");

        //$stmt = $con -> prepare("INSERT INTO `f1_db`.`race` (`season`, `round`, `raceName`, `circuitid`, `circuitName`, `country`, `race_date`, `race_time`, `FirstPractice_date`, `FirstPractice_time`, `SecondPractice_date`, `SecondPractice_time`, `ThirdPractice_date`, `ThirdPractice_time`, `Qualifying_date`, `Qualifying_time`, `Sprint_date`, `Sprint_time`)
        //VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $jsonItem->season);
        $stmt->bindValue(2, $jsonItem->round);
        $stmt->bindValue(3, $jsonItem->raceName);
        $stmt->bindValue(4, $jsonItem->Circuit->circuitId);
        $stmt->bindValue(5, $jsonItem->Circuit->circuitName);
        $stmt->bindValue(6, $jsonItem->Circuit->Location->country);
        $stmt->bindValue(7, $jsonItem->date);
        $stmt->bindValue(8, $time);
        $stmt->bindValue(9, $jsonItem->FirstPractice->date);
        $stmt->bindValue(10, $timefirst);
        $stmt->bindValue(11, $jsonItem->SecondPractice->date);
        $stmt->bindValue(12, $timesecond);
        $stmt->bindValue(13, $thirdPractice);
        $stmt->bindValue(14, $timethird);
        $stmt->bindValue(15, $jsonItem->Qualifying->date);
        $stmt->bindValue(16, $timequaly);
        $stmt->bindValue(17, $sprint);
        $stmt->bindValue(18, $timesprint);

        $stmt->execute();
    }
?>