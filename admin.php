<html>
    <head>
        <?php 
            error_reporting(0);
            include "include/head.php";

            if($_SESSION["is_admin"] === "0"){
                header('Location:index');
                exit;
            }
        ?>
        <link rel="stylesheet" href="css/admin.css">
        <script>
            function edit_user(id){
                var x = document.getElementById("form-" + id);
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            };
            $(document).ready(function(){
                $("input#usernameUpdate").on({
                    keydown: function(e) {
                        if (e.which === 32)
                        return false;
                    },
                    change: function() {
                        this.value = this.value.replace(/\s/g, "");
                    }
                });
            });
        </script>
    </head>
    <body>
        <header>
            <?php include "include/header.php"; ?>
            <link rel="stylesheet" href="css/admin.css">
        </header>
        <main>
            <div class="user_container">
                <form method="POST">
                    <input value="Data manual update" name="data" type="submit"/>
                    <input value="Update na season" name="season" type="submit"/>
                    <input value="Punten berekenen" name="punten" type="submit"/>
                </form>
                <div class="inner_user">
                    <?php
                        if(isset($_POST["season"])){
                            UpdateManager::updateAll();
                            header("location:admin?status=".$_GET['status']. "&order=".$_GET['order']);
                        }
                        if(isset($_POST["data"])){
                            UpdateManager::updatemanual();
                            header("location:admin?status=".$_GET['status']. "&order=".$_GET['order']);
                        }
                        if(isset($_POST["punten"])){
                            PuntenManager::after();
                            header("location:admin?status=".$_GET['status']. "&order=".$_GET['order']);
                        }
                        // var_dump($_GET["status"]);
                        // var_dump($_GET["order"]);
                        include "adminChange.php";
                        $select = userManager::selectOnAdmin(
                            htmlspecialchars($_GET["status"]), 
                            htmlspecialchars($_GET["order"]),
                            htmlspecialchars($_GET["username"])
                        );
                        echo "<form method=\"POST\">";
                        foreach($select as $allUsers){
                            switch($allUsers->is_admin){
                                case "0":
                                    echo "<div class=\"user_card user_id_" . $allUsers->idperson . "\" style=\"background: #b984f5;\">";
                                    break;
                                case "1":
                                    echo "<div class=\"user_card user_id_" . $allUsers->idperson . "\" style=\"background: #EEE8AA;\">";
                                    break;
                                case "2":
                                    echo "<div class=\"user_card user_id_" . $allUsers->idperson . "\" style=\"background: #de3c31;\">";
                                    break;
                            }
                                echo "<div class=\"ucPicture\">";
                                    if(!file_exists("pfp/$allUsers->profile_picture")){
                                        $UserPFP = "pictures/user_profile_error.png";
                                    }else{
                                        $UserPFP = $allUsers->profile_picture;
                                    }
                                    echo "<img src=\"pfp/" . $UserPFP . "\">";
                                echo "</div>";
                                echo "<div class=\"ucName\">";
                                    echo "<h2>Username = <input required type=\"text\" id=\"usernameUpdate\" name=\"usernameUpdate$allUsers->username\" value=\"$allUsers->username\" style=\"width:150px;\" maxlength=\"20\"></h2>";
                                    echo "<h2>Naam = " . $allUsers->firstname . " " . $allUsers->lastname . "</h2>";
                                    echo "<h2>Email = " . $allUsers->email . "</h2>";
                                echo "</div>";
                                echo "<div class=\"ucPoints\">";
                                    echo "<h2>Points<span>" . $allUsers->total_points . "</span></h2>";
                                echo "</div>";
                                echo "<div class=\"ucIsAdmin\">";
                                    echo "<h2>Status = ";
                                            if($_SESSION["is_admin"] != "2" || $_SESSION["user_id"] == $allUsers->idperson){
                                                switch($allUsers->is_admin){
                                                    case "0":
                                                        echo "User";
                                                        break;
                                                    case "1":
                                                        echo "Moderator";
                                                        break;
                                                    case "2":
                                                        echo "Admin";
                                                        break;
                                                }
                                            }else{
                                                echo "<select class=\"browser-default custom-select mb-4 textDarkColor\" name=\"is_adminChange$allUsers->idperson\" id=\"is_adminChange" . $allUsers->idperson . "\" onchange=\"changeStatus" . $allUsers->idperson . "()\">";
                                                    switch($allUsers->is_admin){
                                                        case "0":
                                                            echo "<option value=\"\" disabled>Kies een optie</option>";
                                                            echo "<option value=\"0\" selected>User</option>";
                                                            echo "<option value=\"1\">Moderator</option>";
                                                            echo "<option value=\"2\">Admin</option>";
                                                            break;
                                                        case "1":
                                                            echo "<option value=\"\" disabled>Kies een optie</option>";
                                                            echo "<option value=\"0\">User</option>";
                                                            echo "<option value=\"1\" selected>Moderator</option>";
                                                            echo "<option value=\"2\">Admin</option>";
                                                            break;
                                                        case "2":
                                                            echo "<option value=\"\" disabled>Kies een optie</option>";
                                                            echo "<option value=\"0\">User</option>";
                                                            echo "<option value=\"1\">Moderator</option>";
                                                            echo "<option value=\"2\" selected>Admin</option>";
                                                            break;
                                                    }
                                                echo "</select>";
                                            }
                                    echo "</h2>";
                                    echo "<input type=\"submit\" name=\"changeUser$allUsers->idperson\" value=\"Pas aan\">";
                                    echo "<a href=\"profile?username=$allUsers->username\">Ga naar profiel</a>";
                                echo "</div>";
                            echo "</div><br/>";
                            if(isset($_POST["changeUser$allUsers->idperson"])){
                                if($_POST["is_adminChange$allUsers->idperson"] != NULL){
                                    $id_admin_num = intval($_POST["is_adminChange$allUsers->idperson"]);
                                }else{

                                    $id_admin_num = intval($allUsers->is_admin);
                                }
                                $dupelicateUsername_check = loginManager::selectUsernameInsert(strtolower($_POST["usernameUpdate$allUsers->username"]));
                                if(strtolower($dupelicateUsername_check->username) == strtolower($_POST["usernameUpdate$allUsers->username"])){
                                    if($dupelicateUsername_check->idperson !== $allUsers->idperson){
                                    echo "<script> alert(\"" . htmlspecialchars($_POST["usernameUpdate$allUsers->username"]) . " wordt al gebruikt. Kies een andere username!\") </script>";
                                    }else{
                                        userManager::updateAsAdmin(
                                            htmlspecialchars($_POST["usernameUpdate$allUsers->username"]),
                                            intval($id_admin_num),
                                            $allUsers->idperson,
                                            htmlspecialchars($_GET["search"])
                                        );
                                        echo "<script>window.location.href = \"admin?status=". $_GET['status'] . "&order=" . $_GET['order'] . "\"</script>";
                                    }
                                }else{
                                    userManager::updateAsAdmin(
                                        htmlspecialchars($_POST["usernameUpdate$allUsers->username"]),
                                        intval($id_admin_num),
                                        $allUsers->idperson,
                                        htmlspecialchars($_GET["search"])
                                    );
                                    echo "<script>window.location.href = \"admin?status=". $_GET['status'] . "&order=" . $_GET['order'] . "\"</script>";
                                }
                            }
                        }
                        echo "</form>";
                    ?>
                </div>
            </div>
        </main>
        <footer>
            <?php include "include/footer.php"; ?>
        </footer>
    </body>
</html>