<?php
    class ConstrucotorsManager{
        public static function select(){
            global $con;
            $stmt = $con->prepare("SELECT * FROM construcotors");
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_OBJ);
        }
        public static function insert(){
            global $con;
            $number = 0;

            $data = file_get_contents('http://ergast.com/api/f1/'.$number .'/constructors.json');
            $jsonObject = json_decode($data);
            $jsonArray = $jsonObject->MRData->RaceTable->Races[0]->QualifyingResults;

            foreach ($jsonArray as $jsonItem){
                $stmt = $con->prepare("INSERT INTO `f1_db`.`construcotors` (`season`, `constructorId`, `url`, `name`, `nationality`, `drivers_IDdrivers`)
                VALUES (?, ?, ?, ?, ?, ?);");
                $stmt->bindValue(1,$jsonItem->season);
                $stmt->bindValue(2,$jsonItem->constructorId);
                $stmt->bindValue(3,$jsonItem->url);
                $stmt->bindValue(4,$jsonItem->name);
                $stmt->bindValue(5,$jsonItem->nationality);
                
            }
        }
        
    }
?>