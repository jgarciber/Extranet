<?php 
// Autoload. PHP proporciona la funcionalidad spl_autoload_register() que permite que una vez definida una función esta recorra las carpetas de nuestro proyecto cargando todas las clases que localice dentro del proyecto, pero en realidad no las usará hasta que no sean instanciadas.

  require_once './archivos/config.php';
  require_once './archivos/funciones.php';
  // require_once './archivos/conectabd.php';

  // Al incluir el archivo autolad que está en la carpeta de las clases, se escanean los archivos de dicho directorio para encontrar la clase en el caso de que se haya llamado a dicha clase. De esta forma no hay que incluir archivo por archivo. Además solo se cargarán las clases que se están utilizando en el código.
  require_once './clases/autoload.php';

  $bdInfo = new BD($sql_host, $sql_usuario, $sql_pass, $sql_db);
  $bd = BD::conectarBD($bdInfo);
?>

<?php
  //Comprobación para autocompletar y errores para el input nombre
  $usuario = (isset($_REQUEST['usuario'])) ? htmlspecialchars($_REQUEST['usuario'], ENT_QUOTES) : '';
  if (isset($_REQUEST['enviar']) && !isset($_REQUEST['error']) && empty(trim($usuario))) redireccionar('index.php?error=1');

  //Comprobación para autocompletar y errores para el input contrasena
  $contrasena = (isset($_REQUEST['contrasena'])) ? htmlspecialchars($_REQUEST['contrasena'],ENT_QUOTES) : '';
  if (isset($_REQUEST['enviar']) && !isset($_REQUEST['error']) && empty(trim($contrasena))) redireccionar('index.php?error=1');

  $alumno = null;
  $profesor = null;
  $archivoExtranet = 'archivos/extranet.php';

  //DECLARACION DE ARRAYS PARA LA API DEL TIEMPO METEORED
	$tempMinimas = array(); 
	$tempMaximas = array();
	$vientos = array();
	$simbolosTiempo = array();
	$diasSemana = array();

  $file='http://api.tiempo.com/index.php?api_lang=es&localidad=106&affiliate_id=en93fl64dwjn';
  $nDiasPrediccion = 7;

  //ABRIMOS EL FICHERO CON LA LIBRERIA SIPLEXML_LOAD_FILE
  //LEEMOS LOS DATOS QUE NECESITAMOS//
  if($xml = simplexml_load_file($file)){
    $url= $xml->location->interesting->url;
    $array=explode('-', $url);	
    $lugar = $xml->location->attributes();
    $city = explode('[', $lugar);

    $tempMinimas = meteoRedObtenerAtributosForecast ($xml->location->var[0], $nDiasPrediccion);
    $tempMaximas = meteoRedObtenerAtributosForecast ($xml->location->var[1], $nDiasPrediccion);
    $vientos = meteoRedObtenerAtributosForecast ($xml->location->var[2], $nDiasPrediccion);
    $simbolosTiempo = meteoRedObtenerAtributosForecast ($xml->location->var[3], $nDiasPrediccion);
    $diasSemana = meteoRedObtenerAtributosForecast ($xml->location->var[4], $nDiasPrediccion);

    $tiempoHoy = [
      'tempMinima' => intval($tempMinimas[2]->value),
      'tempMaxima' => intval($tempMaximas[2]->value),
      'viento' => intval($vientos[2]->idB)
    ];
  }else{
    echo "Introduzca la ruta del fichero XML";
  }


  //SE COMPRUEBAN LAS CONTRASEÑAS CON TODOS LOS ALGORITMOS HASHING, el primero que coincida se considera válido
  if (isset($_REQUEST['enviar'])) {

    //Verificación contraseña sin encriptar  
    $alumno = BD::loginAlumno($usuario, $contrasena, null);
    $profesor = BD::loginProfesor($usuario, $contrasena, null);

    // Verificación contraseña algoritmo MD5  
    if($alumno == null && $profesor == null){
      $alumno = BD::loginAlumno($usuario, $contrasena, 'md5');
      $profesor = BD::loginProfesor($usuario, $contrasena, 'md5');
    }

    //Verificación contraseña algoritmo BlowFish
    if($alumno == null && $profesor == null){
      $alumno = BD::loginAlumno($usuario, $contrasena, 'blowfish');
      $profesor = BD::loginProfesor($usuario, $contrasena, 'blowfish');
    }
  
    //Si se ha encontrado el alumno o el profesor con la contraseña introducida, es decir, se ha autenticado el usuario, se le redireccionar a la extranet.
    if($alumno != null || $profesor != null){
      session_start();
      //Creamos las variables de sesión
      $_SESSION['usuario'] = $usuario;
      $_SESSION['ultimo_login'] = setcookie("ultimo_login", time(), time()+3600);
      $_SESSION['tiempoHoy'] = $tiempoHoy;
      
      if($alumno != null && $profesor == null){
        $_SESSION['alumno'] = $alumno;
        $_SESSION['tipoUsuario'] = 'alumno';
        $_SESSION['id'] = $alumno->getId();
        //indico por la url que el usuario es de tipo alumno
        redireccionar($archivoExtranet.'?tipoUsuario=alumno&id='.$alumno->getId().'&usuario='.$usuario);
      }

      if($alumno == null && $profesor != null){
        $_SESSION['profesor'] = $profesor;
        $_SESSION['tipoUsuario']='profesor';
        $_SESSION['id']= $profesor->getId();
        //indico por la url que el usuario es de tipo profesor
        redireccionar($archivoExtranet.'?tipoUsuario=profesor&id='.$profesor->getId().'&usuario='.$usuario);
      }
    }

    //Si el alumno o el profesor no ha sido encontrado, la autenticación ha fallado. Se manda un mensaje de error
    //Se comprueba que no haya ningún otro error en la url, ya que en principio solo se puede mostrar uno. Se mostrará el primero que se produjo.
    if($alumno == null && $profesor == null && !isset($_REQUEST['error'])){
      redireccionar('index.php?&usuario='.$usuario.'&error=2');
    }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>"Login de usuarios"</title>
  
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- Popper JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="./estilos/index.css">
  <link rel="stylesheet" href="./estilos/tiempo.css">

  
</head>

<body>
  <div class="container pt-3">
    <div class="row">
      <div class="col-12 d-flex flex-column justify-content-center align-items-center">
        <!-- <h2>Login extranet del IES Al-Ándalus</h2> -->
        <img src="./imagenes/logo-ies2-edit5.png" alt="Imagen_bienvenida_extranet" id="imagen_portada"><br>
        <h3>Iniciar sesión</h3>
        <?php
          //Mostramos los mensajes de error al usuario
          if(isset($_REQUEST['error'])){
            mostrarMensajeERR($_REQUEST['error']);
          }
          //Mostramos los mensajes de confirmación al usuario
          if(isset($_REQUEST['OK'])){
            mostrarMensajeOK($_REQUEST['OK']);
          }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <label for="usuario">Usuario: </label><br>
          <input type="text" name="usuario" value="<?php echo $usuario; ?>"/><br>
          <label for="contrasena">Contraseña: </label><br>
          <input type="password" name="contrasena" /><br><br>
          <input type="submit" name="enviar" value="Entrar" class="btn btn-primary" />
        </form>

        <?php
          //Tabla de resultados del tiempo de la API MeteoRed
           
          echo '<table id="tabla_tiempo">';
            echo '<caption>';
              echo 'Predicciones para  '.trim($city[0]);
            echo '</caption>';
            
            echo '<tr>';
              for ($i=0; $i<count($diasSemana); $i++) {
                echo '<td>';
                if($i==0){ echo '<table class="columna_tabla_tiempo text-center ml-2">';
              }else{
                echo '<table class="columna_tabla_tiempo text-center">';
              }
                  echo '<th>'.$diasSemana[$i]->value.'</th>';

                  echo '<tr>';
                    echo '<td>';
                      echo '<img src="./iconos/tiempo-weather/galeria1/' . $simbolosTiempo[$i]->id . '.png" alt="' . $simbolosTiempo[$i]->value . '" title="' . $simbolosTiempo[$i]->value . '"/><BR>';
                    echo '</td>';
                  echo '</tr>';
                  
                  echo '<tr>';
                    echo '<td>Max: '.$tempMaximas[$i]->value.'</td>';
                  echo '</tr>';
                    
                  echo '<tr>';
                    echo '<td>Min: '.$tempMinimas[$i]->value.'</td>';
                  echo '</tr>';


                  echo '<tr>';
                    echo '<td align="center">';
                    $wind = $vientos[$i]->id % 8;
                    if ($wind == 0) $wind = 8;
                    if ($vientos[$i]->id == 33){
                      echo  '<img src="./iconos/viento-wind/galeria1/' . $vientos[$i]->id . '.png" alt="' . $vientos[$i]->value . '" title="' . $vientos[$i]->value . '"/><BR>';
                    }else{
                      echo  '<img src="./iconos/viento-wind/galeria1/' . $wind . '.png" alt="' . $vientos[$i]->value . '" title="' . $vientos[$i]->value . '"/><BR>';
                    echo '</td>';
                    }
                  echo '</tr>';

                echo '</table>';

                echo '</td>';
              }
            echo '</tr>';

          echo '</table>';
        ?>
	    </div>
    </div>
  </div>
</body>
</html>
<?php
  BD::desconectarBD($bd);
  // require_once '../archivos/desconectabd.php'; 
?>
