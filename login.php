<?php
    error_reporting(0);
    include "include/autoloader.php";
?>
<!doctype html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="css/loginstyle.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
                $("input#username").on({
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <main>
            <div class="loginLeft">
                <img src="images/logo.png" alt="logo">
            </div>
            <div class="loginRight">
            <?php
                            switch($_GET["form"]){
                                case "login":
                                case "":
                                    echo "<div class=\"login_form\">";
                                        echo "<h2 class=\"title\">Welkom</h2>";
                                        if(isset($_POST["login"])){
                                            $userInlog = loginManager::selectUserLogin(strtolower($_POST["email"]));

                                            if($userInlog->email != false){
                                                echo "<form method=\"POST\">";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"email\" name=\"email\" value=" . htmlspecialchars($_POST["email"]) . " maxlength=\"40\">";
                                                        echo "<span></span>";
                                                        echo "<label>Email</label>";
                                                    echo "</div>";
                                                    if(password_verify( $_POST["password"] , $userInlog->password)){
                                                        echo "<div class=\"field\">";
                                                            echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                            echo "<span></span>";
                                                            echo "<label style=\"color:green;\">Wachtwoord correct</label>";
                                                        echo "</div>";

                                                        $_SESSION["user_id"] = $userInlog->idperson;
                                                        $_SESSION["username"] = $userInlog->username;
                                                        $_SESSION["is_admin"] = $userInlog->is_admin;
                                                        
                                                        header("location:index");
                                                    }
                                                    else{
                                                        echo "<div class=\"field\">";
                                                            echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                            echo "<span></span>";
                                                            echo "<label style=\"color:red;\">Wachtwoord niet correct</label>";
                                                        echo "</div>";
                                                        
                                                    }
                                                    echo "<input type=\"submit\" name=\"login\" value=\"Login\">";
                                                    echo "<div class=\"signup_link\">";
                                                        echo "Nog geen account? <a href=\"?form=signup\" class=\"login-to-signup\">Aanmelden</a>";
                                                    echo "</div>";
                                                echo "</form>";
                                            }
                                            else{
                                                echo "<form method=\"POST\">";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"email\" name=\"email\" value=" . htmlspecialchars($_POST["email"]) . " maxlength=\"40\">";
                                                    echo "<span></span>";
                                                    echo "<label style=\"color: red;\">Ongeldig Email Addres</label>";
                                                echo "</div>";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                    echo "<span></span>";
                                                    echo "<label>Wachtwoord</label>";
                                                echo "</div>";
                                                echo "<input type=\"submit\" name=\"login\" value=\"Login\">";
                                                echo "<div class=\"signup_link\">";
                                                    echo "Nog geen account? <a href=\"?form=signup\" class=\"login-to-signup\">Aanmelden</a>";
                                                echo "</div>";
                                            echo "</form>";
                                            }
                                        }
                                        else{
                                            echo "<form method=\"POST\">";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"email\" name=\"email\" maxlength=\"40\">";
                                                    echo "<span></span>";
                                                    echo "<label>Email</label>";
                                                echo "</div>";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                    echo "<span></span>";
                                                    echo "<label>Wachtwoord</label>";
                                                echo "</div>";
                                                echo "<input type=\"submit\" name=\"login\" value=\"Login\">";
                                                echo "<div class=\"signup_link\">";
                                                    echo "Nog geen account? <a href=\"?form=signup\" class=\"login-to-signup\">Aanmelden</a>";
                                                echo "</div>";
                                            echo "</form>";
                                        }
                                    echo "</div>";
                                    break;
                                case "signup":
                                    echo "<div class=\"signup_form\">";
                                        echo "<h2 class=\"title\">Welkom</h2>";
                                        if(isset($_POST["aanmelden"])){
                                            $dupelicateUsername_check = loginManager::selectUsernameInsert(strtolower($_POST["username"]));
                                            $dupelicateEmail_check = loginManager::selectMailInsert(strtolower($_POST["email"]));
                                            if(strtolower($dupelicateUsername_check->username) == strtolower($_POST["username"])){
                                                echo "<form method=\"POST\">";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"text\" id=\"username\" value=" . htmlspecialchars($_POST["username"]) . " name=\"username\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label style=\"color: red;\">Username bestaat al *</label>";
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"text\" value=" . htmlspecialchars($_POST["voornaam"]) . " name=\"voornaam\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Voornaam *</label>";
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"text\" value=" . htmlspecialchars($_POST["achternaam"]) . " name=\"achternaam\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Achternaam *</label>";
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"email\" value=" . htmlspecialchars($_POST["email"]) . " name=\"email\" maxlength=\"40\">";
                                                        echo "<span></span>";
                                                        if(strtolower($dupelicateEmail_check->email) == strtolower($_POST["email"])){
                                                            echo "<label style=\"color: red;\">Probeer een ander Email Addres*</label>";
                                                        }
                                                        else{
                                                            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                                                echo "<label style=\"color: red;\">Ongeldig Email Addres *</label>";
                                                            }else{
                                                                echo "<label>Email *</label>";
                                                                if(strtolower($dupelicateUsername_check->username) !== strtolower($_POST["username"])){
                                                                    loginManager::insert($_POST["username"], $_POST["voornaam"], $_POST["achternaam"], $_POST["email"], $_POST["password"]);
                                                                    header("location:?form=login");
                                                                }
                                                            }
                                                        }
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Wachtwoord *</label>";
                                                    echo "</div>";
                                                    echo "<input type=\"submit\" name=\"aanmelden\" value=\"Aanmelden\">";
                                                    echo "<div class=\"login_link\">";
                                                        echo "Heb je al een account? <a href=\"?form=login\" class=\"signup-to-login\">Login</a>";
                                                    echo "</div>";
                                                echo "</form>";
                                            }
                                            else{
                                                echo "<form method=\"POST\">";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"text\" id=\"username\" value=" . htmlspecialchars($_POST["username"]) . " name=\"username\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Username *</label>";
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"text\" value=" . htmlspecialchars($_POST["voornaam"]) . " name=\"voornaam\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Voornaam *</label>";
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"text\" value=" . htmlspecialchars($_POST["achternaam"]) . " name=\"achternaam\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Achternaam *</label>";
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"email\" value=" . htmlspecialchars($_POST["email"]) . " name=\"email\" maxlength=\"40\">";
                                                        echo "<span></span>";
                                                        if(strtolower($dupelicateEmail_check->email) == strtolower($_POST["email"])){
                                                            echo "<label style=\"color: red;\">Probeer een ander Email Addres*</label>";
                                                        }
                                                        else{
                                                            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                                                echo "<label style=\"color: red;\">Ongeldig Email Addres *</label>";
                                                            }else{
                                                                echo "<label>Email *</label>";
                                                                if(strtolower($dupelicateUsername_check->username) !== strtolower($_POST["username"])){
                                                                    $_SESSION["signup_username"] = $_POST["username"];
                                                                    $_SESSION["signup_voornaam"] = $_POST["voornaam"];
                                                                    $_SESSION["signup_achternaam"] = $_POST["achternaam"];
                                                                    $_SESSION["signup_email"] = $_POST["email"];
                                                                    $_SESSION["signup_password"] = $_POST["password"];

                                                                    $verify_code = loginManager::getVerifyCode();
                                                                    $_SESSION["verify_code"] = $verify_code;
                                                                    loginManager::sendVerifyMail($_POST["email"],$_POST["username"], $_SESSION["verify_code"]);
                                                                    header("location:?form=verify");
                                                                }
                                                            }
                                                        }
                                                    echo "</div>";
                                                    echo "<div class=\"field\">";
                                                        echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                        echo "<span></span>";
                                                        echo "<label>Wachtwoord *</label>";
                                                    echo "</div>";
                                                    echo "<input type=\"submit\" name=\"aanmelden\" value=\"Aanmelden\">";
                                                    echo "<div class=\"login_link\">";
                                                        echo "Heb je al een account? <a href=\"?form=login\" class=\"signup-to-login\">Login</a>";
                                                    echo "</div>";
                                                echo "</form>";
                                            }
                                        }
                                        else{
                                            echo "<form method=\"POST\">";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"text\" id=\"username\" name=\"username\" maxlength=\"20\">";
                                                    echo "<span></span>";
                                                    echo "<label>Username *</label>";
                                                echo "</div>";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"text\" name=\"voornaam\" maxlength=\"20\">";
                                                    echo "<span></span>";
                                                    echo "<label>Voornaam *</label>";
                                                echo "</div>";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"text\" name=\"achternaam\" maxlength=\"20\">";
                                                    echo "<span></span>";
                                                    echo "<label>Achternaam *</label>";
                                                echo "</div>";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"email\" name=\"email\" maxlength=\"40\">";
                                                    echo "<span></span>";
                                                    echo "<label>Email *</label>";
                                                echo "</div>";
                                                echo "<div class=\"field\">";
                                                    echo "<input required type=\"password\" name=\"password\" minlength=\"5\" maxlength=\"20\">";
                                                    echo "<span></span>";
                                                    echo "<label>Wachtwoord *</label>";
                                                echo "</div>";
                                                echo "<input type=\"submit\" name=\"aanmelden\" value=\"Aanmelden\">";
                                                echo "<div class=\"login_link\">";
                                                    echo "Heb je al een account? <a href=\"?form=login\" class=\"signup-to-login\">Login</a>";
                                                echo "</div>";
                                            echo "</form>";
                                        }
                                    echo "</div>";
                                    break;
                                case "verify":
                                    if(!isset($_SESSION["verify_code"])){
                                        header("location:login?form=login");
                                    }
                                    echo "<div class=\"signup_form\">";
                                        echo "<h5 class=\"title text-center\">Er is een vertificatie code naar u gestuurt naar het door u ingevulde email adress!</h5>";
                                        echo "<h5>Zonder code komt u niet in onze website!</h5>";
                                        echo "<form method=\"POST\">";
                                            echo "<div class=\"field\">";
                                                echo "<input type=\"text\" id=\"verify_code_input\" name=\"verify_code_input\" maxlength=\"6\">";
                                                echo "<span></span>";
                                                if(isset($_POST["verify_code_input"])){
                                                    if($_POST["verify_code_input"] != $_SESSION["verify_code"]){
                                                        echo "<label>Code niet juist *</label>";
                                                    }else{
                                                        loginManager::insert($_SESSION["signup_username"], $_SESSION["signup_voornaam"], $_SESSION["signup_achternaam"], $_SESSION["signup_email"], $_SESSION["signup_password"]);
                                                        header("location:logout");
                                                    }
                                                    echo "<label>Code niet juist *</label>";
                                                }else{
                                                    echo "<label>Code *</label>";
                                                }
                                            echo "</div>";
                                            echo "<input type=\"submit\" name=\"verify_code_submit\" value=\"Verifiëren\">";
                                            echo "<input type=\"submit\" name=\"verify_code_again_submit\" value=\"Stuur code opnieuw.\" style=\"background:none;border:none;color:black;\">";
                                            if(isset($_POST["verify_code_again_submit"])){
                                                $verify_code = loginManager::getVerifyCode();
                                                $_SESSION["verify_code"] = $verify_code;
                                                loginManager::sendVerifyMail($_SESSION["signup_email"],$_SESSION["signup_username"], $_SESSION["verify_code"]);
                                                header("location:login?form=verify&nc=1");
                                            }
                                            if(isset($_GET["nc"]) == 1 ){
                                                echo "<p style=\"font-size:10px;\">Nieuwe code gestuurt naar " . $_SESSION["signup_email"] . "</p>";
                                            }
                                        echo "</form>";
                                    break;
                            }
                        ?>
            </div>
                        
        </main>
    </body>
</html>