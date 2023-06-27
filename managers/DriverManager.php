<?php

    class DriverManager{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM drivers");
            $stmt -> execute();
            
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function truncate(){
            global $con;
            $stmt = $con->prepare("TRUNCATE TABLE drivers");
            $stmt-> execute();
            $stmt = $con->prepare("ALTER TABLE drivers AUTO_INCREMENT = 1");
            $stmt -> execute();
        }
        public static function update(){
            global $con;
            $stmt=$con->prepare("SELECT IDdrivers from drivers");
            $stmt->execute();
            
            $data = file_get_contents('http://ergast.com/api/f1/2022/drivers.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->DriverTable->Drivers;

            foreach ($jsonArray as $jsonItem){
                $stmt = $con-> prepare("INSERT INTO drivers (drivername, permanentNumber, givenName, familyName, dateOfBirth, nationality)
                VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $jsonItem->driverId);
                $stmt->bindValue(2, $jsonItem->permanentNumber);
                $stmt->bindValue(3, $jsonItem->givenName);
                $stmt->bindValue(4, $jsonItem->familyName);
                $stmt->bindValue(5, $jsonItem->dateOfBirth);
                $stmt->bindValue(6, $jsonItem->nationality);
                
                $stmt->execute();
            }
        }
        public static function insert(){
            global $con;
            $data = file_get_contents('http://ergast.com/api/f1/2022/drivers.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->DriverTable->Drivers;

            foreach ($jsonArray as $jsonItem){
                $stmt = $con-> prepare("INSERT INTO drivers (drivername, permanentNumber, givenName, familyName, dateOfBirth, nationality)
                VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $jsonItem->driverId);
                $stmt->bindValue(2, $jsonItem->permanentNumber);
                $stmt->bindValue(3, $jsonItem->givenName);
                $stmt->bindValue(4, $jsonItem->familyName);
                $stmt->bindValue(5, $jsonItem->dateOfBirth);
                $stmt->bindValue(6, $jsonItem->nationality);
                
                $stmt->execute();
            }
        }
        public static function searchOption($order){
            global $con;

            $query = "SELECT * FROM drivers ";
            switch($order){
                case "name":
                    $query .= "ORDER BY drivername ASC ";
                    break;
                case "country":
                    $query .= "ORDER BY nationality ASC ";
                    break;
                case "number":
                    $query .= "ORDER BY permanentNumber ASC ";
                    break;
            }

            $stmt = $con->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
?>