<?php
  require_once 'config.php';
  require_once 'funciones.php';
  // require_once 'conectabd.php';
  
  // Al incluir el archivo autolad que está en la carpeta de las clases, se escanean los archivos de dicho directorio para encontrar la clase en el caso de que se haya llamado a dicha clase. De esta forma no hay que incluir archivo por archivo. Además solo se cargarán las clases que se están utilizando en el código.
  require_once '../clases/autoload.php';

  //require_once 'funciones-AJAX.php';
  
  $bdInfo = new BD($sql_host, $sql_usuario, $sql_pass, $sql_db);
  $bd = BD::conectarBD($bdInfo);

  // Recuperamos la información de la sesión
  session_start();

  // Y comprobamos que el usuario se haya autentificado
  if (!isset($_SESSION['usuario'])) {
    redireccionar('../index.php');  
    // redireccionarSinHistorial('index.php');  
  }
?>

<?php    
  //Datos del login pasados por la url. Comprobación e inicialización.
  $tipoUsuario = (isset($_SESSION['tipoUsuario'])) ? htmlspecialchars($_SESSION['tipoUsuario'], ENT_QUOTES) : '';
  $idAlumno = (isset($_SESSION['id']) && $tipoUsuario=='alumno') ? htmlspecialchars($_SESSION['id'], ENT_QUOTES) : '';
  $idProfesor = (isset($_SESSION['id']) && $tipoUsuario=='profesor') ? htmlspecialchars($_SESSION['id'], ENT_QUOTES) : '';
  $usuario = (isset($_SESSION['usuario'])) ? htmlspecialchars($_SESSION['usuario'], ENT_QUOTES) : '';
  $alumno = (isset($_SESSION['alumno'])) ? $_SESSION['alumno'] : '';
  $profesor = (isset($_SESSION['profesor'])) ? $_SESSION['profesor'] : '';

  $tiempoHoy = (isset($_SESSION['tiempoHoy'])) ? $_SESSION['tiempoHoy'] : '';

  //Variable para monstrar mensajes de error o confirmación. Comprobación e inicialización.
  $error = (isset($_REQUEST['error'])) ? trim(htmlspecialchars($_REQUEST['error'], ENT_QUOTES)) : '';
  $OK = (isset($_REQUEST['OK'])) ? trim(htmlspecialchars($_REQUEST['OK'], ENT_QUOTES)) : '';
  // $error = (isset($_SESSION['error'])) ? trim(htmlspecialchars($_SESSION['error'], ENT_QUOTES)) : '';
  // $OK = (isset($_SESSION['OK'])) ? trim(htmlspecialchars($_SESSION['OK'], ENT_QUOTES)) : '';

  //Variable para mostra la fecha del último login. Comprobación e inicialización.
  $ultimo_login = (isset($_COOKIE['ultimo_login'])) ? trim(htmlspecialchars($_COOKIE['ultimo_login'], ENT_QUOTES)) : '';
  //Actualizo la columna ultimo_login de mi bd solo una vez, dependiendo del tipo de usuario (alumno y profesor)
  $loginActualizado = (isset($_SESSION['loginActualizado'])) ? htmlspecialchars($_SESSION['loginActualizado'], ENT_QUOTES) : '';
  if(empty($loginActualizado)){
    BD::actualizarUltimoLogin($ultimo_login);
    $_SESSION['loginActualizado'] = true;
  }
   
  $nuevaPestana = (isset($_REQUEST['pestana'])) ? trim(htmlspecialchars($_REQUEST['pestana'], ENT_QUOTES)) : '';
  if(!empty($nuevaPestana)){
    $_SESSION['pestana'] = $nuevaPestana;
  }else{
    if(empty($_SESSION['pestana'])) $_SESSION['pestana'] = 'default';
  }
  $pestana = $_SESSION['pestana'];

  
  //  ELIMINAR LA SESION
   if (isset($pestana) && $pestana=='salir'){
    session_unset();
    session_destroy();
    redireccionar('../index.php?OK=9');
    // header("Location: index.php");
  }
?>

<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso a la extranet del IES Al-Ándalus</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="../estilos/extranet.css">
    <script src="../scripts/menu_vista_general.js"></script>
  </head>
   
  <body>
    <div id="cabecera_extranet" class="container-fluid pt-3">
      <div class="row">
        <div class="col-12 d-flex flex-column justify-content-center align-items-center">
          <h1>Extranet del IES Al-Ándalus</h1>
          <?php // Muestro hora último login
          echo '<span><b>Bienvenid@ '.$usuario.'</b></span>';
          if (isset($ultimo_login) && !empty($ultimo_login)){
            echo '<span>Último login: '. date("d/m/y \a \l\a\s H:i", $ultimo_login).'</span>';
          }?><br>
          
          <?php
              //Mostramos los mensajes de error al usuario
              if(!empty($error)){
                mostrarMensajeERR($error);
              }
              //Mostramos los mensajes de confirmación al usuario
              if(!empty($OK)){
                mostrarMensajeOK($OK);
              }
          ?>
          <div id="mensajesAJAX"></div>
        </div>
      </div>
    </div>

    <?php //EXTRANET PARA UN USURIO DE TIPO ALUMNO ?>
    <?php if($tipoUsuario == 'alumno'){
      require_once 'extranet-alumno.php';
    } ?>  

    <?php // EXTRANET PARA UN USURIO DE TIPO PROFESOR ?>
    <?php if($tipoUsuario == 'profesor'){
      require_once 'extranet-profesor.php';
    } ?>  
    
    <footer class="bg-dark text-center my-3">
      <!-- Copyright -->
      <div class="text-white pt-3 pb-2">
        <p>© 2021 Copyright: Jesús Garcimartín Berbel</p>
        <p>Desarrollo Web Entorno Servidor</p>
      </div>
      <!-- Copyright -->
    </footer>
  </body>
</html>
<?php
  BD::desconectarBD($bd);
  // require_once 'desconectabd.php'; 
?>