<?php
    //Comunicación asíncrona con el servidor web: AJAX.

    //Tengo que volver a importar todas las clases porque por algún motivo (el cual desconozco), no se reconocen ni las clases, ni la conexión a la base de datos, ni las variables de sesión. No depende de si la importación de este archivo pruebas-AJAX.php se hizo desde el archivo 'raiz' extranet.php, se produce el mismo error. Las variables de sesión no existen tanto en este archivo como en las llamadas a otras funciones que se hagan en este archivo.
   require_once 'config.php';
   require_once 'funciones.php';
   require_once '../clases/autoload.php';

   $bdInfo = new BD($sql_host, $sql_usuario, $sql_pass, $sql_db);
   $bd = BD::conectarBD($bdInfo);

   $evaluar = (isset($_REQUEST['evaluar'])) ? htmlspecialchars($_REQUEST['evaluar'], ENT_QUOTES) : '';
   $idAlumnoAEvaluar = (isset($_REQUEST['idAlumnoAEvaluar'])) ? htmlspecialchars($_REQUEST['idAlumnoAEvaluar'], ENT_QUOTES) : '';
   $idProfesor = (isset($_REQUEST['idProfesor'])) ? htmlspecialchars($_REQUEST['idProfesor'], ENT_QUOTES) : '';
   $evaluacionesNotas = (isset($_REQUEST['evaluacionesNotas'])) ? json_decode($_REQUEST['evaluacionesNotas']) : '';

   $arrayNotas = Array();

   if($evaluar == 1){
      // print_r($evaluacionesNotas);
      foreach($evaluacionesNotas as $nota){
         $arrayNotas[] = new Nota($nota->curso, $nota->asignatura, $nota->profesor, $nota->trimestre, $nota->calificacion);
      }
      BD::actualizarNotas($idAlumnoAEvaluar, $idProfesor, $arrayNotas);
   }
?>