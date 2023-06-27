<a href="index"">
    <img src="images/logo.png" alt="logo" class="logo">
</a>
<nav>
    <?php
        if($_SESSION["is_admin"] == 0){

        }else{
            echo "<a href=\"admin?status=searchAll&order=joinDate\">Admin</a>";
        }
    ?>
    <a href="scoreboard">Scorebord</a>
    <a href="drivers">Coureurs</a>
    <?php
    echo "<a href=\"profile?username=" . $_SESSION['username'] . "\">";
            if(!file_exists("pfp/$pf->profile_picture")){
                echo "<img src=\"pfp/pictures/user_profile_error.png\" alt=\"PFP\" class=\"pfp\">";
            }else{
                echo "<img src=\"pfp/$pf->profile_picture\" alt=\"PFP\" class=\"pfp\">";
            }
    echo "</a>";
    ?>
    <a href="logout">
        <span class="material-symbols-outlined">logout</span>
    </a>
</nav>