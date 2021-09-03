<?php
    include("connection.php");
    $dominios = "";
    session_start();
    if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
        $dominios =  getdominios($connection);
        if(isset($_POST['btn'])){
            $user = $_SESSION['user'];
            $web = $_POST['web'];
            if($web == "" ){
                echo "No ingreso una  web";
            }else{
                $web = $user.$web;
                $sql = "SELECT dominio FROM `webs` WHERE `dominio` = '$web' ";
                $response =  mysqli_query($connection,$sql);
                if( mysqli_num_rows($response) >= 1){
                    echo "Dominio registrado anteriormente";
                }else{
                    $sql = "INSERT INTO `webs` (`idUsuario`,`dominio`) VALUES('$user','$web')";
                    $response =  mysqli_query($connection,$sql);
                    if ($response) {
                        echo "El registro de la web fue exitoso";
                        echo shell_exec("./wix.sh '$web' '$web' ");
                        $dominios =  getdominios($connection);
                    }else {
                        echo "Error al registrar la web " . mysqli_error($connection);
                    }
                }
                
            }
        }

        if(isset($_POST['download'])){
            $folder = $_POST['download'];
            $zip = "$folder.zip";
            echo shell_exec( "zip -r ".$zip." ".$folder);
            $location = "location: ".$zip ;
            if(!header($location)){
                echo "zip no encontrado";
            }
        }
        
        if(isset($_POST['delete'])){
            $folder = $_POST['delete'];
            echo shell_exec(" rm -r ".$folder);
            $sql = "DELETE FROM `webs` WHERE `dominio` = '$folder'";
            $response =  mysqli_query($connection,$sql);
            if ($response) {
                echo "Se elimino la web correctamente";
                $dominios =  getdominios($connection);
            }else{
                echo "Error al eliminar la web";
            }
        }

    }else{
        header("location: login.php");
    }
    

    function getdominios($connection){
        $dominios = "";
        if( $_SESSION['acceso'] == "admin"){
            $sql = "SELECT `dominio` FROM `webs`";
        }else{
            $user = $_SESSION['user'];
            $sql = "SELECT dominio FROM `webs` WHERE `idUsuario` = '$user' ";
        }

        $response =  mysqli_query($connection,$sql);
        if( mysqli_num_rows($response) >= 1){
            $dominios .= "<ol>";
            while( $row = mysqli_fetch_array($response,MYSQLI_ASSOC)){
                $domain =$row['dominio'];
                // $idWeb = $row['idWeb'];
                $dominios.=
                "<li>
                    <a href='".$domain."'>".$domain."</a>
                    <button name='download' value='".$domain."'>Descargar web</button>
                    <button  name='delete' value='".$domain."'>Eliminar</button>
                </li>";
            }  
            $dominios .= "</ol>";
        }
        return $dominios;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel</title>
</head>
<body>
<h1>Bienvenido a tu panel</h1>
<a href="logout.php">Cerrar sesión de <?php echo $_SESSION['user'] ?></a>
<form method="POST" action="">
    <label for="">Generar web de:</label>
    <input type="text" name="web" >
    <button name="btn" value="crearWeb">Crear web</button>
</form>
<u>Lista de dominios: </u>
<form action="" method="POST">
    <?php echo $dominios ?>
    
</form>

</body>
</html>