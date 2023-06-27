<?php
    class LastraceManager{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM driverstandings_lastrace");
            $stmt -> execute();
            
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function join(){
            global $con;
            $stmt = $con->prepare("SELECT driverstandings_lastrace.position, driverstandings_lastrace.points, 
            driverstandings_lastrace.grid, driverstandings_lastrace.`status`,driverstandings_lastrace.time, drivers.familyName
            FROM driverstandings_lastrace
                join drivers on driverstandings_lastrace.Drivers_idDrivers = drivers.idDrivers 
                join race on  driverstandings_lastrace.race_idRace = race.IDrace;");
            $stmt -> execute();
            
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function truncate(){
            global $con;

            $stmt = $con->prepare("TRUNCATE TABLE driverstandings_lastrace");
            $stmt-> execute();
            $stmt = $con->prepare("ALTER TABLE driverstandings_lastrace AUTO_INCREMENT = 1");
            $stmt -> execute();
        }
        public static function update(){
            $idD = 1;
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/current/last/results.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->RaceTable->Races[0]->Results;


            foreach ($jsonArray as $jsonItem){
                $stmt = $con->prepare("SELECT idDriverStandings_lastrace FROM driverstandings_lastrace WHERE idDriverStandings_lastrace = ?");
                $stmt ->bindValue(1, $idD);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $id = strval($result->idDriverStandings_lastrace);

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
                $timeL = substr($time, 0,5);

                if (isset($jsonItem->Time)){
                    $timeR = $jsonItem->Time->time;
                }else{
                    $timeR = "00:00";
                }

                $stmt = $con-> prepare("UPDATE `f1_db`.`driverStandings_lastrace` SET
                season = ?, `round` = ?, timerace = ?, permanentNumber = ?, position = ?, points = ?, grid = ?, `status` = ?, `time` = ?, Drivers_idDrivers = ?, race_idRace = ? WHERE (idDriverStandings_lastrace = ?)");
                $stmt->bindValue(1, $jsonObject->MRData->RaceTable->Races[0]->season);
                $stmt->bindValue(2, $jsonObject->MRData->RaceTable->Races[0]->round);
                $stmt->bindValue(3, $timeL);
                $stmt->bindValue(4, $jsonItem->Driver->permanentNumber);
                $stmt->bindValue(5, $jsonItem->position);
                $stmt->bindValue(6, $jsonItem->points);
                $stmt->bindValue(7, $jsonItem->grid);
                $stmt->bindValue(8, $jsonItem->status);
                $stmt->bindValue(9, $timeR);
                $stmt->bindValue(10, $iddrivers);
                $stmt->bindValue(11, $idrace);
                $stmt->bindValue(12, $id);
                
                $stmt->execute();

                $idD ++;
            }
        }
        public static function insert(){
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/current/last/results.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->RaceTable->Races[0]->Results;


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
                $timeL = substr($time, 0,5);

                if (isset($jsonItem->Time)){
                    $timeR = $jsonItem->Time->time;
                }else{
                    $timeR = "00:00";
                }

                $stmt = $con-> prepare("INSERT INTO driverstandings_lastrace (season, `round`,timerace, permanentNumber,position ,points, grid, `status`, `time`,Drivers_idDrivers, race_idRace)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $jsonObject->MRData->RaceTable->Races[0]->season);
                $stmt->bindValue(2, $jsonObject->MRData->RaceTable->Races[0]->round);
                $stmt->bindValue(3, $timeL);
                $stmt->bindValue(4, $jsonItem->Driver->permanentNumber);
                $stmt->bindValue(5, $jsonItem->position);
                $stmt->bindValue(6, $jsonItem->points);
                $stmt->bindValue(7, $jsonItem->grid);
                $stmt->bindValue(8, $jsonItem->status);
                $stmt->bindValue(9, $timeR);
                $stmt->bindValue(10, $iddrivers);
                $stmt->bindValue(11, $idrace);
                
                $stmt->execute();
            }
        }
    }

?>