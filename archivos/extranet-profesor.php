<?php
   //IMPORTANTE posibles opciones para encriptar la contraseña, establecer $algoritmoContrasena a: 'md5' , 'blowfish' o null(no aplica)
   // Selecionar solo uno, comentar el resto. No habrá que modificar más código para que sea efectivo, es similar a aplicar una directiva.

   // $algoritmoContrasena = 'md5'; 
   $algoritmoContrasena = 'blowfish'; 
   // $algoritmoContrasena = null;

   $alumnoAEvaluar = null;   

   $alumnos = null;
   $cursos = null;
   $cursoProfesor = null;
   $trimestres = null;
   $todosAlumnos = null;
   $todosProfesores = null;
   $todasAsignaturas = null;
   $notificarAlertas = null;
   $todasIncidencias = null;
   $todasIncidenciasLog = null;
   $historialIncidencia = null;
   
   //Campos del formulario de inserción de un nuevo alumno. Comprobación e inicialización.
   $nuevoUsuario = (isset($_REQUEST['nuevoUsuario'])) ? trim(htmlspecialchars($_REQUEST['nuevoUsuario'], ENT_QUOTES)) : '';
   $nuevaContrasena = (isset($_REQUEST['nuevaContrasena'])) ? trim(htmlspecialchars($_REQUEST['nuevaContrasena'], ENT_QUOTES)) : '';
   $nuevoNombre = (isset($_REQUEST['nuevoNombre'])) ? trim(htmlspecialchars($_REQUEST['nuevoNombre'], ENT_QUOTES)) : '';
   $nuevoApellidos = (isset($_REQUEST['nuevoApellidos'])) ? trim(htmlspecialchars($_REQUEST['nuevoApellidos'], ENT_QUOTES)) : '';
   $nuevoTelefono = (isset($_REQUEST['nuevoTelefono'])) ? trim(htmlspecialchars($_REQUEST['nuevoTelefono'], ENT_QUOTES)) : '';
   $nuevoEmail = (isset($_REQUEST['nuevoEmail'])) ? trim(htmlspecialchars($_REQUEST['nuevoEmail'], ENT_QUOTES)) : '';
   $nuevoCurso = (isset($_REQUEST['nuevoCurso'])) ? trim(htmlspecialchars($_REQUEST['nuevoCurso'], ENT_QUOTES)) : '';
   $formMatricularAlumno = (isset($_REQUEST['formMatricularAlumno'])) ? trim(htmlspecialchars($_REQUEST['formMatricularAlumno'], ENT_QUOTES)) : 0;
   $matricularAlumno = (isset($_REQUEST['matricularAlumno'])) ? trim(htmlspecialchars($_REQUEST['matricularAlumno'], ENT_QUOTES)) : '';

   //Campos del formulario para dar de BAJA a un alumno. Comprobación e inicialización.
   $usuarioADarBaja = (isset($_REQUEST['usuarioADarBaja'])) ? trim(htmlspecialchars($_REQUEST['usuarioADarBaja'], ENT_QUOTES)) : '';
   $formDarBajaAlumno = (isset($_REQUEST['formDarBajaAlumno'])) ? trim(htmlspecialchars($_REQUEST['formDarBajaAlumno'], ENT_QUOTES)) : 0;
   $darBajaAlumno = (isset($_REQUEST['darBajaAlumno'])) ? trim(htmlspecialchars($_REQUEST['darBajaAlumno'], ENT_QUOTES)) : '';

   //Campos del formulario para dar de ALTA a un alumno. Comprobación e inicialización.
   $usuarioADarAlta = (isset($_REQUEST['usuarioADarAlta'])) ? trim(htmlspecialchars($_REQUEST['usuarioADarAlta'], ENT_QUOTES)) : '';
   $formDarAltaAlumno = (isset($_REQUEST['formDarAltaAlumno'])) ? trim(htmlspecialchars($_REQUEST['formDarAltaAlumno'], ENT_QUOTES)) : 0;
   $darAltaAlumno = (isset($_REQUEST['darAltaAlumno'])) ? trim(htmlspecialchars($_REQUEST['darAltaAlumno'], ENT_QUOTES)) : '';

   //Campos del formulario para dar de alta una incidencia. Comprobación e inicialización.
   $detallesIncidencia = (isset($_REQUEST['detallesIncidencia'])) ? trim(htmlspecialchars($_REQUEST['detallesIncidencia'], ENT_QUOTES)) : '';
   $formDarAltaIncidencia = (isset($_REQUEST['formDarAltaIncidencia'])) ? trim(htmlspecialchars($_REQUEST['formDarAltaIncidencia'], ENT_QUOTES)) : 0;
   $darAltaIncidencia = (isset($_REQUEST['darAltaIncidencia'])) ? trim(htmlspecialchars($_REQUEST['darAltaIncidencia'], ENT_QUOTES)) : '';

   //Ver historial de una incidencia
   $verHistorialIncidencia = (isset($_REQUEST['verHistorialIncidencia'])) ? trim(htmlspecialchars($_REQUEST['verHistorialIncidencia'], ENT_QUOTES)) : '';

   //Campos del formulario para actualizar una incidencia. Comprobación e inicialización.
   $idIncidencia = (isset($_REQUEST['idIncidencia'])) ? trim(htmlspecialchars($_REQUEST['idIncidencia'], ENT_QUOTES)) : '';
   $nuevoEstadoIncidencia = (isset($_REQUEST['nuevoEstadoIncidencia'])) ? trim(htmlspecialchars($_REQUEST['nuevoEstadoIncidencia'], ENT_QUOTES)) : '';
   $nuevosDetallesIncidencia = (isset($_REQUEST['nuevosDetallesIncidencia'])) ? trim(htmlspecialchars($_REQUEST['nuevosDetallesIncidencia'], ENT_QUOTES)) : '';
   $formActualizarIncidencia = (isset($_REQUEST['formActualizarIncidencia'])) ? trim(htmlspecialchars($_REQUEST['formActualizarIncidencia'], ENT_QUOTES)) : 0;
   $actualizarIncidencia = (isset($_REQUEST['actualizarIncidencia'])) ? trim(htmlspecialchars($_REQUEST['actualizarIncidencia'], ENT_QUOTES)) : '';

   //Id del Alumno a evaluar
   $idAlumnoAEvaluar = (isset($_REQUEST['idAlumnoAEvaluar'])) ? trim(htmlspecialchars($_REQUEST['idAlumnoAEvaluar'], ENT_QUOTES)) : '';

   //Ver notas del alumno
   $ver_notas = (isset($_REQUEST['ver_notas'])) ? trim(htmlspecialchars($_REQUEST['ver_notas'], ENT_QUOTES)) : '';

   //Formulario actualizar notas del alumno
   $formActualizarNotas = (isset($_REQUEST['formActualizarNotas'])) ? trim(htmlspecialchars($_REQUEST['formActualizarNotas'], ENT_QUOTES)) : '';

   //Variables para aplicar los algoritmos de encriptación de las contraseñas. Comprobación e inicialización.
   $aplicarMD5 = (isset($_REQUEST['aplicarMD5'])) ? trim(htmlspecialchars($_REQUEST['aplicarMD5'], ENT_QUOTES)) : 0;
   $aplicarBlowFish = (isset($_REQUEST['aplicarBlowFish'])) ? trim(htmlspecialchars($_REQUEST['aplicarBlowFish'], ENT_QUOTES)) : 0;

   //MATRICULAR-INSERTAR NUEVO ALUMNO:
   // Compruebo que se ha rellenado el formulario para insertar el nuevo alumno y que todos los campos tienen un valor. Además, el profesor deberá ser tutor de un curso, y solo podrá matricular a alumnos en dicho curso
   if ($matricularAlumno=='Matricular' && !empty($nuevoUsuario) && !empty($nuevaContrasena) && !empty($nuevoNombre) && !empty($nuevoApellidos) && !empty($nuevoTelefono) && !empty($nuevoEmail) && !empty($nuevoCurso)){
   
   //Compruebo si ya existía el nombre del alumno
   if(BD::buscarUsuarioPorNombre('alumno', $nuevoUsuario) != null){
      redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&error=17');
   }else{
      //Compruebo que el curso del profesor sea el mismo que el curso a matricular del nuevo alumno
      if($profesor->getTutorCurso() == $nuevoCurso){
         $nuevoAlumno = new Alumno(null, $nuevoUsuario, $nuevoNombre, $nuevoApellidos, $nuevoTelefono, $nuevoEmail, $nuevoCurso, 1, null);
         BD::matricularAlumnoPOO($nuevoAlumno, $nuevaContrasena, $algoritmoContrasena);
      }else{
         redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&error=13');
      }
   }
   }

   //DAR DE BAJA A UN ALUMNO:
   //Compruebo que se ha rellenado el formulario para dar de baja al alumno y que todos los campos tienen un valor.
   if ($darBajaAlumno=='Dar de baja' && !empty($usuarioADarBaja)){
   BD::darBajaAlumno($usuarioADarBaja);
   }

   //DAR DE ALTA A UN ALUMNO:
   //Compruebo que se ha rellenado el formulario para dar de alta al alumno y que todos los campos tienen un valor.
   if ($darAltaAlumno=='Dar de alta' && !empty($usuarioADarAlta)){
   BD::darAltaAlumno($usuarioADarAlta);
   }

   //DAR DE ALTA UNA INCIDENCIA:
   //Compruebo que se ha rellenado el formulario para dar de alta una incidencia.
   if ($darAltaIncidencia=='Dar alta incidencia' && !empty($detallesIncidencia)){
   $nuevaIncidencia = new Incidencia(null, $idProfesor, null, 0, $detallesIncidencia);
   BD::darAltaIncidencia($nuevaIncidencia);
   }

   //ACTUALIZAR UNA INCIDENCIA:
   //Compruebo que se ha rellenado el formulario para dar de alta una incidencia.

   if ($actualizarIncidencia=='Actualizar incidencia' && !empty($nuevosDetallesIncidencia)){
      BD::actualizarIncidencia($idIncidencia, $idProfesor, $nuevoEstadoIncidencia, $nuevosDetallesIncidencia);
   }

   //VER HISTORIAL DE CAMBIOS DE UNA INCIDENCIA:
   if ($verHistorialIncidencia == 1){
      $historialIncidencia = BD::obtenerHistorialIncidencia($idIncidencia);
   }

   //APLICAR EL HASH MD5 a las contrasenas de los alumnos y profesores
   if($aplicarMD5 == 1) aplicarHashingContrasenasBD($bd, 'md5');
   if($aplicarBlowFish == 1) aplicarHashingContrasenasBD($bd, 'blowfish');

   //Varible para la barra de navegación y las pestañas de Bootstrap
   if($pestana == 'default') $pestana = 'mis-alumnos';

   // CONSULTAS A LA BASE DE DATOS
   // -------------CONSULTAS-BD------------------

   // Consultas para un usuario tipo "profesor"
   $alumnos = BD::obtenerAlumnosCurso($profesor->getTutorCurso());
   // Otra forma de obtener los alumnos
   // $alumnos = BD::obtenerAlumnosProfesor($profesor->getId());
   
   $cursos = BD::obtenerCursos();
   // también funciona con obtenerCursos2(), es otra implementación de lo mismo.
   // $cursos = BD::obtenerCursos2();
   $cursoProfesor = BD::obtenerCurso($profesor->getTutorCurso())->getNombre();

   $trimestres = BD::obtenerTrimestres();
   $todosAlumnos = BD::obtenerAlumnos();
   $todosProfesores = BD::obtenerProfesores();
   $todasAsignaturas = BD::obtenerAsignaturas();

   if($idAlumnoAEvaluar != null) $alumnoAEvaluar = BD::obtenerAlumno($idAlumnoAEvaluar);

   $notificarAlertas = BD::comprobarAlertas($tiempoHoy['tempMaxima'], $tiempoHoy['viento']);

   $todasIncidencias = BD::obtenerIncidencias();
   $todasIncidenciasLog = BD::obtenerIncidenciasLog();
   // -------------FIN-CONSULTAS-BD------------------
   ?>

<?php //EXTRANET PARA UN USURIO DE TIPO PROFESOR ?>

<script src="../scripts/extranet-profesor.js" defer></script>
   <nav class="navbar navbar-expand-md sticky-top bg-dark navbar-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
         <ul class="navbar-nav">
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='mis-alumnos') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=mis-alumnos'; ?>">Mis Alumnos</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='profesores') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=profesores'; ?>">Profesores</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='cursos') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=cursos'; ?>">Cursos</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='asignaturas') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=asignaturas'; ?>">Asignaturas</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='evaluaciones') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=evaluaciones'; ?>">Evaluaciones</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='trimestres') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=trimestres'; ?>">Trimestres</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='vista-resumen') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=vista-resumen'; ?>">Vista Resumen</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='avisos') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=avisos'; ?>">Avisos</a>
         </li>
         <li class="nav-item">
            <a class="nav-link <?php if($pestana=='incidencias') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=incidencias'; ?>">Incidencias</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana=salir'; ?>">Salir</a>
         </li>
         </ul>
      </div>  
   </nav>

   <div class="container pt-3">
      <div class="row">
         <div class="col-12 d-flex flex-column">
            <div id="extranet_profesor" class="table table-striped table-hover">

               <?php // Pestana Profesor. MIS ALUMNOS ?> 
               <?php if($pestana == 'mis-alumnos'){ ?>
                  <div id="alumnos_profesor" class="d-flex flex-column align-items-center">
                  <?php echo '<h5><b>'.$usuario.'</b>, eres tutor del curso "'.$cursoProfesor.'"</h5>'; ?>
                  <div class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered table-sm">
                        <caption>Alumnos, ordenado por apellidos</caption>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Curso</th>
                        <th>Acciones</th>
                        <?php
                        foreach ($alumnos as $al){
                           if ($al->getActivo() == 0) echo '<tr class=filaInactivo>';else echo '<tr>';
                           echo '<td>'.$al->getUsuario().'</td>';
                           echo '<td>'.$al->getNombre().'</td>';
                           echo '<td>'.$al->getApellidos().'</td>';
                           echo '<td>'.$al->getTelefono().'</td>';
                           echo '<td>'.$al->getEmail().'</td>';
                           echo '<td>'.$al->getCurso()->getNombre().'</td>';
                           echo '<td>';
                           if ($al->getActivo() == 0){
                              echo '<button class="btn btn-outline-success btn-sm bt-custom-verde " onclick="location.replace(\''.$_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&darAltaAlumno=Dar de alta&usuarioADarAlta='.$al->getUsuario().'\')">Dar de alta</button>';
                           }
                           if ($al->getActivo() == 1){
                              echo '<button class="btn btn-outline-danger btn-sm bt-custom-rojo" onclick="location.replace(\''.$_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&darBajaAlumno=Dar de baja&usuarioADarBaja='.$al->getUsuario().'\')">Dar de baja</button>';
                           }
                           echo '</td>';
                           echo '</tr>';
                        }
                        if($alumnos == null) echo '<tr><td>No tiene ningún alumno</td></tr>'; 
                        ?>
                     </table><br>
                  </div>

                  <br>
                  <div id="divBotonesTablaAlumnos">
                     <?php // Solo puede matricular el profesor que es tutor ?>
                     <?php if($profesor->getTutorCurso() != null){ ?>
                        <button class="btn btn-outline-primary bt-custom-azul" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&formMatricularAlumno=1' ; ?>')">Matricular alumno</button>
                     <?php } ?>
                     <button class="btn btn-outline-success bt-custom-verde" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&formDarAltaAlumno=1' ; ?>')">Dar de alta alumno</button>
                     <button class="btn btn-outline-danger bt-custom-rojo" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&formDarBajaAlumno=1' ; ?>')">Dar de baja alumno</button>
                     <br><br>
                     
                     <?php // Botones exclusivos para el usuario admin ?> 
                     <?php if ($usuario=='admin') { ?> 
                     <p>Aplicar algoritmo hashing a todas las contraseñas de la base de datos </p>
                     <p>Este proceso puede tomar un tiempo si hay muchos usuarios en la base de datos</p>
                     <button class="btn btn-outline-warning" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&aplicarMD5=1' ; ?>')">Aplicar hash MD5</button>
                     <button class="btn btn-outline-warning" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&aplicarBlowFish=1' ; ?>')">Aplicar hash BlowFish</button>
                     <?php } ?>
                  </div>
                  
                  <?php // Formulario para PARA MATRICULAR UN ALUMNO ?>
                  <?php if (isset($formMatricularAlumno) && $formMatricularAlumno==1) { ?> 
                     <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="formMatricularAlumno">
                     <fieldset class="border p-2">    
                        <legend class="w-auto">Matricular a un alumno</legend>
                        <label for="nuevoUsuario">Usuario:</label><input type="text" name="nuevoUsuario" id="nuevoUsuario" placeholder="@nombre" pattern="@[a-zA-ZáéíóúüÁÉÍÓÓÜ]{3,}[0-9]*" title="El nombre de usuario debe comenzar por @, seguido de mínimo 3 caracteres. Se pueden incluir números al final del nombre (opcional). No puede contener barras o signos." required/><br /><br />
                        <label for="nuevaContrasena">Contraseña:</label><input type="pass" name="nuevaContrasena" id="nuevaContrasena" required/><br /><br />
                        <label for="nuevoNombre">Nombre:</label><input type="text" name="nuevoNombre" id="nuevoNombre" pattern="[a-zA-ZáéíóúüÁÉÍÓÓÜ]+" title="El nombre del alumno solo puede contener caracteres, no se permiten números, barras o signos." required/><br /><br />
                        <label for="nuevoApellidos">Apellidos:</label><input type="text" name="nuevoApellidos" id="nuevoApellidos" pattern="[a-zA-ZáéíóúüÁÉÍÓÓÜ]+[\s]{1}[a-zA-ZáéíóúüÁÉÍÓÓÜ]*" title="Los apellidos del alumno deben ser de al menos dos palabras. Solo puede contener caracteres, no se permiten números, barras o signos." required/><br /><br />
                        <label for="nuevoTelefono">Teléfono:</label><input type="tel" name="nuevoTelefono" id="nuevoTelefono" pattern="(\+34|0034|34)?[ -]*(6|7|8|9)[ -]*([0-9][ -]*){8}" title="El teléfono debe seguir el patrón de España." required/><br /><br />
                        <label for="nuevoEmail">Email:</label><input type="email" name="nuevoEmail" id="nuevoEmail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="La contraseña debe estar en el siguiente formato: caracteres@caracteres.dominio. Después del &quot;.&quot;, agregue al menos 2 letras de la &quot;a&quot; a la &quot;z&quot;." required/><br /><br />
                        <label for="nuevoCurso">Curso:</label>
                        <select name="nuevoCurso" id="nuevoCurso" required>
                        <?php imprimirOpcionesCursoProfesor($profesor->getTutorCurso()); ?>
                        </select>
                        <br /><br />
                        <input type="submit" id="matricularAlumno" name="matricularAlumno" value="Matricular" class="btn btn-outline-success bt-custom-verde" />
                        <button class="btn btn-outline-danger bt-custom-rojo" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario; ?>')">Cancelar</button>
                     </fieldset> 
                     </form>
                     
                     <?php 
                     // Desplaza el scroll hasta el formulario que se acaba de cargar. Dicho formulario fue accionado al seleccionar el boton de Matricular alumno
                     smoothScroll('formMatricularAlumno');
                     ?>
                  <?php } ?>

                  <?php // Formulario DAR DE BAJA A UN ALUMNO ?>
                  <?php if (isset($formDarBajaAlumno) && $formDarBajaAlumno==1) { ?> 
                     <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="formDarBajaAlumno">
                        <fieldset class="border p-2"> 
                           <legend class="w-auto">Dar de baja a un alumno</legend>
                           <label for="usuarioADarBaja">Usuario:</label><input type="text" name="usuarioADarBaja" placeholder="@nombre" pattern="@[a-zA-ZáéíóúüÁÉÍÓÓÜ]{3,}[0-9]*" title="El nombre de usuario debe comenzar por @, seguido de mínimo 3 caracteres, a continuación se pueden incluir números (opcional). No puede contener barras o signos." required/><br /><br />
                           <input type="submit" id="darBajaAlumno" name="darBajaAlumno" value="Dar de baja" class="btn btn-outline-success bt-custom-verde"/>
                           <button class="btn btn-outline-danger bt-custom-rojo" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario; ?>')">Cancelar</button>
                        </fieldset> 
                     </form>
                     <?php smoothScroll('formDarBajaAlumno'); ?>
                  <?php } ?>

                  <?php // Formulario DAR DE ALTA A UN ALUMNO ?>
                  <?php if (isset($formDarAltaAlumno) && $formDarAltaAlumno==1) { ?> 
                     <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="formDarAltaAlumno">
                        <fieldset class="border p-2"> 
                           <legend class="w-auto">Dar de alta a un alumno</legend>
                           <label for="usuarioADarAlta">Usuario:</label><input type="text" name="usuarioADarAlta" placeholder="@nombre" pattern="@[a-zA-ZáéíóúüÁÉÍÓÓÜ]{3,}[0-9]*" title="El nombre de usuario debe comenzar por @, seguido de mínimo 3 caracteres, a continuación se pueden incluir números (opcional). No puede contener barras o signos." required/><br /><br />
                           <input type="submit" id="darAltaAlumno" name="darAltaAlumno" value="Dar de alta" class="btn btn-outline-success bt-custom-verde" />
                           <button class="btn btn-outline-danger bt-custom-rojo" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario; ?>')">Cancelar</button>
                        </fieldset> 
                     </form>
                     <?php smoothScroll('formDarAltaAlumno'); ?>
                  <?php } ?>

                  <br><br>
                  </div>
               <?php } ?>
               
               <?php // Pestana Profesor, PROFESORES ?> 
               <?php if($pestana == 'profesores'){ ?>
                  <div id="profesores">
                  <div class="table-responsive d-flex justify-content-around">
                     <table class="table-bordered table-sm">
                        <caption>Todos los profesores, ordenado por curso</caption>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Curso Tutor</th>
                        <?php
                           foreach ($todosProfesores as $p){
                              if($p->getId() == $profesor->getId()) echo '<tr class=filaProfesor>';else echo '<tr>';
                              echo '<td>'.$p->getUsuario().'</td>';
                              echo '<td>'.$p->getNombre().'</td>';
                              echo '<td>'.$p->getApellidos().'</td>';
                              echo '<td>'.$p->getEmail().'</td>';
                              $cursoEncontrado = Curso::encontrarCurso($p->getTutorCurso(), $cursos);
                              if($cursoEncontrado != null) $nombreCurso = $cursoEncontrado->getNombre(); else $nombreCurso = null;
                              echo '<td>'.$nombreCurso.'</td>';
                              echo '</tr>';
                           }
                        ?>
                     </table>
                  </div>
                  </div>
               <?php } ?>

               <?php // Pestana Profesor, CURSOS ?> 
               <?php if($pestana == 'cursos'){ ?>
                  <div id="cursos" class="d-flex flex-row">
                  <div class="table-responsive d-flex justify-content-around">
                     <table id="tablaTotalCursos" class="table-bordered table-sm">
                        <caption>Total Cursos</caption>
                        <th>Nombre</th>
                        <?php 
                        foreach($cursos as $cur){
                           echo '<tr>';
                              echo '<td>'.$cur->getNombre().'</td>';
                           echo '</tr>';
                        }
                        ?>
                     </table>

                     <table id="tablaCursosProfesor" class="table-bordered table-sm">
                        <caption>Cursos que imparte <b><?php echo $usuario ?></b></caption>
                        <th>Nombre</th>
                        <?php
                        // foreach($cursosProfesor as $cur){
                        //   echo '<tr>';
                        //     echo '<td>'.$cur->getNombre().'</td>';
                        //   echo '</tr>';
                        // }
                           echo '<tr>';
                              echo '<td>'.$cursoProfesor.'</td>';
                           echo '</tr>';
                        
                        if($cursoProfesor == null) echo '<tr><td>No imparte ningún curso</td></tr>'; 
                        ?>
                     </table>
                  </div>
                  
                  </div>
               <?php } ?>

               <?php // Pestana Profesor, ASIGNATURAS ?> 
               <?php if($pestana == 'asignaturas'){ ?>
                  <div id="asignaturas">
                     <?php $contador = 0; ?>
                     <?php foreach($cursos as $curso){
                        // Separo las tablas en capas de dos en dos para posicionarlas en filas de 2 en el navegador
                        if($contador == 0){
                           echo '<div class="table-responsive d-flex flex-sm-row flex-column justify-content-around">';
                        }
                        echo '<table class="table-responsive table-bordered table-sm mx-2">';
                        echo '<caption>Asignaturas del curso <b>'.$curso->getNombre().'</b></caption>';
                        echo '<th>Nombre</th>';
                        echo '<th>Nombre corto</th>';
                        //Filtro las asignaturas de cada curso, para ello busco el curso al que pertenece cada asignatura
                        $asignaturas = BD::obtenerAsignaturasCurso($curso->getId());
                        foreach ($asignaturas as $asig){
                           echo '<tr>';
                              echo '<td>'.$asig->getNombre().' -- id ('.$asig->getId().')</td>';
                              echo '<td>'.$asig->getNombreCorto().'</td>';
                           echo '</tr>';
                        }

                        // Otra forma de hacer lo mismo pero sin hacer consultas repetitivas a la BD. Se procesan los datos ya obtenidos de todos los cursos y todas las asignaturas.
                        //Filtro las asignaturas de cada curso, para ello busco el curso al que pertenece cada asignatura
                        /*
                        foreach ($todasAsignaturas as $asig){
                           $cursoEncontrado = Curso::encontrarCurso($asig->getCurso(), $cursos);
                           if($cursoEncontrado != null) $nombreCurso = $cursoEncontrado->getNombre(); else $nombreCurso = null;
                           if($cursoEncontrado->getId() == $curso->getId()){  
                              echo '<tr>';
                              echo '<td>'.$asig->getNombre().' -- id ('.$asig->getId().')</td>';
                              echo '<td>'.$asig->getNombreCorto().'</td>';
                              echo '</tr>';
                           }
                        }
                        echo '</table><br>'; */

                        echo '</table>';
                        $contador++;
                        if($contador == 2){
                           echo '</div><br>';
                           $contador = 0; 
                        }
                     } 
                        //En el caso de que la última columna solo tenga una tabla, entonces se cierra el div. El contador en ese caso valdría 1
                        if ($contador == 1) echo '</div>';
                     ?> 
                  </div>
               <?php } ?>

               <?php // Pestana Profesor. EVALUACIONES ?> 
               <?php if($pestana == 'evaluaciones'){ ?>
               <div id="evaluaciones" class="d-flex flex-column align-items-center">
                  <div class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered table-sm">
                        <caption>Alumnos, ordenado por apellidos</caption>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Acciones</th>
                        <?php
                        foreach ($alumnos as $al){
                           if ($al->getActivo() == 0) echo '<tr class=filaInactivo>';else echo '<tr>';
                           echo '<td>'.$al->getUsuario().'</td>';
                           echo '<td>'.$al->getNombre().'</td>';
                           echo '<td>'.$al->getApellidos().'</td>';
                           echo '<td>';
                              echo '<button class="btn btn-outline-primary btn-sm bt-custom-azul" onclick="location.replace(\''.$_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana='.$pestana.'&ver_notas=1&idAlumnoAEvaluar='.$al->getId().'\')">Ver notas</button>';
                              echo '<button class="btn btn-outline-success btn-sm bt-custom-verde" onclick="location.replace(\''.$_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana='.$pestana.'&formActualizarNotas=Aceptar&idAlumnoAEvaluar='.$al->getId().'\')">Actualizar notas</button>';
                           echo '</td>';
                           echo '</tr>';
                        }
                        if($alumnos == null) echo '<tr><td>No tiene ningún alumno</td></tr>'; 
                        ?>
                     </table><br>
                  </div>

                  <?php if (isset($formActualizarNotas) && $formActualizarNotas=='Aceptar') { ?> 
                     <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="formActualizarNotas">
                     <fieldset class="border p-2">    
                        <legend class="w-auto">Actualizar notas</legend>
                        <?php
                           echo '<p>Evaluará a <b>'.$alumnoAEvaluar->getNombre().' '.$alumnoAEvaluar->getApellidos().'</b></p>';
                           foreach($trimestres as $tri){
                              echo '<label for="tri-'.$tri->getId().'">'.$tri->getNombre2().'</label><input type="checkbox" name="chk-tri-'.$tri->getId().'" id="chk-tri-'.$tri->getId().'"><br>';
                           }
                              echo '<label for="tri-all">Evaluar Todas</label><input type="checkbox" name="chk-tri-all" id="chk-tri-all"><br>';

                              echo '<table class="table-responsive table-bordered table-sm" id="tb-actualizar-notas">';
                                 echo '<caption>Boletín del alumno</caption>';
                                 echo '<colgroup>';
                                       echo '<col span="2">';
                                    foreach($trimestres as $tri){
                                       echo '<col id="col-'.$tri->getNombre2().'"></col>';
                                    }
                                 echo '</colgroup>';
                                 echo '<tr>';
                                       echo '<th>Asignatura</th>';
                                       echo '<th>Profesor</th>';
                                    foreach($trimestres as $tri){
                                       echo '<th class="tri-'.$tri->getId().'">'.$tri->getNombre2().'</th>';
                                    }
                                 echo '</tr>';

                                 foreach($alumnoAEvaluar->getCurso()->getAsignaturas() as $asig){
                                    echo '<tr>';
                                       echo '<td>'.$asig->getNombre().'</td>';
                                       $profesoresAsignatura = BD::obtenerProfesoresAsignatura($asig->getId());

                                       // echo '<td><input type="text" name="prof-'.$profesoresAsignatura[1]->getId().'" id="prof-'.$profesoresAsignatura[1]->getId().'" readonly>'.$profesoresAsignatura[1]->getNombre().'<td>';
                                       if($profesoresAsignatura != null){
                                          echo '<td>'.$profesoresAsignatura[1]->getNombre().'</td>';
                                       }else{
                                          echo '<td>(Sin profesor)</td>';
                                       }

                                       foreach($trimestres as $tri){
                                          // Los 'id' de los input number (la nota) siguen un formato tri-1_cur-1_asig-1_prof-1
                                          if($profesoresAsignatura != null){
                                             $idNuevaNota = 'tri-'.$tri->getId().'_cur-'.$alumnoAEvaluar->getCurso()->getId().'_asig-'.$asig->getId().'_prof-'.$profesoresAsignatura[1]->getId();
                                          }else{
                                             //Si no existe el profesor se indica con el IdProfesor = 0
                                             $idNuevaNota = 'tri-'.$tri->getId().'_cur-'.$alumnoAEvaluar->getCurso()->getId().'_asig-'.$asig->getId().'_prof-0';
                                          }

                                          $notaAutocompletar = $alumnoAEvaluar->buscarNotaAlumno($alumnoAEvaluar->getCurso()->getId(), $asig->getId(), $tri->getId());

                                          if($notaAutocompletar != null){
                                                echo '<td class="tri-'.$tri->getId().'"><input type="number" name="'.$idNuevaNota.'" id="'.$idNuevaNota.'" class="calificacion calificacion-autocompletar" min="0" max="10" value="'.$notaAutocompletar->getNota().'"></td>';
                                          }else{
                                             echo '<td class="tri-'.$tri->getId().'"><input type="number" name="'.$idNuevaNota.'" id="'.$idNuevaNota.'" class="calificacion" min="0" max="10"></td>';
                                          }
                                       }
                                    echo '</tr>';
                                 }
                              echo '</table>';
                        ?>
                        <button class="btn btn-outline-success bt-custom-verde" id="bt-actualizar-notas" name="actualizarNotas">Aceptar</button>
                        <button class="btn btn-outline-danger bt-custom-rojo" id="bt-cancelar-actualizar-notas" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario; ?>')">Cancelar</button>
                     </fieldset> 
                  </form>
                  <button class="btn btn-outline-primary bt-custom-azul" id="bt-ver-notas-alumnoAEvaluar" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&ver_notas=1&idAlumnoAEvaluar='.$alumnoAEvaluar->getId(); ?>')">Ver notas <?php echo $alumnoAEvaluar->getNombre().' '.$alumnoAEvaluar->getApellidos(); ?></button>

                     <script>
                        $(document).ready(function(){
                           //Evito que el usuario introduzca por teclado caraceteres o más de 2 cifras en el campo nota, de esta manera se respeta el valor mínimo y máximo del input number
                           $('.calificacion').on('keydown', function (e) {
                              var numTecla = Number.parseInt(e.key);
                              //Hay que añadir el número introducido por teclado como una cadena ya que el campo todavía no tiene el valor (el evento se lanzó justo antes de establecerse el valor)
                              var valorInput = Number.parseInt(this.value.concat(numTecla));
                              var minNota = this.min;
                              var maxNota = this.max;
                              var maxDigitos = maxNota.toString().length;
                              if(numTecla <  minNota || numTecla > maxNota || valorInput > maxNota || valorInput.toString().length > maxDigitos){
                                 if(e.key != 'Backspace'){
                                    e.preventDefault();
                                    e.stopPropagation();
                                 }
                              }
                           });

                           //Inicializo la tabla a oculta para no mostrarla hasta que el usuario marque una opción
                           $("[class^=tri-]").hide();
                           $("#tb-actualizar-notas").hide();
                           
                           //A cada columna de la tabla le asocio un evento onchange, de forma que se mostrara toda esa columna si se ha selecionado su checkbox correspondiente.
                           <?php foreach($trimestres as $tri){ 
                              $chkOrigen = 'chk-tri-'.$tri->getId();
                              $columnaDestino = 'tri-'.$tri->getId();
                              ?>
                              $('#<?php echo $chkOrigen ?>').change(function() {
                                 $(".<?php echo $columnaDestino ?>").fadeToggle(this.checked);
                                 ToggleFormActualizarNotas();
                              });
                              
                           <?php } ?>
                           
                              //Marco o desmarco todas las casillas en el caso de marcar-desmarcar 'Evaluar Todas'.
                              $("#chk-tri-all").change(function() {
                                 if($("#chk-tri-all").is(':checked')){
                                    $("input[type=checkbox][id^=chk-tri-]").prop('checked', true);
                                    $("[class^=tri-]").fadeIn("slow");
                                 }else{
                                    $("input[type=checkbox][id^=chk-tri-]").prop('checked', false);
                                    $("[class^=tri-]").fadeOut("slow");
                                 }
                                 ToggleFormActualizarNotas();
                              });

                              //solo se mostrará la tabla si hay alguna opción marcada. El autoScroll solo se aplicará sólo si la tabla cambia su visibilidad (aparece o desparece), no siendo así cuando se muestra/oculta alguna de sus columnas estando la tabla visible.
                              function ToggleFormActualizarNotas(){
                                 if(Array.from(document.querySelectorAll("input[type=checkbox][id^=chk-tri-]")).some(input => input.checked)){
                                    if(!$("#tb-actualizar-notas").is(":visible")){
                                       $("#tb-actualizar-notas").slideDown("slow");
                                       smoothScrollJS('tb-actualizar-notas');
                                    }
                                 }else{
                                    $("#tb-actualizar-notas").slideUp("slow");
                                    smoothScrollJS('formActualizarNotas');
                                 }
                              }
                              //No funciona, no se encuentra la propiedad en el objeto tipo jquery, al parecer no es un objeto normal
                              // function hasValue(obj, key, value) {
                              //    console.log(obj, key, value);
                              //    console.log(obj.hasOwnProperty(key) && obj[key] === value);
                              //    return obj.hasOwnProperty(key) && obj[key] === value;
                              // }

                              $("#bt-actualizar-notas").click(function (e){
                                 e.preventDefault();
                                 e.stopPropagation();
                                 var calificaciones = $(".calificacion");
                                 var notas = [];
    
                                 calificaciones.each(function (){
                                    // Los 'id' de los campos siguen un formato tri-1_cur-1_asig-1_prof-1
                                    let campos = $(this).prop('id').split("_");
                                    let trimestreVal = campos[0].split("-")[1];
                                    let cursoVal = campos[1].split("-")[1];
                                    let asignaturaVal = campos[2].split("-")[1];
                                    let profesorVal = campos[3].split("-")[1];
                                    let calificacionVal = $(this).val();

                                    let nota = {
                                       "trimestre":trimestreVal,
                                       "curso":cursoVal,
                                       "asignatura":asignaturaVal,
                                       "profesor":profesorVal,
                                       "calificacion":calificacionVal
                                    };
                                    notas.push(nota);
                                 })
                                 enviarNotas(notas);

                                 function enviarNotas(notas) {
                                    $.ajax({
                                       type: "POST", url: "funciones-AJAX.php", data: { 'evaluar': 1, 'idAlumnoAEvaluar': <?php echo $alumnoAEvaluar->getId(); ?>, 'idProfesor': <?php echo $profesor->getId(); ?>, 'evaluacionesNotas': JSON.stringify(notas) },
                                       statusCode: {
                                          404: function() { alert('Página no encontrada'); }
                                       },
                                       beforeSend: function() {
                                          var mensajeProcesando = $('<span class="alert alert-primary" id="mensajeProcesando">Procesando la petición ...</span>');
                                          mostrarMensajeEsquina(mensajeProcesando);
                                       },
                                       complete: function(){
                                          $("#mensajeProcesando").remove();
                                       },
                                       success: function(result) {
                                          //Mensaje de ejemplo '<span class="alert alert-success">Se han actualizado las calificaciones correctamente</span>'
                                          if($(result).hasClass('alert-success')){
                                             //Cambio el color de las casillas que se han actualizado en la BD.
                                             calificaciones.each(function (){
                                                // Los 'id' de los campos siguen un formato tri-1_cur-1_asig-1_prof-1
                                                let campos = $(this).prop('id').split("_");
                                                let profesorVal = campos[3].split("-")[1];
                                                let calificacionVal = $(this).val();
                                                if(profesorVal != 0 && calificacionVal !== ''){
                                                   $(this).removeClass('calificacion-autocompletar');
                                                   $(this).addClass('calificacion-actualizada');
                                                }
                                             })
                                          }
                                          // mostrarMensajeEnCursor(result);
                                          mostrarMensajeEsquina(result);
                                          // mostrarToastEsquina(result);
                                       } 
                                    });
                                 }  
                              })
                        });
                     </script>
   
                     <?php 
                        // Desplaza el scroll hasta el formulario que se acaba de cargar. Dicho formulario fue accionado al seleccionar el boton de Dar de alta una incidencia
                        smoothScroll('formActualizarNotas');
                     ?>
                  <?php } ?>

                  <?php if($alumnoAEvaluar != null && $ver_notas == 1){ ?>
                     <!-- Button trigger modal -->
                     <button type="button" id="modalVerNotasAlumno" class="btn btn-primary" data-toggle="modal" data-target="#ModalCenterVerNotasAlumno" style="display:none;">
                        Ver notas del alumno
                     </button>

                     <!-- Modal -->
                     <div class="modal fade" id="ModalCenterVerNotasAlumno" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLongTitle">Ver notas del alumno <b><?php echo $alumnoAEvaluar->getNombre().' '.$alumnoAEvaluar->getApellidos(); ?></b></h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                           </div>
                           <div class="modal-body">
                              <table class="table-responsive d-flex flex-column justify-content-center">
                                 <?php foreach($trimestres as $tri){
                                    $notasPublicadas = false;
                                    $notasTrimestreCursoAlumno = $alumnoAEvaluar->getNotasTrimestreCurso($tri->getId(), $alumnoAEvaluar->getCurso()->getId());

                                    echo '<table class="table-bordered table-sm mw-100">';
                                    echo '<caption>Notas '.$tri->getNombre2().'</caption>';
                                    echo '<th>Asignatura</th>';
                                    echo '<th>Profesor</th>';
                                    echo '<th>Nota</th>';

                                    //Hay que evitar recorrer un array nulo con foreach, se produciría un error
                                    if(!empty($notasTrimestreCursoAlumno)){
                                       $notasPublicadas = true;
                                       foreach($notasTrimestreCursoAlumno as $nota){
                                          $profesorAsignatura = BD::obtenerProfesor($nota->getIdProfesor());
                                          echo '<tr>';
                                             echo '<td>'.$alumnoAEvaluar->getCurso()->getAsignaturaId($nota->getIdAsignatura())->getNombre().'</td>';
                                             echo '<td>'.$profesorAsignatura->getNombre().' '.$profesorAsignatura->getApellidos().'</td>';
                                             echo '<td>'.$nota->getNota().'</td>';
                                          echo '</tr>';
                                       }
                                    }
                                    if(!$notasPublicadas) echo '<tr><td colspan="3">No se han publicado las notas</td></tr>';

                                    echo '</table><br>';
                                 }?>
                              </table>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                              <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                           </div>
                        </div>
                     </div>
                     </div>
                     <script>document.getElementById("modalVerNotasAlumno").click();</script>
                  <?php } ?>
               </div>
               <?php } ?>

               <?php // Pestana Profesor, TRIMESTRES?> 
               <?php if($pestana == 'trimestres'){ ?>
                  <div id="trimestres">
                  <div class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered table-sm">
                        <caption>Trimestres</caption>
                        <th>Periodo</th>
                        <th>Evaluación</th>
                        <th>Orden</th>
                        <?php
                        foreach($trimestres as $tri){
                           echo '<tr>';
                              echo '<td>'.$tri->getNombre().'</td>';
                              echo '<td>'.$tri->getNombre2().'</td>';
                              echo '<td>'.$tri->getOrden().'</td>';
                           echo '</tr>';
                        }
                        ?>
                     </table>
                  </div>
                  </div>
               <?php } ?>

               <?php // Pestana Profesor, VISTA RESUMEN?> 
               <?php if($pestana == 'vista-resumen'){ ?>
                  <div id="vista_resumen">
                  <div class="table-responsive d-flex flex-column align-items-center">
                     <?php
                        echo '<div class="tab">';
                        foreach($cursos as $curso){
                        if($curso->getId() == 1){
                           echo '<button class="tablinks" onclick="openMenu(event, \'curso_'.$curso->getId().'\')"  id="defaultOpen">'.$curso->getNombre().'</button>';
                        }else{
                           echo '<button class="tablinks" onclick="openMenu(event, \'curso_'.$curso->getId().'\')">'.$curso->getNombre().'</button>';
                        }
                        }
                        echo '</div>';
                        ?>
                     <p>Todos los alumnos, con su curso y una asignatura alteatoria de su curso. Ordenado por curso y apellidos.</p>
                     <?php
                        foreach($cursos as $curso){
                        echo '<div id="curso_'.$curso->getId().'" class="tabcontent">';
                        echo '<table class="table-bordered d-flex flex-column table-sm">';
                           echo '<caption>'.$curso->getNombre().'</caption>';
                           echo '<th>Usuario Alumno</th>';
                           echo '<th>Nombre</th>';
                           echo '<th>Apellidos</th>';
                           echo '<th>Nombre Asignatura aleatoria del curso</th>';
                           foreach ($todosAlumnos as $al){
                              if ($al->getCurso()->getId() != $curso->getId()) continue;
                              if ($al->getActivo() == 0) echo '<tr class=filaInactivo>';else echo '<tr>';
                              echo '<td>'.$al->getUsuario().'</td>';
                              echo '<td>'.$al->getNombre().'</td>';
                              echo '<td>'.$al->getApellidos().'</td>';
                              $numAleatorio = rand(1,count($al->getCurso()->getAsignaturas()));
                              $asignatura = $al->getCurso()->getAsignaturas()[$numAleatorio];
                              echo '<td>'.$asignatura->getNombre().' -- id ('.$asignatura->getId().')</td>';
                              echo '</tr>';
                           }
                        echo '</table>';
                        echo '</div>';
                        }
                     ?>
                  </div>
                  </div>
               <?php } ?>

               <?php // Pestana Profesor. AVISOS ?> 
               <?php if($pestana == 'avisos'){ ?>
                  <div id="avisos" class="text-center">
                  <?php
                     foreach($notificarAlertas as $aviso){
                        echo '<h4>'.$aviso->getDescripcion().'</h4>';
                     }
                     if(empty($notificarAlertas)) echo '<h4>No existen avisos. El centro permanecerá abierto</h4>';
                  ?>
                  </div>
               <?php } ?>
               
               <?php // Pestana Profesor, INCIDENCIAS?> 
               <?php if($pestana == 'incidencias'){ ?>
                  <div id="incidencias">
                     <div class="table-responsive d-flex flex-column align-items-center
                     ">
                        <div class="tab">
                           <button class="tablinks" onclick="openMenu(event, 'incidencias_1')"  id="defaultOpen">Incidencias</button>
                           <button class="tablinks" onclick="openMenu(event, 'incidencias_2')">Ver histórico incidencias</button>
                        </div>
                        
                        <div id="incidencias_1" class="tabcontent">
                        <table class="table-bordered d-flex flex-column table-sm">
                           <caption>Incidencias, ordenadas por fecha de modificación</caption>
                           <th>Caso</th>
                           <th>Profesor</th>
                           <th>Estado</th>
                           <th>Detalles</th>
                           <th>Última modificación</th>
                           <th>Acciones</th>
                           <?php
                              foreach($todasIncidencias as $incidencia){
                                 $profesorIncidencia = BD::obtenerProfesor($incidencia->getIdProfesor());
                                 echo '<tr class="incidenciaEstado_'.$incidencia->getEstado().'">';
                                    echo '<td>'.$incidencia->getId().'</td>';
                                    echo '<td>'.$profesorIncidencia->getNombre().'</td>';
                                    echo '<td>';
                                    switch($incidencia->getEstado()){
                                       case 0: echo 'Sin resolver'; break;
                                       case 1: echo 'En espera'; break;
                                       case 2: echo 'Resuelta'; break;
                                    }
                                    echo '</td>';
                                    echo '<td>'.$incidencia->getDetalles().'</td>';
                                    echo '<td>'.$incidencia->getFecha().'</td>';
                                    echo '<td>';
                                    // if ($incidencia->getEstado() == 0 || $incidencia->getEstado() == 1){
                                       echo '<button class="btn btn-outline-primary btn-sm bt-custom-azul bt-custom-azul" onclick="location.replace(\''.$_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana='.$pestana.'&idIncidencia='.$incidencia->getId().'&formActualizarIncidencia=1'.'\')">Actualizar estado</button>';
                                    // }
                                       echo '<button class="btn btn-outline-primary btn-sm bt-custom-azul" onclick="location.replace(\''.$_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana='.$pestana.'&idIncidencia='.$incidencia->getId().'&verHistorialIncidencia=1'.'\')">Ver historial</button>';
                                    // }
                                    '</td>';
                                 echo '</tr>';
                              }
                           ?>
                        </table>
                        </div>

                        <div id="incidencias_2" class="tabcontent">
                        <table class="table-bordered table-sm">
                           <caption>Histórico de incidencias</caption>
                           <th>Caso</th>
                           <th>Profesor</th>
                           <th>Estado</th>
                           <th>Detalles</th>
                           <th>Fecha</th>
                           <?php
                           foreach($todasIncidenciasLog as $incidencia){
                              $profesorIncidencia = BD::obtenerProfesor($incidencia->getIdProfesor());
                              echo '<tr class="incidenciaEstado_'.$incidencia->getEstado().'">';
                                 echo '<td>'.$incidencia->getId().'</td>';
                                 echo '<td>'.$profesorIncidencia->getNombre().'</td>';
                                 echo '<td>';
                                 switch($incidencia->getEstado()){
                                    case 0: echo 'Sin resolver'; break;
                                    case 1: echo 'En espera'; break;
                                    case 2: echo 'Resuelta'; break;
                                 }
                                 echo '</td>';
                                 echo '<td>'.$incidencia->getDetalles().'</td>';
                                 echo '<td>'.$incidencia->getFecha().'</td>';
                              echo '</tr>';
                           }
                           ?>
                        </table>
                        </div>

                        <?php if($verHistorialIncidencia == 1){ ?>
                           <!-- Button trigger modal -->
                           <button type="button" id="btVerhistorial" class="btn btn-primary" data-toggle="modal" data-target="#ModalCenterHistorialIncidencia" style="display:none;">
                              Ver historial incidencia
                           </button>

                           <!-- Modal -->
                           <div class="modal fade" id="ModalCenterHistorialIncidencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                           <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                 <h5 class="modal-title" id="exampleModalLongTitle">Historial Incidencia Caso <?php echo $idIncidencia ?></h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button>
                                 </div>
                                 <div class="modal-body">
                                    <table class="table-bordered table-sm">
                                       <th>Profesor</th>
                                       <th>Estado</th>
                                       <th>Detalles</th>
                                       <th>Fecha</th>
                                       <?php
                                       foreach($historialIncidencia as $incidencia){
                                          $profesorIncidencia = BD::obtenerProfesor($incidencia->getIdProfesor());
                                          echo '<tr class="incidenciaEstado_'.$incidencia->getEstado().'">';
                                             echo '<td>'.$profesorIncidencia->getNombre().'</td>';
                                             echo '<td>';
                                             switch($incidencia->getEstado()){
                                                case 0: echo 'Sin resolver'; break;
                                                case 1: echo 'En espera'; break;
                                                case 2: echo 'Resuelta'; break;
                                             }
                                             echo '</td>';
                                             echo '<td>'.$incidencia->getDetalles().'</td>';
                                             echo '<td>'.$incidencia->getFecha().'</td>';
                                          echo '</tr>';
                                       }
                                       ?>
                                    </table>
                                 </div>
                                 <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                 <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                 </div>
                              </div>
                           </div>
                           </div>

                           <script>document.getElementById("btVerhistorial").click();</script>
                        <?php } ?><br>

                        <div id="divBotonesTablaIncidencias">
                           <button class="btn btn-outline-primary bt-custom-azul" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&formDarAltaIncidencia=1'.'&pestana='.$pestana; ?>')">Dar de alta una incidencia</button>
                        </div>
               

                        <?php // Formulario para DAR DE ALTA UNA INCIDENCIA ?>
                        <?php if (isset($formDarAltaIncidencia) && $formDarAltaIncidencia==1) { ?> 
                           <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="formDarAltaIncidencia">
                           <fieldset class="border p-2">    
                           <legend class="w-auto">Dar de alta una incidencia</legend>
                           <label for="detallesIncidenciaTextArea">Detalles:</label>
                           <textarea id="detallesIncidenciaTextArea" cols="30" rows="3" placeholder="Breve descripción de la incidencia" title="Se creará una nueva incidencia y se actualizará el log de incidencias" required></textarea><br><br>             

                           <input type="hidden" name="detallesIncidencia" id="detallesIncidencia"/><br /><br />
                           <input type="submit" id="darAltaIncidencia" name="darAltaIncidencia" value="Dar alta incidencia" class="btn btn-outline-success bt-custom-verde" />
                           <button class="btn btn-outline-danger bt-custom-rojo" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana='.$pestana; ?>')">Cancelar</button>
                           </fieldset> 
                           </form>
                           
                           <?php 
                           // Desplaza el scroll hasta el formulario que se acaba de cargar. Dicho formulario fue accionado al seleccionar el boton de Dar de alta una incidencia
                           smoothScroll('formDarAltaIncidencia');
                           ?>
                        <?php } ?>

                        <?php // Formulario para ACTUALIZAR UNA INCIDENCIA ?>
                        <?php if (isset($formActualizarIncidencia) && $formActualizarIncidencia==1) { ?> 
                           <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="formActualizarIncidencia">
                           <fieldset class="border p-2">    
                           <legend class="w-auto">Actualizar una incidencia</legend>

                           <label for="detallesOriginal">Detalles de la incidencia (caso <?php echo $idIncidencia; ?>):</label>
                           <textarea id="detallesOriginal" cols="30" rows="3" readonly><?php echo BD::obtenerIncidencia($idIncidencia)->getDetalles(); ?></textarea><br><br>

                           <label for="nuevoEstadoIncidencia">Nuevo estado:</label>
                           <select name="nuevoEstadoIncidencia" id="nuevoEstadoIncidencia" required>
                              <option value="0">Sin Resolver</option>
                              <option value="1">En Espera</option>
                              <option value="2">Resuelta</option>
                           </select><br /><br />

                           <label for="nuevosDetallesIncidenciaTextArea">Nuevos detalles:</label>
                           <textarea id="nuevosDetallesIncidenciaTextArea" cols="30" rows="3" placeholder="Breve descripción de la incidencia" title="Se creará una nueva incidencia y se actualizará el log de incidencias" required></textarea><br><br>

                           <input type="hidden" name="nuevosDetallesIncidencia" id="nuevosDetallesIncidencia"/><br /><br />
                           
                           <input type="submit" id="actualizarIncidencia" name="actualizarIncidencia" value="Actualizar incidencia" class="btn btn-outline-success bt-custom-verde" />
                           <button class="btn btn-outline-danger bt-custom-rojo" onclick="location.replace('<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idProfesor.'&usuario='.$usuario.'&pestana='.$pestana; ?>')">Cancelar</button>
                           <input type="hidden" id="idIncidencia" name="idIncidencia" value="<?php echo $idIncidencia; ?>" readonly><br><br>
                           </fieldset> 
                           </form>
                           
                           <?php 
                           // Desplaza el scroll hasta el formulario que se acaba de cargar. Dicho formulario fue accionado al seleccionar el boton de Actualizar estado
                           smoothScroll('formActualizarIncidencia');
                           ?>
                        <?php } ?>
                     </div>
                  </div>
               <?php } ?>


            </div>
         </div>
      </div>
   </div>
<?php //----------------FIN EXTRANET PROFESOR---------------------- ?>

