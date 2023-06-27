<?php
    class userManager{
        public static function select(){
            global $con;

            $stmt=$con->prepare("SELECT * FROM user");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public static function selectOnId($id){
            global $con;

            $stmt=$con->prepare("SELECT * FROM user WHERE idperson = ? ");
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return $stmt->fetchObject();
        }
        public static function selectOnIdfetch($id){
            global $con;

            $stmt=$con->prepare("SELECT * FROM user WHERE idperson = ? ");
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public static function selectOnUsernameGet($username){
            global $con;

            $stmt=$con->prepare("SELECT * FROM user WHERE username = ? ");
            $stmt->bindValue(1, htmlspecialchars($username));
            $stmt->execute();

            return $stmt->fetchObject();
        }
        public static function getProfileInfoHeader($id){
            global $con;

            $stmt=$con->prepare("SELECT * FROM user WHERE idperson = ? ");
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return $stmt->fetchObject();
        }
        public static function selectOnAdmin($GETusers, $GETorder, $GETusername){
            global $con;

            $query = "SELECT * FROM user where";


            switch($GETusers){
                default:
                case "searchAll":
                    $query = "SELECT * FROM user ";
                    break;
                case "searchUsers":
                    $users = 0;
                    $query .= " is_admin = ? ";
                    break;
                case "searchMods":
                    $users = 1;
                    $query .= " is_admin = ? ";
                    break;
                case "searchAdmins":
                    $users = 2;
                    $query .= " is_admin = ? ";
                    break;
                case "searchModsEnAdmins":
                    $users = 1;
                    $users2 = 2;
                    $query .= " is_admin = ? || is_admin = ? ";
                    break;
            }
            if(!isset($GETusername)){
                $username = htmlspecialchars(strtolower($GETusername));
                $query .= "username like ? ";
            }
            switch($GETorder){
                default:
                case "joinDate":
                    break;
                case "A-Z":
                    $query .= "order by username asc ";
                    break;
                case "Z-A":
                    $query .= "order by username desc ";
                    break;
            }

            $stmt=$con->prepare($query);
            if(!isset($GETusername)){
                $stmt->bindValue(1, "%$username%");
                $stmt->bindValue(2, $users);
                if($GETusers !== "searchModsEnAdmins"){
                }else{
                    $stmt->bindValue(3, $users2);
                }
            }else{
                $stmt->bindValue(1, $users);
                if($GETusers !== "searchModsEnAdmins"){
                }else{
                    $stmt->bindValue(2, $users2);
                }
            }
            // $stmt->bindValue(1, $users);
            // if($GETusers !== "searchModsEnAdmins"){
            // }else{
            //     $stmt->bindValue(2, $users2);
            // }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        public static function updateProfilePicture($oldfileName, $file, $id, $getUsername){
            global $con;

            $cPF_file = $file;

            $cPF_fileName = $_FILES["file"]["name"];
            $cPF_fileTmpName = $_FILES["file"]["tmp_name"];
            $cPF_fileSize = $_FILES["file"]["size"];
            $cPF_fileError = $_FILES["file"]["error"];
            $cPF_fileType = $_FILES["file"]["type"];

            $cPF_fileExt = explode(".", $cPF_fileName);
            $cPF_fileLowerType = strtolower(end($cPF_fileExt));
            $cPF_allowedTypes = array(
                "jpg",
                "jpeg",
                // "gif",
                "png"
            );
            if (in_array($cPF_fileLowerType, $cPF_allowedTypes)) {
                if($cPF_fileError === 0){
                    if($cPF_fileSize < 3000000){
                        if($oldfileName != "pictures/user_profile.png"){
                            unlink("pfp/" . $oldfileName);
                        }
                        $cPF_fileNameUpload = uniqid("", true) . "." . $cPF_fileLowerType;
                        $cPF_fileDestination = "pfp/pictures/" . $cPF_fileNameUpload;
                        move_uploaded_file($cPF_fileTmpName, $cPF_fileDestination);

                        $query = "UPDATE user ";
                        $query .= "SET profile_picture = ? ";
                        $query .= "WHERE idperson = ? ";
            
                        $stmt=$con->prepare($query);
                        $stmt->bindValue(1, "pictures/" . $cPF_fileNameUpload);
                        $stmt->bindValue(2, $id);
                        $stmt->execute();

                        header("location:profile?username=$getUsername");
                    }else{
                        echo "The image you uploaded was too big. Plz downscale the image or choose another one.";
                    }
                }else{
                    echo "There was an error uploading your profile picture. Plz try again.";
                }
            }else{
                // echo "Upload an picture!";
            }
        }
        public static function insert(){
            
        }
        public static function DeleteProfilePicture($id){
            global $con;

            $stmt=$con->prepare("SELECT * FROM user WHERE idperson = ? ");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $user = $stmt->fetchObject();

            if($user->profile_picture != "pictures/user_profile.png"){
                unlink("pfp/" . $user->profile_picture);
            }

            $query = "UPDATE user ";
            $query .= "SET profile_picture = ? ";
            $query .= "WHERE idperson = ? ";

            $stmt=$con->prepare($query);
            $stmt->bindValue(1, "pictures/user_profile.png");
            $stmt->bindValue(2, $id);
            $stmt->execute();


            
        }
        public static function checkIsAdmin($id){
            global $con;

            $stmt=$con->prepare("SELECT is_admin FROM user WHERE idperson = ? ");
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return $stmt->fetchObject();

            // 0 = is User. Geen Admin.
            // 1 = is Moderator. Admin zonder rechten om andere admin te geven of te ontemen
            // 2 = is Admin met de rechten om andere admin te geven of te ontemen

        }
        public static function updateAsAdmin($iUsername, $iIs_admin, $iId, $page_info){
            global $con;

            $query = "UPDATE user ";
            $query .= "SET username = ? , is_admin = ? ";
            $query .= "WHERE idperson = ? ";

            $stmt=$con->prepare($query);    
            $stmt->bindValue(1,$iUsername);
            $stmt->bindValue(2,$iIs_admin);
            $stmt->bindValue(3,$iId);
            $stmt->execute();
        }
        public static function updateUserData($data, $id){
            global $con;

            $password = password_hash($data["password"], PASSWORD_DEFAULT);

            $query = "UPDATE user ";
            $query .= "SET username = ? , firstname = ?, lastname = ?, email = ?, password = ?";
            $query .= "WHERE idperson = ? ";

            $stmt=$con->prepare($query);    
            $stmt->bindValue(1,$data["username"]);
            $stmt->bindValue(2,$data["firstname"]);
            $stmt->bindValue(3,$data["lastname"]);
            $stmt->bindValue(4,$data["email"]);
            $stmt->bindValue(5,$password);
            $stmt->bindValue(6,$id);
            $stmt->execute();
        }
    }

?>