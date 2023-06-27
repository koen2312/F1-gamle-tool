<?php
    class RaceManager{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM race");
            $stmt -> execute();
            
            return $stmt -> fetchAll(PDO::FETCH_OBJ);

        }
        public static function selectOnId($id){
            global $con;
            $stmt = $con->prepare("SELECT * FROM race where IDrace = ? ");
            $stmt->bindValue(1, $id);
            $stmt -> execute();
            
            return $stmt->fetchObject();

        }
        public static function AankomendeRace(){
            global $con;
            $dateToday = date("Y-m-d");

            $stmt = $con->prepare("SELECT * FROM race where race_date > ? limit 1 ");
            $stmt->bindValue(1, $dateToday);
            $stmt -> execute();

            return $stmt->fetchObject();
        }
        public static function tijdConverter($time){
            $timestring = substr($time ,0,2);
            $time = substr($time ,2,3);
            $timestring = $timestring + 2;
            $time= $timestring . $time;
            return $time;
        }
        public static function Qualirace(){
            global $con;
            $dateToday = date("Y-m-d");

            $stmt = $con->prepare("SELECT * FROM race where Qualifying_date > ? limit 1 ");
            $stmt->bindValue(1, $dateToday);
            $stmt -> execute();

            return $stmt->fetchObject();
        }
        public static function eersteRace(){
            global $con;

            $stmt = $con->prepare("SELECT * FROM race order by IDrace ASC limit 1 ");
            $stmt -> execute();

            return $stmt->fetchObject();
        }
        public static function laatsteRace(){
            global $con;

            $stmt = $con->prepare("SELECT * FROM race order by IDrace DESC limit 1 ");
            $stmt -> execute();

            return $stmt->fetchObject();
        }
        public static function truncate(){
            global $con;

            $stmt = $con->prepare("TRUNCATE TABLE race");
            $stmt-> execute();
            $stmt = $con->prepare("ALTER TABLE race AUTO_INCREMENT = 1");
            $stmt -> execute();
        }
        public static function insert(){
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/current.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->RaceTable->Races;

            foreach ($jsonArray as $jsonItem){
                $time = $jsonItem->time;
                $time = substr($time, 0,5);
                // tijd goed zetten en in de database zetten
                $timefirst = $jsonItem->FirstPractice->time;
                $timefirst = substr($timefirst,0,5);
        
                $timesecond = $jsonItem->SecondPractice->time;
                $timesecond = substr($timesecond,0,5);

                //of third practice in de api gevuld is ja of nee
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
                
                $stmt = $con -> prepare("INSERT INTO `f1_db`.`race` (`season`, `round`, `raceName`, `circuitid`, `circuitName`, `country`, `race_date`, `race_time`, `FirstPractice_date`, `FirstPractice_time`, `SecondPractice_date`, `SecondPractice_time`, `ThirdPractice_date`, `ThirdPractice_time`, `Qualifying_date`, `Qualifying_time`, `Sprint_date`, `Sprint_time`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
        }
        public static function lastRace(){
            $aankomend = RaceManager::AankomendeRace();
            $last = $aankomend->IDrace - 1;
            
            global $con;
            $stmt = $con->prepare("SELECT * FROM race where IDrace = ? ");
            $stmt->bindValue(1, $last);
            $stmt -> execute();
            
            return $stmt->fetchObject();
        }
    }

?>