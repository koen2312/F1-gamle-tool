<?php
    $data = file_get_contents('http://ergast.com/api/f1/current.json');
    $jsonObject = json_decode($data);
    $jsonArray = $jsonObject->MRData->RaceTable->Races;

    $driver = file_get_contents('http://ergast.com/api/f1/2022/drivers.json');
    $jsonObjectdriver = json_decode($driver);
    $jsonArraydriver = $jsonObjectdriver->MRData->DriverTable->Drivers;

    foreach ($jsonArray as $jsonItem) {
        echo "<br>";
        echo "season ".$jsonItem->season;

        echo "<br>";
        echo "round ".$jsonItem->round;

        echo "<br>";
        echo "raceName ".$jsonItem->raceName;

        echo "<br>";
        echo "circuitid ".$jsonItem->Circuit->circuitId;

        echo "<br>";
        echo "circuitName ".$jsonItem->Circuit->circuitName;

        echo "<br>";
        echo "coutry ".$jsonItem->Circuit->Location->country;

        echo "<br>";
        echo "date ".$jsonItem->date;

        echo "<br>";
        $time = $jsonItem->time;
        $time = substr($time, 0,8);
        echo "time ".$time;

        echo "<br>";
        echo "firstpractice date ".$jsonItem->FirstPractice->date;

        echo "<br>";
        $timefirst = $jsonItem->FirstPractice->time;
        $timefirst = substr($timefirst,0, 8);
        echo "firstpractice time ".$timefirst;

        echo "<br>";
        echo "secondpractice date ".$jsonItem->SecondPractice->date;

        echo "<br>";
        $timesecond = $jsonItem->SecondPractice->time;
        $timesecond = substr($timesecond, 0, 8);
        echo "secondpractice time ".$timesecond;

        if (isset($jsonItem->ThirdPractice)){
            echo "<br>";
            echo "thirdpractice date ".$jsonItem->ThirdPractice->date;

            echo "<br>";
            $timethird = $jsonItem->ThirdPractice->time;
            $timethird = substr($timethird, 0,8);
            echo "thirdpractice time ".$timethird;
        }else{
            echo "<br>";
            echo "sprint date ".$jsonItem->Sprint->date;

            echo "<br>";
            $timesprint = $jsonItem->Sprint->time;
            $timesprint = substr($timesprint, 0, 8);
            echo "sprint time ".$timesprint;
        }
        
        echo "<br>";
        echo "qualifying date ".$jsonItem->Qualifying->date;

        echo "<br>";
        $timequaly = $jsonItem->Qualifying->time;
        $timequaly = substr($timequaly, 0,8);
        echo "qualifying time ".$timequaly;

        echo "<br>";
        echo "<br>";
    }
    foreach($jsonArraydriver as $jsonDriver){
        echo "<br>";
        echo "driver ID ".$jsonDriver->driverId;

        echo "<br>";
        echo "Number ".$jsonDriver->permanentNumber;

        echo "<br>";
        echo "Firstname ".$jsonDriver->givenName;

        echo "<br>";
        echo "Lastname ".$jsonDriver->familyName;

        echo "<br>";
        echo "Birthday ".$jsonDriver->dateOfBirth;

        echo "<br>";
        echo "Land ".$jsonDriver->nationality;
        echo "<br>";
        echo "<br>";

    }
?>