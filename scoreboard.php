<html>
    <head>
        <?php include "include/head.php"; 
        $drivers = Driverstandings_overall::join();
        $last = LastraceManager::join();
        $qualifying = QualifyingManager::join();
        $users = userManager::select();
        ?>
        <link rel="stylesheet" href="css/scoreboard.css">
    </head>
    <body>
        <header>
            <?php include "include/header.php"; ?>
        </header>
        <main>
            <div class="select">
                <div class="selectBlock" id="s1" onclick="scoreSelect(1)">
                    Users
                </div>
                <div class="selectBlock" id="s2" onclick="scoreSelect(2)">
                    Standings
                </div>
                <div class="selectBlock" id="s3" onclick="scoreSelect(3)">
                    Last race
                </div>
                <div class="selectBlock" id="s4" onclick="scoreSelect(4)">
                    Qualifying
                </div>
            </div>
            <div class="selected" id="s">
                
            </div>
            <table class="table table-striped" id="t1">
                <thead class="table-dark">
                    <th>Username</th>
                    <th>Points</th>
                </thead>
                    <tbody>
                        <?php
                            foreach($users as $user){
                                echo "<tr>";
                                    echo "<td>$user->firstname $user->lastname</td>";
                                    echo "<td>$user->total_points</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
            </table>
            <div>
            </div>
            <table class="table table-striped" id="t2">
                <!-- race overall -->
                <thead class="table-dark">
                    <th>Points</th>
                    <th>Wins</th>
                    <th>Position</th>
                    <th>Family name</th>
                </thead>
                    <tbody>
                        <?php
                            foreach($drivers as $driver){
                                echo "<tr>";
                                echo "<td>$driver->points</td>";
                                echo "<td>$driver->wins</td>";
                                echo "<td>$driver->position</td>";
                                echo "<td>$driver->familyName</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
            </table>
            <table class="table table-striped" id="t3">
                <!-- lastrace -->
                <div id="tLastRace1">
                    <?php 
                        $prev = RaceManager::lastRace();
                        echo "$prev->raceName, $prev->circuitName";
                    ?>
                </div>
                <thead class="table-dark">
                    <th>Position</th>
                    <th>Points</th>
                    <th>Grid</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Family name</th>
                </thead>
                    <tbody>
                        <?php
                            foreach($last as $L){
                                echo "<tr>";
                                echo "<td>$L->position</td>";
                                echo "<td>$L->points</td>";
                                echo "<td>$L->grid</td>";
                                echo "<td>$L->status</td>";
                                echo "<td>$L->time</td>";
                                echo "<td>$L->familyName</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
            </table>
            <table class="table table-striped" id="t4">
                <!-- qualifying -->
                <div id="tLastRace2">
                    <?php 
                        $prev = RaceManager::lastRace();
                        echo "$prev->raceName, $prev->circuitName";
                    ?>
                </div>
                <thead class="table-dark">
                    <th>Date</th>
                    <th>Time</th>
                    <th>Q1</th>
                    <th>Q2</th>
                    <th>Q3</th>
                    <th>Family name</th>
                </thead>
                    <tbody>
                        <?php
                            foreach($qualifying as $quali){
                                echo "<tr>";
                                echo "<td>$quali->date</td>";
                                echo "<td>$quali->time</td>";
                                echo "<td>$quali->Q1</td>";
                                echo "<td>$quali->Q2</td>";
                                echo "<td>$quali->Q3</td>";
                                echo "<td>$quali->familyName</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
            </table>
        </main>
        <footer>
            <?php include "include/footer.php"; ?>
        </footer>
    </body>
</html>