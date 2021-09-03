<?php
    include("connection.php");
    session_start();

    
    if(isset($_POST['btn'])){
        $email = $_POST['email'];
        $contra = $_POST['contra'];
        $contra2 = $_POST['contra2'];
        $sql = "SELECT * FROM `usuarios` WHERE `email` = '$email'";
        $response =  mysqli_query($connection,$sql);
       
        
        if( !empty($response) AND  mysqli_num_rows($response) >= 1 ){
            echo "Email ya registrado, ingrese uno nuevo ";
            
        }

        if($contra != $contra2 ){
            echo "Las contraseñas no son iguales ";  
            
        }

        if($contra == ""OR $contra2 =="" OR $email == "" ){
            echo "Hay campos vacíos ";  
            
        }

        $accept = true;
        if($accept){
            $sql = "INSERT INTO `usuarios` (`email`,`password`) VALUES ('$email','$contra')";
            $response =  mysqli_query($connection,$sql);
            if ($response) {
                echo "El registro fue exitoso";
                header("location: login.php");
            }else {
            echo "Error al registrarse " . mysqli_error($connection);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
<h1>Registrarse es simple</h1>
    <form method="POST" action="">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="contra" placeholder="Contraseña">
    <input type="password" name="contra2"  placeholder="Repetir contraseña">
    <button name="btn" value="registrate">Registrate</button>
</form>
</body>
</html>