<?php
    class PuntenManager{
        public static function getall($id){
            global $con;
            $stmt = $con->prepare("select sum(points.Points) as point, race.raceName as race, user.username from points join race on race.IDrace = points.race_idRace join user on user.idperson = points.user_idperson where user_idperson = ? group by race.raceName");
            $stmt->bindValue(1,$id);
            $stmt->execute();
            return $stmt->fetchall(PDO::FETCH_OBJ);
        }
        public static function before($post){
            global $con;

            date_default_timezone_set("Europe/Amsterdam");
            $nowdate = date("Y-m-d",(strtotime("now")));
            $nowtime = date("H",(strtotime("now")));
            //time
            $nextRace = RaceManager::lastRace();
            $user = $_SESSION["user_id"];
            $race = $_SESSION["race_id"];
            //wie user is
            $selectid = BetManager::selectID($user);
            $time = substr($nextRace->race_time,0,2);
            $bet = BetManager::select();

            if($bet->position == true and $bet->user_idperson == $user and $selectid->raceID == $race){
                foreach($post as $p){
                $stmt = $con->prepare("UPDATE `bet` SET position = ? WHERE (`user_idperson` = ?) and (`raceID` = ?)");
                $stmt->bindValue(1,$p);
                $stmt->bindValue(2,$user);
                $stmt->bindValue(3,$race);
                $stmt->execute();
                }
            }else{
                if($nextRace->race_date == $nowdate){
                //kijken of de datum en tijd kloppen
                if($time == $nowtime){
                    echo "Je mag geen punten in zetten";
                }else{
                    $id = 1;
                    foreach($post as $p){
                    $stmt = $con -> prepare("INSERT INTO bet (`position`, `driverID`, `raceID`, `user_idperson`) VALUES (?, ?, ?, ?);");
                    $stmt->bindValue(1,$p);
                    $stmt->bindValue(2,$id);
                    $stmt->bindValue(3,$race);
                    $stmt->bindValue(4,$user);
                    $stmt->execute();

                    $id++;
                    }
                }
                }else{
                    $id = 1;
                    foreach($post as $p){
                    $stmt = $con -> prepare("INSERT INTO bet (`position`, `driverID`, `raceID`, `user_idperson`) VALUES (?, ?, ?, ?);");
                    $stmt->bindValue(1,$p);
                    $stmt->bindValue(2,$id);
                    $stmt->bindValue(3,$race);
                    $stmt->bindValue(4,$user);
                    $stmt->execute();

                    $id++;
                    }
                }
            }
        }
        public static function after(){
            global $con;
            $user = userManager::select();
            foreach($user as $U){
                $betstandings = BetManager::selectstandingsID($U->idperson);
                $lastRaceId = RaceManager::lastRace();
                $points = 0;
                $totalpoints = 0;
                foreach($betstandings as $bet){
                    //of je al iets hebt ingevuld
                        if($bet->raceID == $lastRaceId->IDrace){
                            if($bet->position == $bet->drivers_ending_position){
                            //punten of het geleijk is
                            $points = 3;
                            }else{
                            //punten mogen niet de min in gaan
                            $different = abs($bet->drivers_ending_position - $bet->position);
                            $points = 3 - $different;
                            if(strpos($points, "-") !== false) {
                                $points = 0;
                            }
                            }
                        $totalpoints = $points + $totalpoints;
                        //total points is array
                        //var_dump($id);
                        $id = $betstandings[0]->user_idperson;
                    }
                }
                if(isset($id)){
                    $userid = userManager::selectOnId($id);

                    if($totalpoints != 0){
                        //als user al wat heeft ingevuld
                        if($userid == false){
                            $databasepoints = 0;
                        }else{
                            $databasepoints = $userid->total_points + $totalpoints;
                        }
                        $stmt = $con->prepare("UPDATE user SET `total_points` = ? WHERE (`idperson` = ?);");
                        $stmt->bindValue(1, $databasepoints);
                        $stmt->bindValue(2, $id);
                        $stmt->execute();
                        if($userid == false){
                            $totalpoints = 0;
                        }
                        $stmt = $con->prepare("INSERT INTO points (Points, race_idRace, user_idperson) VALUES (?, ?, ?)");
                        $stmt->bindValue(1, $totalpoints);
                        $stmt->bindValue(2, $bet->raceID);
                        $stmt->bindValue(3, $U->idperson);
                        $stmt->execute();
                    }
                }
            }
        }
    }
?>