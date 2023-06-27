<?php
    class BetManager{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM bet");
            $stmt->execute();
            return $stmt->fetchObject();
        }
        public static function selectID($id){
            global $con;
            $stmt = $con->prepare("SELECT * FROM bet where user_idperson = ?");
            $stmt->bindValue(1,$id);
            $stmt->execute();
            return $stmt->fetchObject();
        }
        public static function selectstandingsID($id){
            global $con;
            $stmt = $con->prepare("SELECT
                idBet,
                position,
                (SELECT position FROM driverstandings_lastrace WHERE driverstandings_lastrace.Drivers_idDrivers = bet.driverID) as drivers_ending_position,
                driverID,
                raceID,
                user_idperson
            FROM bet where user_idperson = ?");
            $stmt->bindValue(1,$id);
            $stmt->execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function selectstandings(){
            global $con;
            $stmt = $con->prepare("SELECT
                idBet,
                position,
                (SELECT position FROM driverstandings_lastrace WHERE driverstandings_lastrace.Drivers_idDrivers = bet.driverID) as drivers_ending_position,
                driverID,
                raceID,
                user_idperson
            FROM bet");
            $stmt->execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
    }
?>                  