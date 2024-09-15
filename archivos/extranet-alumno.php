<?php
//Inicializo variables y arrays
  $tutoresAlumno = null;
  $compañerosAlumno = null;
  $cursos = null;
  $trimestres = null;

   //Datos del login pasados por la url. Comprobación e inicialización.
   $tipoUsuario = (isset($_SESSION['tipoUsuario'])) ? htmlspecialchars($_SESSION['tipoUsuario'], ENT_QUOTES) : '';
   $idAlumno = (isset($_SESSION['id']) && $tipoUsuario=='alumno') ? htmlspecialchars($_SESSION['id'], ENT_QUOTES) : '';
   $usuario = (isset($_SESSION['usuario'])) ? htmlspecialchars($_SESSION['usuario'], ENT_QUOTES) : '';
   $alumno = (isset($_SESSION['alumno'])) ? $_SESSION['alumno'] : '';

   //Varible para la barra de navegación y las pestañas de Bootstrap
   if($pestana == 'default') $pestana = 'perfil';

     // CONSULTAS A LA BASE DE DATOS
  // -------------CONSULTAS-BD------------------
  
  // Consultas para un usuario tipo "alumno"
   $tutoresAlumno = BD::obtenerProfesoresCurso($alumno->getCurso()->getId());
   $compañerosAlumno = BD::obtenerAlumnosProfesor($tutoresAlumno[1]->getId());
   $cursos = BD::obtenerCursos();
   $trimestres = BD::obtenerTrimestres();

   $notificarAlertas = BD::comprobarAlertas($tiempoHoy['tempMaxima'], $tiempoHoy['viento']);
   ?>

   <?php //EXTRANET PARA UN USURIO DE TIPO ALUMNO ?>

   <nav class="navbar navbar-expand-sm sticky-top bg-dark navbar-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
         <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='perfil') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=perfil'; ?>">Perfil</a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='companeros') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=companeros'; ?>">Compañeros</a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='asignaturas') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=asignaturas'; ?>">Asignaturas</a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='notas') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=notas'; ?>">Notas</a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='oferta-cursos') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=oferta-cursos'; ?>">Oferta Cursos</a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='trimestres') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=trimestres'; ?>">Trimestres</a>
            </li>
            <li class="nav-item">
            <a class="nav-link <?php if($pestana=='avisos') echo 'active'; ?>" href="<?php echo $_SERVER['PHP_SELF'].'?tipoUsuario='.$tipoUsuario.'&id='.$idAlumno.'&usuario='.$usuario.'&pestana=avisos'; ?>">Avisos</a>
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
            <div id="extranet_alumno" class="table table-striped table-hover">

               <?php // Pestana 1 Alumno. PERFIL ?> 
               <?php if($pestana == 'perfil'){ ?>
                  <div id="perfil_alumno" class="table table-striped table-hover d-flex flex-column align-items-center">
                     <div class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered">
                        <caption>Alumno</caption>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Curso</th>
                        <?php
                        echo '<tr>';
                           echo '<td>'.$alumno->getUsuario().'</td>';
                           echo '<td>'.$alumno->getNombre().'</td>';
                           echo '<td>'.$alumno->getApellidos().'</td>';
                           echo '<td>'.$alumno->getTelefono().'</td>';
                           echo '<td>'.$alumno->getEmail().'</td>';
                           echo '<td>'.$alumno->getCurso()->getNombre().'</td>';
                        echo '</tr>';
                        ?>
                     </table>
                     </div>
                     <br>
                     
                     <div class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered">
                        <?php if(count($tutoresAlumno) == 1){
                           echo '<caption>Tutor de tu curso</caption>';
                        }else{
                           echo '<caption>Tutores de tu curso</caption>';
                        }
                        ?>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <?php
                           foreach ($tutoresAlumno as $p){
                           echo '<tr>';
                           echo '<td>'.$p->getNombre().'</td>';
                           echo '<td>'.$p->getApellidos().'</td>';
                           echo '<td>'.$p->getEmail().'</td>';
                           echo '</tr>';
                           }
                        ?>
                     </table>
                     </div>
                     <br>
                  </div>
               <?php } ?>

               <?php // Pestana 2 Alumno. COMPAÑEROS ?> 
               <?php if($pestana == 'companeros'){ ?>
                  <div id="companeros_alumno" class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered table-sm">
                     <caption>Mis compañeros de curso</caption>
                     <th>Nombre</th>
                     <th>Apellidos</th>
                     <th>Email</th>
                     <?php
                        foreach ($compañerosAlumno as $al){
                           echo '<tr>';
                           echo '<td>'.$al->getNombre().'</td>';
                           echo '<td>'.$al->getApellidos().'</td>';
                           echo '<td>'.$al->getEmail().'</td>';
                           echo '</tr>';
                        }
                     ?>
                     </table>
                  </div>
               <?php } ?>

               <?php // Pestana Alumno. MIS ASIGNATURAS ?> 
               <?php if($pestana == 'asignaturas'){ ?>
                  <div id="asignaturas_alumno" class="table-responsive d-flex justify-content-center">
                     <table class="table-bordered sm">
                     <caption>Mis asignaturas del curso</caption>
                     <th>Nombre</th>
                     <th>Nombre corto</th>
                     <?php
                        foreach($alumno->getCurso()->getAsignaturas() as $asig){
                           echo '<tr>';
                              echo '<td>'.$asig->getNombre().'</td>';
                              echo '<td>'.$asig->getNombreCorto().'</td>';
                           echo '</tr>';
                        }
                     ?>
                     </table>
                  </div>
               <?php } ?>

               <?php // Pestana Alumno. MIS NOTAS ?> 
               <?php if($pestana == 'notas'){ ?>
                  <div id="notas_alumno" class="table-responsive d-flex flex-column justify-content-center">
                     <?php foreach($trimestres as $tri){
                        $notasPublicadas = false;
                        $notasTrimestreCursoAlumno = $alumno->getNotasTrimestreCurso($tri->getId(), $alumno->getCurso()->getId());
                        
                        echo '<table class="table-bordered table-sm">';
                        echo '<caption>Notas '.$tri->getNombre().'</caption>';
                        echo '<th>Asignatura</th>';
                        echo '<th>Profesor</th>';
                        echo '<th>Nota</th>';

                        // var_dump($alumno->getNotasCurso($alumno->getCurso()));
                        //Hay que evitar recorrer un array nulo con foreach, se produciría un error
                        if(!empty($notasTrimestreCursoAlumno)){
                           $notasPublicadas = true;
                           foreach($notasTrimestreCursoAlumno as $nota){
                                 $profesorAsignatura = BD::obtenerProfesor($nota->getIdProfesor());
                                 echo '<tr>';
                                 echo '<td>'.$alumno->getCurso()->getAsignaturaId($nota->getIdAsignatura())->getNombre().'</td>';
                                 echo '<td>'.$profesorAsignatura->getNombre().' '.$profesorAsignatura->getApellidos().'</td>';
                                 echo '<td>'.$nota->getNota().'</td>';
                                 echo '</tr>';
                           }
                        }
                        if(!$notasPublicadas) echo '<tr><td colspan="3">No se han publicado las notas</td></tr>';
                        echo '</table><br>';
                     }?>
                  </div>
               <?php } ?>

               <?php // Pestana Alumno. OFERTAS CURSOS ?> 
               <?php if($pestana == 'oferta-cursos'){ ?>
                  <div id="cursos" class="d-flex flex-row">
                     <div class="table-responsive d-flex justify-content-around">
                     <table id="tablaTotalCursos" class="table-bordered table-sm">
                        <caption>Oferta Cursos</caption>
                        <th>Nombre</th>
                        <?php 
                           foreach($cursos as $cur){
                           echo '<tr>';
                              echo '<td>'.$cur->getNombre().'</td>';
                           echo '</tr>';
                           }
                        ?>
                     </table>
                     </div>
                  </div>
               <?php } ?>

               <?php // Pestana Alumno. TRIMESTRES ?> 
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

               <?php // Pestana Alumno. AVISOS ?> 
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

            </div>
         </div>
      </div>
   </div>
<?php //----------------FIN EXTRANET ALUMNO---------------------- ?>
