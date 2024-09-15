<?php
// Función para redireccionar al usuario a una url pasada por parámetro.
function redireccionar($url) {
   echo "<script type='text/javascript'>location.href='".$url."';</script>";
   exit;
}

// Función para redireccionar al usuario a una url pasada por parámetro.
function redireccionarSinHistorial($url) {
   echo "<script type='text/javascript'>location.replace(".$url.");</script>";
   exit;
}

function imprimirRegistrosTabla($registros){
   //Apunto a la posición 0 de la consulta mysqli para "reiniciar" la cuenta de los fetch_array
   $registros->data_seek(0); //Estilo orientado a objetos
   // mysqli_data_seek($registros, 0); //Estilo por procedimientos
   while(($row = $registros->fetch_array(MYSQLI_NUM)) != null){
      echo '<tr>';
      for($i=0; $i<count($row); $i++){
         echo '<td>'.$row[$i].'</td>';
      }
      echo '</tr>';
   }
}

function imprimirRegistrosTablaConColores($registros, $indiceEstado){
   // El $indiceEstado indica si está activo(1) o dado de baja(0)
   $registros->data_seek(0);
   while(($row = $registros->fetch_array(MYSQLI_NUM)) != null){
      if ($row[$indiceEstado] == 0) echo '<tr class=filaInactivo>';else echo '<tr>';
      for($i=0; $i<count($row); $i++){
         echo '<td>'.$row[$i].'</td>';
      }
      echo '</tr>';
   }
}

function imprimirRegistrosTablaOmitirColumnas($registros, $strColumnasAOmitir){
   $registros->data_seek(0);
   //Obtengo un array con los índices de las columnas a omitir, se indican mediante una cadena separando los índices por comas
   $omitirColumnas = preg_split("/[\s,]+/", $strColumnasAOmitir);
   //Copio los valores de las columnas de $registros de las posiciones que quiero omitir
   while(($row = $registros->fetch_array(MYSQLI_NUM)) != null){ 
      echo '<tr>';
      for ($i=0; $i<count($row); $i++){
         //Busco si la posición $i indicada no existe en el array, ese es el valor que quiero imprimir
         if(!in_array($i, $omitirColumnas)){
            echo '<td>'.$row[$i].'</td>';
         }
      }
      echo '</tr>';
   }
}

function matricularAlumno($nuevoUsuario, $nuevaContrasena, $nuevoNombre, $nuevoApellidos, $nuevoTelefono, $nuevoEmail, $nuevoCurso, $algoritmoContrasena){
   //Creo la consulta para insertar el nuevo alumno solo la primera vez que ejecuto esta función.
   global $consultaMatricularAlumno;
   global $bd;
   if($consultaMatricularAlumno == null){
      $consultaMatricularAlumno = $bd->stmt_init();
      $consultaMatricularAlumno->prepare("INSERT INTO ies_alumno (usuario, pass, nombre, apellidos, telefono, email, curso, activo) VALUES (?, ?, ?, ?, ?, ?, ?, 1) ;");
   }
   switch($algoritmoContrasena){
      case 'md5': $nuevaContrasena = md5($nuevaContrasena); break;
      case 'blowfish': $nuevaContrasena = password_hash($nuevaContrasena, PASSWORD_BCRYPT); break;
   }
   //los parámetros deben siempre pasarte como variables, nunca como constantes, ya que entonces se produce un error
   $consultaMatricularAlumno->bind_param('ssssisi', $nuevoUsuario, $nuevaContrasena, $nuevoNombre, $nuevoApellidos, $nuevoTelefono, $nuevoEmail, $nuevoCurso);
   $consultaMatricularAlumno->execute();
   if ($consultaMatricularAlumno->affected_rows >= 1){
      redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=5');
   }else{
      redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=11');
   }
   // $consultaMatricularAlumno->free();
}

function imprimirOpcionesCurso (){
   global $bd;
   $cursos = $bd->query("SELECT c.id, c.nombre FROM ies_curso c ;");
   while(($row = $cursos->fetch_array(MYSQLI_ASSOC)) != null){
      echo '<option value="'.$row['id'].'">'.$row['nombre'].' (Curso '.$row['id'].')</option>';
   }

   // Vista previa
   // <option value="1">1º ESO A (Curso 1)</option>
   // <option value="2">1º ESO B (Curso 2)</option>
   // <option value="3">2º ESO (Curso 3)</option>
   // <option value="5">3º ESO (Curso 5)</option>
   // <option value="6">4º ESO (Curso 6)</option>
   // <option value="7">1º FPB Admin. (Curso 7)</option>
   // <option value="8">2º FPB Admin. (Curso 8)</option>
   // <option value="9">1º FPB Peluq. (Curso 9)</option>
   // <option value="10">1º EEUE (Curso 10)</option>
}

function imprimirOpcionesCursoProfesor ($tutorCurso){
   global $bd;
   $cursos = $bd->query("SELECT c.id, c.nombre FROM ies_curso c INNER JOIN ies_profesor p on p.tutor_curso=c.id WHERE p.tutor_curso='".$tutorCurso."' LIMIT 1;");
   $row=$cursos->fetch_array(MYSQLI_ASSOC);
   echo '<option value="'.$row['id'].'">'.$row['nombre'].' (Curso '.$row['id'].')</option>';
}

function aplicarHashingContrasenasBD ($bd, $algoritmo){
   $numAlumnos = $bd->query("SELECT count(*) FROM ies_alumno ;");
   $numAlumnos = $numAlumnos->fetch_array(MYSQLI_NUM);
   $numAlumnos = $numAlumnos[0];
   
   $numProfesores = $bd->query("SELECT count(*) FROM ies_profesor ;");
   $numProfesores = $numProfesores->fetch_array(MYSQLI_NUM);
   $numProfesores = $numProfesores[0];
   
   $passAlumnos = $bd->query("SELECT pass FROM ies_alumno ;");
   while(($passAlumno = $passAlumnos->fetch_array(MYSQLI_NUM)) != null){    
     if($algoritmo == 'md5'){
       $cambiarContrasena = $bd->query("UPDATE ies_alumno SET pass='".md5($passAlumno[0])."' ;");
     }
     if($algoritmo == 'blowfish'){
       // PASSWORD_BCRYPT - Usar el algoritmo CRYPT_BLOWFISH para crear el hash. Producirá un hash estándar compatible con crypt() utilizando el identificador "$2y$". El resultado siempre será un string de 60 caracteres, o FALSE en caso de error.
       // Si no se indica el párametro options (que es array asociativo), se creará una sal aleatoria y el coste algorítmico por defecto será utilizado
       $cambiarContrasena = $bd->query("UPDATE ies_alumno SET pass='".password_hash($passAlumno[0], PASSWORD_BCRYPT)."' ;");
     }
   }

   $passProfesores = $bd->query("SELECT pass FROM ies_profesor ;");
   while(($passProfesor = $passProfesores->fetch_array(MYSQLI_NUM)) != null){
     if($algoritmo == 'md5'){
       $cambiarContrasena = $bd->query("UPDATE ies_profesor SET pass='".md5($passProfesor[0])."' ;");
     }
     if($algoritmo == 'blowfish'){
       $cambiarContrasena = $bd->query("UPDATE ies_profesor SET pass='".password_hash($passProfesor[0], PASSWORD_BCRYPT)."' ;");
     }
   }

   //Mensajes de confirmación de que se ha realizo correctamente el cambio de contraseña
   if($algoritmo == 'md5'){
     redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=7');
   }
   if($algoritmo == 'blowfish'){
     redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=8');
   }

   $numAlumnos->free();
   $numProfesores->free();
   $passAlumnos->free();
   $passProfesores->free();
}

// LLevo al usuario hasta la ubicación del elemento $idDestino, haciendo scroll "smooth".
function smoothScroll($idDestino){
   echo "<script>
      window.addEventListener('load', function() {
         document.getElementById('".$idDestino."').scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
      </script> ";
}

// Función para mostrar los mensajes de Información al usuario
function mostrarMensajeOK($msg) {
   switch($msg){
      case 1: $mensaje[1]="Se ha registrado correctamente. Ahora recibirá un email en su cuenta, y deberá seguir las instrucciones para activar su cuenta. Muchas gracias y a disfrutar del servicio!!!"; break;
      case 2: $mensaje[2]="La información se ha guardado correctamente."; break;
      case 3: $mensaje[3]="El comentario se ha guardado correctamente."; break;
      case 4: $mensaje[4]="El comentario sobre el alumno se ha guardado correctamente."; break;
      case 5: $mensaje[5]="Se ha matriculado al alumno correctamente"; break;
      case 6: $mensaje[6]="Se ha dado de <b>baja</b> al alumno correctamente"; break;
      case 7: $mensaje[7]="Se ha aplicado el algoritmo MD5 correctamente"; break;
      case 8: $mensaje[8]="Se ha aplicado el algoritmo BlowFish correctamente"; break;
      case 9: $mensaje[9]="Se ha cerrado la sesión correctamente"; break;
      case 10: $mensaje[10]="Se ha dado de <b>alta</b> al alumno correctamente"; break;
      case 11: $mensaje[11]="Se ha dado de <b>alta</b> la incidencia correctamente"; break;
      case 12: $mensaje[12]="Se ha actualizado el log de incidencias correctamente"; break;
      case 13: $mensaje[13]="Se ha actualizado la incidencia correctamente"; break;
      case 14: $mensaje[14]="Se han actualizado las calificaciones correctamente"; break;
      default: $mensaje[0]=''; break;
   }
   // Mensajes con bootstrap
   echo '<span class="alert alert-success">'.$mensaje[$msg].'</span>';

   // Mensajes con estilos personalizado
   // echo '<span class="mensaje_confirmacion">'.$mensaje[$msg].'</span>';
}

// Función para mostrar los mensajes de Error al usuario
function mostrarMensajeERR($msg) {
   switch($msg){
      case 1: $mensaje[1]="Debe completar los campos del formulario."; break;
      case 2: $mensaje[2]="Datos incorrectos. Pruebe de nuevo."; break;
      case 3: $mensaje[3]="El usuario no está activo. Debe de contactar con su Director para que active su cuenta."; break;
      case 4: $mensaje[4]="Su sesión ha expirado. Debe volver a iniciar la sesión."; break;
      case 5: $mensaje[5]="Debe completar el campo <u>Nombre</u>"; break;
      case 6: $mensaje[6]="Debe completar el campo <u>Apellidos</u>"; break;
      case 7: $mensaje[7]="Debe completar el campo <u>Email</u>"; break;
      case 8: $mensaje[8]="Ese email ya está ocupado. Pruebe con otro o contacte con los administradores del sistema."; break;
      case 9: $mensaje[9]="Email no válido."; break;
      case 10: $mensaje[10]="SinInfo"; break;
      case 11: $mensaje[11]="No se ha matriculado al alumno introducido"; break;
      case 12: $mensaje[12]="No se ha dado de baja al alumno introducido.<br>No se ha encontrado dicho alumno."; break;
      case 13: $mensaje[13]="No puede matricular a un@ alumn@ en un curso distinto al curso donde el profesor es tutor."; break;
      case 14: $mensaje[14]="NO se ha dado de alta al alumno introducido.<br>No se ha encontrado dicho alumno."; break;
      case 15: $mensaje[15]="No se ha dado de alta al alumno introducido.<br>Ya estaba de alta."; break;
      case 16: $mensaje[16]="No se ha dado de baja al alumno introducido.<br>YA estaba de baja."; break;
      case 17: $mensaje[17]="No se puede matricular al alumn@, ya existe ese nombre de usuario."; break;
      case 18: $mensaje[18]="No se ha dado de alta a la incidencia introducida."; break;
      case 19: $mensaje[19]="No se ha actualizado el log de incidencias."; break;
      case 20: $mensaje[20]="No se ha actualizado la incidencia."; break;
      case 21: $mensaje[21]="No se han actualizado las calificaciones. El profesor no es tutor del alumno a evaluar"; break;
      case 22: $mensaje[22]="No se han actualizado las calificaciones."; break;
      default: $mensaje[0]=''; break;
   }
   // Mensajes con bootstrap
   echo '<span class="alert alert-danger">'.$mensaje[$msg].'</span>';

   // Mensajes con estilos personalizado
   // echo '<span class="mensaje_error">'.$mensaje[$msg].'</span>';
}

function meteoRedObtenerAtributosForecast ($ruta_var, $nDiasPrediccion){
   $datosObtenidos = array();
   for ($i = 0; $i < $nDiasPrediccion; $i++) {
      $datosObtenidos[$i] = $ruta_var->data->forecast[$i]->attributes();
   }
   return $datosObtenidos;
}

//Muestra un mensaje en el la consola, muy útil para depurar
function console_log($data){
   echo '<script>';
   echo 'console.log('. json_encode( $data ) .')';
   echo '</script>';
 }
?>
