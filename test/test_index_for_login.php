<?php
    include "../database/database.php";
    session_start();

    include "../managers/userManager.php";
    $pf = userManager::selectOnId(
        $_SESSION["user_id"]
    );

    var_dump($pf->idperson);
    var_dump($_SESSION["user_id"]);

    if(! $_SESSION["user_id"]){
        header("location:login.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            echo "<div style=\"width:150px;height:150px;border:solid 5px #28282B;border-radius:50%;overflow:hidden;background:#28282B;\">";
                echo "<img src=\"../profile/" . $pf->profile_picture . "\" style=\"height:150px;display:block;margin-left:auto;margin-right:auto;\">";
            echo "</div>";
        ?>
        <br/>
        <?php
            if(isset($_POST["cPF"])){
                $pfUpdate = userManager::updateProfilePicture(
                    $pf->profile_picture,
                    $_FILES['file'],
                    $_SESSION["user_id"]
                );
            }
            echo "<form method=\"POST\" enctype=\"multipart/form-data\">";
                echo "<input type=\"file\" name=\"file\"><br/>";
                echo "<input type=\"submit\" name=\"cPF\" value=\"change profile picture\">";
            echo "</form>";
        ?>
        <a href="../login.php">go back to login</a>
    </body>
</html>