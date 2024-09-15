<?php
session_start();

echo '<b>Test de sesiones de PHP</b><hr />';

if(!isset($_GET['reload']) OR $_GET['reload'] != 'true') {
   $_SESSION['MESSAGE'] = '¡Soporte de sesiones activo!<br />';
   echo '<a href="?reload=true">Pulsa AQUÍ</a> para comprobar el soporte de sesiones.<br />';
} else {
   if(isset($_SESSION['MESSAGE'])) {
      echo $_SESSION['MESSAGE'];
      echo session_status();
   } else {
      echo 'El soporte de sesiones de PHP no parece estar activo. <a href="?reload=false">Pulsa AQUÍ</a> para volver atrás.<br />';
   }
}
?>
