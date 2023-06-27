<html>
    <head>
        <?php include "include/head.php";?>
        <link rel="stylesheet" href="css/profile.css"/>
        <?php
            // check of de get goed is ingevuld.
            // zo NIET dan ga je naar je eigen profiel.
            if(!isset($_GET["username"])){
                header("location:profile?username=" . $_SESSION["username"]);
            }else{
                if(! $_GET["username"]){
                    header("location:profile?username=" . $_SESSION["username"]);
                }else{
                    $gUInfo = userManager::selectOnUsernameGet($_GET["username"]);
                    if($gUInfo->username != $_GET["username"]){
                        header("location:profile?username=" . $_SESSION["username"]);
                    }
                }
            }
            if(isset($_POST["DPF"])){
                userManager::DeleteProfilePicture($gUInfo->idperson);
                header("location:profile?username=" . $_GET["username"]);
            }
        ?>
    </head>
    <body>
        <header>
            <?php include "include/header.php";?>
        </header>
        <main>
        <div class="vakLinks">
                <br/><br/>
        <?php
            
            echo "<div class=\"profielAfbeeldingKader\">";
            if(!file_exists("pfp/$gUInfo->profile_picture")){
                $gUInfoPFP = "pictures/user_profile_error.png";
            }else{
                $gUInfoPFP = $gUInfo->profile_picture;
            }
            echo "<img src=\"pfp/" . $gUInfoPFP . "\" class=\"profielAfbeelding\" >";
            echo "</div><br/>";
                if(isset($_POST["cPF"])){
                    $pfUpdate = userManager::updateProfilePicture(
                        $gUInfo->profile_picture,
                        $_FILES['file'],
                        $gUInfo->idperson,
                        htmlspecialchars($_GET["username"])
                    );
                }
                echo "<p class='persoonsgegevens'>User: <span style=\"font-weight:500;\">$gUInfo->username</span></p>";
                echo "<p class='persoonsgegevens'>Name: <span style=\"font-weight:500;\">$gUInfo->firstname $gUInfo->lastname</span></p>";
                echo "<p class='persoonsgegevens'>Email: <span style=\"font-weight:500;\">$gUInfo->email</span></p>";
                echo "<p class='persoonsgegevens'> Total Points: <span style=\"font-weight:500;\">$gUInfo->total_points</span></p>";
               
        ?>

                </div>
        <div class="vakRechts">
            
            <?php
            if($_SESSION["is_admin"] != 0 or $_GET["username"] == $_SESSION["username"]){
                echo "<button class='btn m-3' id='buttonChangeProfilePoints' onclick='handleButtonChangeProfile()'>Change profile</button>";
            }
                if(isset($_POST["username"])){
                    userManager::updateUserData($_POST, $gUInfo->idperson);
                    header("location: profile.php");
                    var_dump($_POST);

                }
            ?>
            <div id="formProfile">
            <form class="formProfile"  method="POST">
                User:<br/>
                <input type="text" value=<?php echo "$gUInfo->username"?> name="username" maxlength="20" required><br/><br/>
                firstname:<br/>
                <input type="text" value=<?php echo "$gUInfo->firstname"?> name="firstname" maxlength="20" required><br/><br/>
                lastname:<br/>
                <input type="text" value=<?php echo "$gUInfo->lastname"?> name="lastname" maxlength="20" required><br/><br/>
                email:<br/>
                <input type="text" value=<?php echo "$gUInfo->email"?> name="email" maxlength="20" required><br/><br/>
                Password:<br/>
                <input type="password" name="password" maxlength="20" minlength="5" required><br/><br/>
            
                <input type="submit" value="Change" class="btn" id="submitForm"><br/><br/><br/><br/>
                
            </form>
            
                <?php
                    echo "<form method=\"POST\" enctype=\"multipart/form-data\" class=\"buttonsCentrerenDiv\">";
                    echo "<input type=\"file\" name=\"file\" class=\"buttonChooseFile\">";
                    echo "<input type=\"submit\" name=\"cPF\" value=\"Change profile picture\">";
                    if($gUInfoPFP != "pictures/user_profile.png"){
                        echo "<input type=\"submit\" name=\"DPF\" value=\"Delete profile picture\">";
                    }
                    echo "</form>";
                ?>
            </div>
            <table class="table table-striped" id="tablePoints">
                <thead class="table-dark">
                    <th>Points</th>
                    <th>Race</th>
                </thead>
                <tbody>
                    <?php
                    $points = PuntenManager::getall($gUInfo->idperson);
                        foreach($points as $point){
                            echo "<tr>";
                            echo "<td>$point->point</td>";
                            echo "<td>$point->race</td>";
                            echo "<tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        </main>
        <script>
            var page = 0;
            function handleButtonChangeProfile(){
                if(page == 0){
                    document.getElementById("formProfile").style.display = "block";
                    document.getElementById("tablePoints").style.display = "none";
                    document.getElementById("buttonChangeProfilePoints").innerHTML = "My Points";
                    page = 1;
            } else{
                    document.getElementById("formProfile").style.display = "none";
                    document.getElementById("tablePoints").style.display = "table";
                    document.getElementById("buttonChangeProfilePoints").innerHTML = "Change profile";
                    page = 0;
            }
            }
       
        </script>
        <footer>
            <?php include "include/footer.php";?>
        </footer>
    </body>
</html>