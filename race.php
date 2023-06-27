<html>
    <head>
        <?php include "include/head.php"; ?>
        <link rel="stylesheet" href="css/race.css">
    </head>
    <body>
        <header>
            <?php include "include/header.php"; ?>
        </header>
        <main>
            <div class="race_info_container">
                <?php
                    // Check of de sesson key is ingevuld. Als de session key niet goed is mee gegeven/verkeerd dan is ID de ID van de aankomende race.
                    // update 6/6/2022 als je niet bent aangemeld dan kan je deze pagina niet zien. 
                    // en in de index worde $_session["race_id"] al ingevuld met nextRace.
                    $nextRace = RaceManager::AankomendeRace();
                    if(!isset($_SESSION["race_id"])){
                        $race = RaceManager::selectOnId($nextRace->IDrace);
                    }else{
                        $race = RaceManager::selectOnId($_SESSION["race_id"]);
                    }
                    $firstRaceId = RaceManager::eersteRace();
                    $lastRaceId = RaceManager::laatsteRace();
                    $time = RaceManager::tijdConverter($race->race_time);

                    echo "<div class=\"race_info_header text-center\">";
                    echo "  <h1>" . $race->raceName . "</h1>";
                    echo "  <p>";
                    echo "      Land: $race->country,";
                    echo "      Datum: $race->race_date,";
                    echo "      Tijd: $time";
                    echo "  </p>";
                    echo "  <form method=\"POST\">";
                                if($_SESSION["race_id"] == $firstRaceId->IDrace){
                                }else{
                    echo "      <input type=\"submit\" name=\"toLastRace\" class=\"material-symbols-outlined arrow_backward\" value=\"arrow_back\">";
                                }
                    echo "      <img src=\"images/" . $race->circuitName . ".png\">";
                                if($_SESSION["race_id"] == $lastRaceId->IDrace){
                                }else{
                    echo "      <input type=\"submit\" name=\"toNextRace\" class=\"material-symbols-outlined arrow_forward\" value=\"arrow_forward\">";
                                }
                    echo "  </form>";
                    echo "</div>";
                    if(isset($_POST["toLastRace"])){
                        $_SESSION["race_id"] --;
                        echo "<script>location.href='race'</script>";
                    }
                    if(isset($_POST["toNextRace"])){
                        $_SESSION["race_id"] ++;
                        echo "<script>location.href='race'</script>";
                    }
                ?>
               <div class="raceBlock">
                    <?php
                        $Drivers = DriverManager::select();
                        $LastRace = LastraceManager::select();
                        $placement = 1;
                        echo "<form method='POST' class='raceBet'>";
                        $racecheck = RaceManager::AankomendeRace();
                        if($racecheck->IDrace <= $_SESSION["race_id"]){
                            echo "<input class='submit' name='raceBet' type='submit' value='Lock in'></input>";
                        }
                        echo "<div class='raceGrid'>";
                            foreach($LastRace as $race){
                            echo "<div class='raceSegment'>";
                            echo "<select name='$placement'>";
                            echo "<option value='0' selected disabled>$placement</option>";
                            $placement ++;
                            foreach($Drivers as $Names){
                            echo "<option value='$Names->IDdrivers'>$Names->permanentNumber - $Names->givenName $Names->familyName</option>";
                            }
                            echo "</select>";
                            echo "<div class='raceExtend'></div>";
                            echo "</div>";
                            }
                        echo "</div>";
                        echo "</form>";
                        if(isset($_POST["raceBet"])){
                            $post = $_POST;
                            $delete = array_shift($post);
                            PuntenManager::before($post);
                            unset($_POST["raceBet"]);
                        }
                    ?>
                </div>
            </div>
        </main>
        <footer>
            <?php include "include/footer.php"; ?>
        </footer>
    </body>
</html>