<?php
    include("connection.php");
    
    session_start();
     if(isset($_POST['btn'])){
        $email = $_POST['email'];
        $contra = $_POST['contra'];
        $sql = "SELECT idUsuario FROM `usuarios` WHERE `email` = '$email' AND `password` = '$contra'";
        $response =  mysqli_query($connection,$sql);
        if( mysqli_num_rows($response) == 1){
            while( $row = mysqli_fetch_array($response,MYSQLI_ASSOC)){
                $_SESSION['user'] = $row['idUsuario'];
                $_SESSION['email'] = $email;
                $_SESSION['contra'] = $contra;
                $_SESSION['acceso'] = "user";
                
                if($email == "admin@server.com" && $contra =="serveradmin" ){
                $_SESSION['acceso'] = "admin";
                }
            
            }
            header("location: panel.php");
        }else{
         echo "Usuario y/o contraseña invalida";
        }
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>webgenerator Thomas Hilgemberg</title>
</head>
<body>
    <form method="POST">
        <input type="email" name="email"  placeholder="Email">
        <input type="password" name="contra"  placeholder="Contraseña">
        <button name="btn" value="iniciarSesion">Iniciar sesión</button>
        <a href="register.php">¿No tienes una cuenta? registrate</a>
    </form>
</body>
</html>