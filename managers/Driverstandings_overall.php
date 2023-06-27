<?php
    class Driverstandings_overall{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM driverstandings_overall");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);

        }
        public static function join(){
            global $con;
            $stmt = $con->prepare("SELECT driverstandings_overall.points, driverstandings_overall.wins, driverstandings_overall.position, drivers.familyName
                FROM driverstandings_overall 
                join drivers on driverstandings_overall.Drivers_idDrivers = drivers.idDrivers 
                join race on  driverstandings_overall.race_idRace = race.IDrace;");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function truncate(){
            global $con;

            $stmt = $con->prepare("TRUNCATE TABLE driverstandings_overall");
            $stmt ->execute();
            $stmt = $con->prepare("ALTER TABLE driverstandings_overall AUTO_INCREMENT = 1");
            $stmt -> execute();
        }
        public static function update(){
            $idD = 1;
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/current/driverStandings.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->StandingsTable->StandingsLists[0]->DriverStandings;

            foreach($jsonArray as $jsonItem){
                $stmt = $con->prepare("SELECT idDriverStandings_overall FROM driverstandings_overall WHERE idDriverStandings_overall = ?");
                $stmt ->bindValue(1, $idD);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $id = strval($result->idDriverStandings_overall);

                $stmt = $con->prepare("SELECT IDdrivers FROM drivers where permanentNumber = ?");
                $stmt->bindValue(1, $jsonItem->Driver->permanentNumber);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $iddrivers = strval($result->IDdrivers);
                
                $stmt = $con->prepare("SELECT IDrace FROM race where `round` = ?");
                $stmt->bindValue(1, $jsonObject->MRData->StandingsTable->StandingsLists[0]->round);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $idrace = strval($result->IDrace);

                $stmt = $con-> prepare("UPDATE `f1_db`.`driverstandings_overall` SET 
                season = ?, `round` = ?, position = ?, points = ?, wins = ?, permanentNumber = ?, Drivers_idDrivers = ?, race_idRace = ?  WHERE (`idDriverStandings_overall` = ?);");
                $stmt->bindValue(1, $jsonObject->MRData->StandingsTable->StandingsLists[0]->season);
                $stmt->bindValue(2, $jsonObject->MRData->StandingsTable->StandingsLists[0]->round);
                $stmt->bindValue(3, $jsonItem->position);
                $stmt->bindValue(4, $jsonItem->points);
                $stmt->bindValue(5, $jsonItem->wins);
                $stmt->bindValue(6, $jsonItem->Driver->permanentNumber);
                $stmt->bindValue(7, $iddrivers);
                $stmt->bindValue(8, $idrace);
                $stmt->bindValue(9, $id);

                $stmt->execute();

                $idD ++;
            }
        }

        public static function insert(){
            $number = 1;
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/2022/'.$number .'/driverStandings.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->StandingsTable->StandingsLists[0]->DriverStandings;

            foreach($jsonArray as $jsonItem){
                $stmt = $con->prepare("SELECT IDdrivers FROM drivers where permanentNumber = ?");
                $stmt->bindValue(1, $jsonItem->Driver->permanentNumber);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $iddrivers = strval($result->IDdrivers);
                

                $stmt = $con->prepare("SELECT IDrace FROM race where `round` = ?");
                $stmt->bindValue(1, $jsonObject->MRData->StandingsTable->StandingsLists[0]->round);
                $stmt -> execute();
                $result = $stmt -> fetchObject();
                $idrace = strval($result->IDrace);

                $stmt = $con-> prepare("INSERT INTO driverstandings_overall (season, `round`, position, points, wins, permanentNumber, Drivers_idDrivers, race_idRace)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $jsonObject->MRData->StandingsTable->StandingsLists[0]->season);
                $stmt->bindValue(2, $jsonObject->MRData->StandingsTable->StandingsLists[0]->round);
                $stmt->bindValue(3, $jsonItem->position);
                $stmt->bindValue(4, $jsonItem->points);
                $stmt->bindValue(5, $jsonItem->wins);
                $stmt->bindValue(6, $jsonItem->Driver->permanentNumber);
                $stmt->bindValue(7, $iddrivers);
                $stmt->bindValue(8, $idrace);

                $stmt->execute();
            }
        }
    }


?>