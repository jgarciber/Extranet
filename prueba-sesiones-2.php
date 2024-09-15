<?php
session_start();

echo '<b>Test de sesiones de PHP, parte 2, recuperación de la variable de sesión</b><hr />';

if(isset($_SESSION['MESSAGE']) && !empty($_SESSION['MESSAGE'])) {
   echo $_SESSION['MESSAGE'];
   echo '<a href="prueba-sesiones-1.php">Pulsa AQUÍ</a> para volver atrás.<br />';
} else {
   echo $_SESSION['MESSAGE'];
   echo 'El soporte de sesiones de PHP no parece estar activo. <a href="prueba-sesiones-1.php">Pulsa AQUÍ</a> para volver atrás.<br />';
}
session_unset();
session_destroy();
?>
