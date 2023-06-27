<?php
    class QualifyingManager{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM qualifying");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function join(){
            global $con;
            $stmt = $con->prepare("SELECT qualifying.date, qualifying.time, qualifying.Q1, qualifying.Q2, qualifying.Q3,drivers.familyName
            FROM f1_db.qualifying
                join drivers on qualifying.Drivers_idDrivers = drivers.idDrivers 
                join race on qualifying.race_idRace = race.IDrace;");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function truncate(){
            global $con;
            $stmt = $con->prepare("TRUNCATE TABLE qualifying");
            $stmt-> execute();
            $stmt = $con->prepare("ALTER TABLE qualifying AUTO_INCREMENT = 1");
            $stmt -> execute();
        }
        public static function update($qualiRacearray){
            $idD = 1;
            $number = $qualiRacearray;
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/2022/'.$number .'/qualifying.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->RaceTable->Races[0]->QualifyingResults;

            foreach ($jsonArray as $jsonItem){
                $stmt = $con->prepare("SELECT IDqualifying FROM qualifying WHERE IDqualifying = ?");
                $stmt ->bindValue(1, $idD);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $id = strval($result->IDqualifying);

                $stmt = $con->prepare("SELECT IDdrivers FROM drivers where permanentNumber = ?");
                $stmt->bindValue(1, $jsonItem->Driver->permanentNumber);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $iddrivers = strval($result->IDdrivers);
                
                $stmt = $con->prepare("SELECT IDrace FROM race where `round` = ?");
                $stmt->bindValue(1, $jsonObject->MRData->RaceTable->Races[0]->round);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $idrace = strval($result->IDrace);

                $time = $jsonObject->MRData->RaceTable->Races[0]->time;
                $timeq = substr($time, 0,5);

                if(isset($jsonItem->Q1)){
                    $Q1 = $jsonItem->Q1;
                }else{
                    $Q1 = 0;
                }
                if(isset($jsonItem->Q2)){
                    $Q2 = $jsonItem->Q2;
                }else{
                    $Q2 = 0;
                }
                if(isset($jsonItem->Q3)){
                    $Q3 = $jsonItem->Q3;
                }else{
                    $Q3 = 0;
                }
                $stmt = $con-> prepare("UPDATE `f1_db`.`qualifying` SET 
                season = ?, `round` = ?, raceName = ?, `date` = ?, `time` = ?, `number` = ?, permanentNumber = ?, position = ?, Q1 = ?, Q2 = ?, Q3 = ?, Drivers_idDrivers = ?, race_idRace = ? WHERE (IDqualifying = ?);)");
                $stmt->bindValue(1, $jsonObject->MRData->RaceTable->Races[0]->season);
                $stmt->bindValue(2, $jsonObject->MRData->RaceTable->Races[0]->round);
                $stmt->bindValue(3, $jsonObject->MRData->RaceTable->Races[0]->raceName);
                $stmt->bindValue(4, $jsonObject->MRData->RaceTable->Races[0]->date);
                $stmt->bindValue(5, $timeq);
                $stmt->bindValue(6, $jsonItem->number);
                $stmt->bindValue(7, $jsonItem->Driver->permanentNumber);
                $stmt->bindValue(8, $jsonItem->position);
                $stmt->bindValue(9, $Q1);
                $stmt->bindValue(10, $Q2);
                $stmt->bindValue(11, $Q3);
                $stmt->bindValue(12, $iddrivers);
                $stmt->bindValue(13, $idrace);
                $stmt->bindValue(14, $id);
                
                $stmt->execute();

                $idD ++;
            }
        }

        public static function insert(){
            $number = 1;
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/2022/'.$number .'/qualifying.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->RaceTable->Races[0]->QualifyingResults;

            foreach ($jsonArray as $jsonItem){
                $stmt = $con->prepare("SELECT IDdrivers FROM drivers where permanentNumber = ?");
                $stmt->bindValue(1, $jsonItem->Driver->permanentNumber);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $iddrivers = strval($result->IDdrivers);
                
                $stmt = $con->prepare("SELECT IDrace FROM race where `round` = ?");
                $stmt->bindValue(1, $jsonObject->MRData->RaceTable->Races[0]->round);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $idrace = strval($result->IDrace);

                $time = $jsonObject->MRData->RaceTable->Races[0]->time;
                $timeq = substr($time, 0,5);

                if(isset($jsonItem->Q1)){
                    $Q1 = $jsonItem->Q1;
                }else{
                    $Q1 = 0;
                }
                if(isset($jsonItem->Q2)){
                    $Q2 = $jsonItem->Q2;
                }else{
                    $Q2 = 0;
                }
                if(isset($jsonItem->Q3)){
                    $Q3 = $jsonItem->Q3;
                }else{
                    $Q3 = 0;
                }

                $stmt = $con-> prepare("INSERT INTO qualifying (season, `round`, raceName, `date`, `time`, `number`, permanentNumber, position, Q1, Q2, Q3, Drivers_idDrivers, race_idRace)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $jsonObject->MRData->RaceTable->Races[0]->season);
                $stmt->bindValue(2, $jsonObject->MRData->RaceTable->Races[0]->round);
                $stmt->bindValue(3, $jsonObject->MRData->RaceTable->Races[0]->raceName);
                $stmt->bindValue(4, $jsonObject->MRData->RaceTable->Races[0]->date);
                $stmt->bindValue(5, $timeq);
                $stmt->bindValue(6, $jsonItem->number);
                $stmt->bindValue(7, $jsonItem->Driver->permanentNumber);
                $stmt->bindValue(8, $jsonItem->position);
                $stmt->bindValue(9, $Q1);
                $stmt->bindValue(10, $Q2);
                $stmt->bindValue(11, $Q3);
                $stmt->bindValue(12, $iddrivers);
                $stmt->bindValue(13, $idrace);
                
                $stmt->execute();
            };
        }
    }
?>