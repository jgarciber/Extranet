<?php
session_start();

echo '<b>Test de sesiones de PHP</b><br />';
echo '<br>Este primer archivo crea la variable de sesión MESSAGE, con el mensaje "¡Soporte de sesiones activo!"<br />';
echo '<p>Si todo funciona correctamente, se mostrará dicho mensaje cuando se redirija al usuario al fichero prueba-sesiones-2.php</p><hr />';

$_SESSION['MESSAGE'] = '¡Soporte de sesiones activo!<br />';
echo "Mediante un enlace.<br />";
echo '<a href="prueba-sesiones-2.php">Pulsa AQUÍ</a> para comprobar el soporte de sesiones.<br />';
echo "<br />";
echo "Mediante JavaScript.<br />";
echo '<button id="btn-redireccionar">Pulsar el botón para comprobar el soporte de sesiones.</button>';
?>
<script>
    let btnRedireccionar = document.getElementById('btn-redireccionar');
    btnRedireccionar.addEventListener('click', function(){location.href = 'prueba-sesiones-2.php';});
</script>