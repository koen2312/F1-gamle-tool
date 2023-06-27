<?php
    class UpdateManager{
        public static function puntenupdate(){
            $now = date("Y-m-d",(strtotime("now")));
            $sun = date("Y-m-d",(strtotime("this sun")));
            if($now == $sun){
                PuntenManager::after();
            }
        }
        public static function update(){
            $now = date("Y-m-d",(strtotime("now")));
            $sun = date("Y-m-d",(strtotime("this sun")));
            $mon = date("Y-m-d",(strtotime("this mon")));
            require_once "include/autoloader.php";
            $nextRace = RaceManager::AankomendeRace();
            $qualirace = RaceManager::Qualirace();
            $race = RaceManager::select();
            $quali = QualifyingManager::select();
            $over = Driverstandings_overall::select();
            $last = LastraceManager::select();

            $nextRacearray = $nextRace->round - 1;
            $qualiRacearray = $qualirace->round - 1;

            if($now == $mon){
                if(strval($nextRacearray) !== $race[1]->round){
                    if($over[1]->round !== strval($nextRacearray)){
                        echo "Driverstandings update";
                        Driverstandings_overall::update();
                    }else{
                        echo "Driverstandings heeft al goede data";
                    };
                    if($last[1]->round !== strval($nextRacearray)){
                        echo "Lastrace update";
                        LastraceManager::update();
                    }else{
                        echo "Lastrace heeft al goede data";
                    };
                }else{
                    echo "Race database heeft geen round";
                };
            }else{
                echo "het is niet maandag";
            }

            if($now == $sun){
                echo "update quali";
                if(strval($nextRacearray) !== $race[1]->round){
                    if($quali[1]->round !== strval($qualiRacearray)){
                        echo "Qualifying update";
                        QualifyingManager::update($qualiRacearray);
                    }else{
                        echo "Qualifying heeft al goede data";
                    }
                }else{
                    echo "Race database heeft geen round";
                };
            }else{
                echo "het is niet zondag";
            }
        }
        public static function updatemanual(){
            $nextRace = RaceManager::AankomendeRace();
            $qualirace = RaceManager::Qualirace();
            $race = RaceManager::select();
            $quali = QualifyingManager::select();
            $over = Driverstandings_overall::select();
            $last = LastraceManager::select();

            $nextRacearray = $nextRace->round - 1;
            $qualiRacearray = $qualirace->round - 1;

            if(strval($nextRacearray) !== $race[1]->round){
                if($over[1]->round !== strval($nextRacearray)){
                    echo "Driverstandings update";
                    Driverstandings_overall::update();
                }else{
                    echo "Driverstandings heeft al goede data";
                };
                if($last[1]->round !== strval($nextRacearray)){
                    echo "Lastrace update";
                    LastraceManager::update();
                }else{
                    echo "Lastrace heeft al goede data";
                };
            }else{
                echo "Race database heeft geen round";
            };

            if(strval($nextRacearray) !== $race[1]->round){
                if($quali[1]->round !== strval($qualiRacearray)){
                    echo "Qualifying update";
                    QualifyingManager::update($qualiRacearray);
                }else{
                    echo "Qualifying heeft al goede data";
                }
            }else{
                echo "Race database heeft geen round";
            };
        }
        public static function updateAll(){
            global $con;
            $stmt = $con->prepare("SET FOREIGN_KEY_CHECKS = 0");
            $stmt -> execute();
            Driverstandings_overall::truncate();
            LastraceManager::truncate();
            DriverManager::truncate();
            QualifyingManager::truncate();
            RaceManager::truncate();
            $stmt = $con->prepare("TRUNCATE TABLE points");
            $stmt -> execute();

            $stmt = $con->prepare("SET FOREIGN_KEY_CHECKS = 1");
            $stmt -> execute();
            
            RaceManager::insert();
            DriverManager::insert();
            QualifyingManager::insert();
            LastraceManager::insert();
            Driverstandings_overall::insert();
        }
    }
    
?>