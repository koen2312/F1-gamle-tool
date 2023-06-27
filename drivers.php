<html>
    <head>
        <?php include "include/head.php"; 
        ?>
    </head>
    <body>
        <header>
            <link rel="stylesheet" href="css/drivers.css">
            <?php include "include/header.php"; ?>
        </header>
        <?php
            if(isset($_POST["order"])){
                DriverManager::searchOption($_GET["order"]);
                header("location:drivers?order=".$_GET['order']);
            }
            $drivers = DriverManager::searchOption(
                htmlspecialchars($_GET["order"])
            );
        ?>
        <main>
            <form action="drivers" method="get" id="filter_form_drivers">
                <label for="order">Filter:&nbsp;</label>
                <select class="browser-default custom-select mb-4 textDarkColor" name="order" id="order" onchange="order()">
                    <option value="" disabled>Kies een optie</option>
                    <?php
                        switch($_GET["order"]){
                            default:
                            case "name":
                                echo "<option value=\"name\" selected>Zoek op naam</option>";
                                echo "<option value=\"country\">Zoek op land</option>";
                                echo "<option value=\"number\">Zoek op nummer</option>";
                                break;
                            case "country":
                                echo "<option value=\"name\">Zoek op naam</option>";
                                echo "<option value=\"country\" selected>Zoek op land</option>";
                                echo "<option value=\"number\">Zoek op nummer</option>";
                                break;
                            case "number":
                                echo "<option value=\"name\">Zoek op naam</option>";
                                echo "<option value=\"country\">Zoek op land</option>";
                                echo "<option value=\"number\" selected>Zoek op nummer</option>";
                                break;
                            case "":
                                header("location:drivers?order=name");
                        }
                    ?>
                </select>
            </form>
            <script>
                $("#order").change(function(){
                    $("#filter_form_drivers").submit();
                });
            </script>
            <div class="drivers">
                <?php
                    foreach($drivers as $driver){
                        echo "<div class='driver'>";
                        echo "<div class='driverL'>";
                        if(file_exists("images/$driver->drivername.png")){
                            echo "<img src='images/$driver->drivername.png' alt='$driver->familyName'>";
                        } else{
                            echo "<img src='pfp/pictures/user_profile.png' alt='$driver->familyName'>";
                        }
                        echo "<div class='titleFont num'>$driver->permanentNumber</div>";
                        echo "</div>";
                        echo "<div class='driverR'>";
                        echo "<div class='titleFont name'>$driver->givenName <br> $driver->familyName</div> <hr class='line'>";
                        echo "<div class='info'>";
                        echo "$driver->dateOfBirth <br>";
                        echo "$driver->nationality";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
        </main>
        <footer>
            <?php include "include/footer.php"; ?>
        </footer>
    </body>
</html>