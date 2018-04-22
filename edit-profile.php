<?php
  session_start();

  require_once "functions.php";
  include "head.php";
  include "navbar.php";

  $connection = connectDB();

    if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {

      // Fix member profile picture max size to 2MB
      $maxsize = 2097152;
      $validFormats = ['jpg', 'jpeg', 'gif', 'png'];

      if ($_FILES['avatar']['size'] <= $maxsize) {

        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if(in_array($extensionUpload, $validFormats)){
          $path = "member/avatar/" . $_SESSION['id'] . "." . $extensionUpload;
          $result = move_uploaded_file($_FILES['avatar']['tmp_name'],$path);
          if($result){
            $updateAvatar = $connection->prepare('UPDATE MEMBER SET profile_photo_filename = :profile_photo_filename WHERE id = :id');
            $updateAvatar->execute(array(
              'profile_photo_filename' => $_SESSION['id'].".".$extensionUpload,
              'id' => $_SESSION['id']
            ));
            header('Location: profile.php');

          }else{
            //message d'erreur "Erreur durant l'importation de votre photo de profil"
            echo "Error while importing your file ";
          }
        }else{
          //message d'erreur "Votre photo de profil doit être au format jpg, jpeg, gif ou png"
          echo "Your file must be in the format jpg, jpeg, gif ou png";
        }
      }else{
        //message d'erreur "Votre photo de profil ne doit pas dépasser 10 Mo"
        echo "Your file should not exceed 2Mo";
      }
    }

  ?>

    <form method="POST" action="" enctype="multipart/form-data">
      <label>Photo de profile (JPG, JPEG, PNG or GIF ) :</label>
      <input type="file" name="avatar" />
      <input type="submit" name="submit" value="Mettre à jour ma photo de profil" />
    </form>

<?php
  include "footer.php";
?>
