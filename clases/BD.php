<?php 
	class BD{
		private $sql_host;
		private $sql_usuario; 
		private $sql_pass; 
      private $sql_db;

      // Conexión a BBDD MySql
		public function __construct($sql_host, $sql_usuario, $sql_pass, $sql_db) {
			$this->setSql_host($sql_host);
			$this->setSql_usuario($sql_usuario);
			$this->setSql_pass($sql_pass);
			$this->setSql_db($sql_db);
     	}
		
		public function getSql_host() {
			return $this->sql_host; 
		}
		public function getSql_usuario() {
			return $this->sql_usuario; 
		}
      	public function getSql_pass() {
			return $this->sql_pass; 
		}
		public function getSql_db() {
			return $this->sql_db; 
		}
		
		private function setSql_host($sql_host){
			if (!empty(trim($sql_host)) && $sql_host != null){
				$this->sql_host = $sql_host;
			}
		}
		private function setSql_usuario($sql_usuario){
			if (!empty(trim($sql_usuario)) && $sql_usuario != null){
				$this->sql_usuario = $sql_usuario;
			}
    	 }
		private function setSql_pass($sql_pass){
			if (!empty(trim($sql_pass)) && $sql_pass != null){
				$this->sql_pass = $sql_pass;
			}
      	}
		private function setSql_db($sql_db){
			if (!empty(trim($sql_db)) && $sql_db != null){
				$this->sql_db = $sql_db;
			}
		}    

      // Conexión a BBDD MySql
      public static function conectarBD($bdInfo){
         $conexion = new mysqli($bdInfo->getSql_host(), $bdInfo->getSql_usuario(), $bdInfo->getSql_pass(), $bdInfo->getSql_db());
         if ($conexion->connect_error) { die('Error de Conexión ('.$conexion->connect_errno.') '.$conexion->connect_error); }
         mysqli_set_charset($conexion,"utf8");
         return $conexion;
      }
      
      // Cierra la conexión con MySql.
      public static function desconectarBD($conexion){
         $conexion->close();
		}
		
		// Matricular alumno. Estilo POO.
		public static function matricularAlumnoPOO($alumno, $nuevaContrasena, $algoritmoContrasena){
			matricularAlumno($alumno->getUsuario(), $nuevaContrasena, $alumno->getNombre(), $alumno->getApellidos(), $alumno->getTelefono(), $alumno->getEmail(), $alumno->getCurso(), $algoritmoContrasena);
		}

		public static function darBajaAlumno($usuarioADarBaja){
			//Creo la consulta para dar de baja al alumno solo la primera vez que ejecuto esta función.
			global $consultaDarBajaAlumno;
			global $bd;
			if($consultaDarBajaAlumno == null){
				$consultaDarBajaAlumno = $bd->stmt_init();
				$consultaDarBajaAlumno->prepare("UPDATE ies_alumno SET activo=0 WHERE id=(SELECT id FROM ies_alumno WHERE usuario=?) ;");
			}
			//los parámetros deben siempre pasarte como variables, nunca como constantes ya que entonces se produce un error
			$consultaDarBajaAlumno->bind_param("s", $usuarioADarBaja);
			$consultaDarBajaAlumno->execute();
			if ($consultaDarBajaAlumno->affected_rows >= 1){
				redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=6');
			}else{
				if(buscarUsuarioPorNombre('alumno', $usuarioADarBaja) == null){
					redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=12');
				}else{
					redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=16');
				}
			}
			// $consultaDarBajaAlumno->free();
		}
		
		public static function darAltaAlumno($usuarioADarAlta){
			//Creo la consulta para dar de alta al alumno solo la primera vez que ejecuto esta función.
			global $consultaDarAltaAlumno;
			global $bd;
			if($consultaDarAltaAlumno == null){
				$consultaDarAltaAlumno = $bd->stmt_init();
				$consultaDarAltaAlumno->prepare("UPDATE ies_alumno SET activo=1 WHERE id=(SELECT id FROM ies_alumno WHERE usuario=?) ;");
			}
			//los parámetros deben siempre pasarte como variables, nunca como constantes ya que entonces se produce un error
			$consultaDarAltaAlumno->bind_param("s", $usuarioADarAlta);
			$consultaDarAltaAlumno->execute();

			if ($consultaDarAltaAlumno->affected_rows >= 1){
				redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=10');
			}else{
				if(self::buscarUsuarioPorNombre('alumno', $usuarioADarAlta) == null){
					redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=14');
				}else{
				redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=15');
				}
			}
			// $consultaDarAltaAlumno->free();
		}
		
		public static function buscarUsuarioPorNombre ($tipoUsuario, $nombreUsuario){
			global $bd;
			if($tipoUsuario == 'alumno'){
				$resultadoBusqueda = $bd->query("SELECT id FROM ies_alumno WHERE usuario='".$nombreUsuario."';");
				$row=$resultadoBusqueda->fetch_array(MYSQLI_NUM);
				if($row != null) return self::obtenerAlumno($row[0]); else return null;
			}
			if($tipoUsuario == 'profesor'){
				$resultadoBusqueda = $bd->query("SELECT id FROM ies_profesor WHERE usuario='".$nombreUsuario."';");
				$row=$resultadoBusqueda->fetch_array(MYSQLI_NUM);
				if($row != null) return self::obtenerProfesor($row[0]); else return null;
			}
		}

		public static function actualizarUltimoLogin ($ultimo_login){
			global $tipoUsuario;
			global $idAlumno;
			global $idProfesor;
			global $bd;
			
			$fechaLogin = date("d/m/y \a \l\a\s H:i", $ultimo_login);
			if($tipoUsuario == 'alumno'){
			  $bd->query("UPDATE ies_alumno SET ultimo_login='".$fechaLogin."' WHERE id='".$idAlumno."' ;");
			}
			if($tipoUsuario == 'profesor'){
			  $bd->query("UPDATE ies_profesor SET ultimo_login='".$fechaLogin."' WHERE id='".$idProfesor."' ;");
			}
		}


		// CONSULTAS A LA BASE DE DATOS
  		// -------------CONSULTAS-BD------------------
      
		public static function obtenerAlumno($idAlumno){
			global $bd;
			//obtengo los datos del alumno
			$consultaAlumno = $bd->query("SELECT al.id, al.usuario, al.nombre, al.apellidos, al.telefono, al.email, al.curso, al.activo FROM ies_alumno al WHERE al.id='".$idAlumno."' ;");

			// Obtengo la información del curso del alumno
			if (($rowAlumno = $consultaAlumno->fetch_array(MYSQLI_ASSOC)) == null){
				return null; //alumno no encontrado
			}else{
				$cursoAuxiliar = $rowAlumno['curso'];
				$consultaCursoAlumno = $bd->query("SELECT cur.id, cur.nombre FROM ies_curso cur WHERE cur.id='".$cursoAuxiliar."' ;");

				// Obtengo la información de las asignaturas del curso del alumno    
				$consultaAsignaturasCurso = $bd->query("SELECT asig.id, asig.nombre, asig.nombre_corto, asig.curso FROM ies_asignatura asig WHERE asig.curso='".$cursoAuxiliar."' ;");
				
				// Obtengo la información de las notas del alumno    
				$notasAlumno = self::obtenerNotasAlumno($idAlumno);
				
				//Creacion del objeto alumno.
				// Primero debo crear los objetos que no continen otros objetos (composición).
				//Creo primero el array de asignaturas
				$asignaturasCurso = Asignatura::creacionAsignaturasPorQuery($consultaAsignaturasCurso);
				// Creo el curso al que pertenece el alumno
				$rowCurso = $consultaCursoAlumno->fetch_array(MYSQLI_ASSOC);
				$cursoAlumno = new Curso($rowCurso['id'], $rowCurso['nombre'], $asignaturasCurso);
				
				// Creo el alumno
				$alumno = new Alumno($rowAlumno['id'], $rowAlumno['usuario'], $rowAlumno['nombre'], $rowAlumno['apellidos'], $rowAlumno['telefono'], $rowAlumno['email'], $cursoAlumno, $rowAlumno['activo'], $notasAlumno);

				$consultaAlumno->free();
				$consultaCursoAlumno->free();
				$consultaAsignaturasCurso->free();

				return $alumno;
			}
		}

		public static function loginAlumno($usuario, $contrasena, $encriptacion){
			global $bd;
			$rowAlumno = null;
			//obtengo los datos del alumno
			switch($encriptacion){
				case null:
					$consultaAlumno = $bd->query("SELECT id FROM ies_alumno WHERE usuario='".$usuario."' AND pass='".$contrasena."' ;");
					$rowAlumno = $consultaAlumno->fetch_array(MYSQLI_ASSOC);
					break;
				case 'md5':
					$consultaAlumno = $bd->query("SELECT id FROM ies_alumno WHERE usuario='".$usuario."' AND pass='".md5($contrasena)."' ;");
					$rowAlumno = $consultaAlumno->fetch_array(MYSQLI_ASSOC);
					break;
				case 'blowfish':
					$hashContrasenaAlumno = $bd->query("SELECT pass FROM ies_alumno WHERE usuario='".$usuario."';");
					$hashContrasenaAlumno = $hashContrasenaAlumno->fetch_array(MYSQLI_NUM);
					if($hashContrasenaAlumno) $hashContrasenaAlumno = $hashContrasenaAlumno[0];
					// Función password_verify()
					// Comprueba que el hash proporcionado coincida con la contraseña facilitada.
					// Observe que password_hash() devuelve el algoritmo, el coste y el salt como parte del hash devuelto. Por lo tanto, toda la información que es necesaria para verificar el hash está incluida. Esto permite a la función de verificación comprobar el hash sin la necesidad de almacenar por separado la información del salt o del algoritmo.
					// Esta función es segura contra ataques basado en tiempo.
					if(password_verify($contrasena, $hashContrasenaAlumno)){
						$consultaAlumno = $bd->query("SELECT id FROM ies_alumno WHERE usuario='".$usuario."';");
						$rowAlumno = $consultaAlumno->fetch_array(MYSQLI_ASSOC);
					}
					break;
			}

			if ($rowAlumno != null){
				return self::obtenerAlumno($rowAlumno['id']);
			}else{
				return null; //alumno no encontrado
			}
		}

		public static function obtenerAlumnos(){
			global $bd;
			//obtengo los datos del los alumnos, unos las tablas 'ies_alumno ies_curso ies_asignatura' y aplico alias a las columnas para diferencialas
			$consulta = $bd->query("SELECT al.id as idAlumno, al.usuario, al.nombre as nombreAlumno, al.apellidos, al.telefono, al.email, al.curso as cursoAlumno, al.activo, cur.id as idCurso, cur.nombre as nombreCurso FROM ies_alumno al INNER JOIN ies_curso cur on cur.id=al.curso ORDER BY al.curso, al.apellidos ;");

			$alumnos = array();
			$asignaturas = array();
			while(($datos = $consulta->fetch_array(MYSQLI_ASSOC)) != null){
				// Creo un objeto alumno por cada fila devuelta de la consulta
				// Para ello tendré que crear al mismo tiempo los cursos, con sus respectivas asignaturas para cada alumno
				$asignaturas = self::obtenerAsignaturasCurso($datos['idCurso']);
				$notas = self::obtenerNotasAlumno($datos['idAlumno']);
				$alumnos[] = new Alumno($datos['idAlumno'], $datos['usuario'], $datos['nombreAlumno'], $datos['apellidos'], $datos['telefono'], $datos['email'], new Curso($datos['idCurso'], $datos['nombreCurso'], $asignaturas), $datos['activo'], $notas);
			}

			$consulta->free();

			return $alumnos;
		}

		public static function obtenerAlumnosCurso($idCurso){
			// Exactamente igual que la funcion obtenerAlumnos pero estableciendo una condición WHERE en la consulta SQL.
			global $bd;
			//obtengo los datos del los alumnos, unos las tablas 'ies_alumno ies_curso ies_asignatura' y aplico alias a las columnas para diferencialas
			$consulta = $bd->query("SELECT al.id as idAlumno, al.usuario, al.nombre as nombreAlumno, al.apellidos, al.telefono, al.email, al.curso as cursoAlumno, al.activo, cur.id as idCurso, cur.nombre as nombreCurso FROM ies_alumno al INNER JOIN ies_curso cur on cur.id=al.curso WHERE al.curso ='".$idCurso."' ORDER BY al.apellidos ;");
			
			$alumnos = array();
			$asignaturas = array();
			while(($datos = $consulta->fetch_array(MYSQLI_ASSOC)) != null){
				// Creo un objeto alumno por cada fila devuelta de la consulta
				// Para ello tendré que crear al mismo tiempo los cursos, con sus respectivas asignaturas para cada alumno
				$asignaturas = self::obtenerAsignaturasCurso($datos['idCurso']);
				$notas = self::obtenerNotasAlumno($datos['idAlumno']);
				$alumnos[] = new Alumno($datos['idAlumno'], $datos['usuario'], $datos['nombreAlumno'], $datos['apellidos'], $datos['telefono'], $datos['email'], new Curso($datos['idCurso'], $datos['nombreCurso'], $asignaturas), $datos['activo'], $notas);
			}

			$consulta->free();

			return $alumnos;
		}

		// Método redundante ya que se solo llama al método obtenerAlumnosCurso()
		public static function obtenerAlumnosProfesor($idProfesor){
			$profesor = self::obtenerProfesor($idProfesor);
			return self::obtenerAlumnosCurso($profesor->getTutorCurso());
		}

		public static function obtenerNotasAlumno($idAlumno){
			global $bd;
			$consultaNotasAlumno = $bd->query("SELECT n.curso, n.asignatura, n.profesor, n.trimestre, n.nota FROM ies_notas n WHERE n.alumno='".$idAlumno."' ORDER BY n.trimestre ASC ;");
			$notas = Nota::creacionNotasPorQuery($consultaNotasAlumno);
			$consultaNotasAlumno->free();

			return $notas;	
		}

		public static function actualizarNotas($idAlumnoAEvaluar, $idProfesor, $notas){
         global $consultaActualizarNotas;
			global $bd;

			//Primero debo comprobar si el profesor es tutor del alumno. Solo pueden ser modificadas las notas del alumno por su tutor
			if (!self::comprobarEsTutorAlumno($idProfesor, $idAlumnoAEvaluar)){
				$_SESSION['error'] = 21;
				print(mostrarMensajeERR(21));
			}

			if($consultaActualizarNotas == null){
				$consultaActualizarNotas = $bd->stmt_init();
				$consultaActualizarNotas->prepare("INSERT INTO ies_notas (curso, asignatura, alumno, profesor, trimestre, nota) VALUES (?, ?, ?, ? ,?, ?) ON DUPLICATE KEY UPDATE nota=?;");
			}

			//$bd->affected_rows no está funcionando correctamente, utilizo en su lugar un centinela
			$cambiosEnBD = false;
			
			foreach($notas as $nota){
				//los parámetros deben siempre pasarte como variables, nunca como constantes ya que entonces se produce un error
				$idCursoNota = $nota->getIdCurso();
				$idAsignatura = $nota->getIdAsignatura();
				$idProfesor = $nota->getIdProfesor();
				$idTrimestre = $nota->getIdTrimestre();
				$calificacion = $nota->getNota();
				
				// No se insertarán las calificaciones con alguna de la claves primarias vacias. Por ejemplo, a veces no se ha establecido el profesor para una asignatura.
				if(!empty($idCursoNota) && !empty($idAsignatura) && !empty($idAlumnoAEvaluar) && !empty($idProfesor) && !empty($idTrimestre) && !empty($calificacion)){
					$consultaActualizarNotas->bind_param("iiiiiii", $idCursoNota, $idAsignatura, $idAlumnoAEvaluar, $idProfesor, $idTrimestre, $calificacion, $calificacion);
					$consultaActualizarNotas->execute();
					$cambiosEnBD = true;
				}
			}
			if ($cambiosEnBD){
				// redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=14');
				$_SESSION['OK'] = 14;
				print(mostrarMensajeOK(14));
			}else{
				$_SESSION['error'] = 22;
				print(mostrarMensajeERR(22));
			}
			// print($bd->affected_rows);
			// $consultaActualizarNotas->free();
      }

		public static function obtenerProfesor($idProfesor){
			global $bd;
			$consultaProfesor = $bd->query("SELECT p.id, p.usuario, p.nombre, p.apellidos, p.email, p.tutor_curso FROM ies_profesor p WHERE p.id='".$idProfesor."' ;");
			$profesor = Profesor::creacionProfesorPorQuery($consultaProfesor);
			$consultaProfesor->free();

			return $profesor;	
		}

		public static function loginProfesor($usuario, $contrasena, $encriptacion){
			global $bd;
			$rowProfesor = null;
			//obtengo los datos del profesor
			switch($encriptacion){
				case null:
					$consultaProfesor = $bd->query("SELECT id FROM ies_profesor WHERE usuario='".$usuario."' AND pass='".$contrasena."' ;");
					$rowProfesor = $consultaProfesor->fetch_array(MYSQLI_ASSOC);
					break;
				case 'md5':
					$consultaProfesor = $bd->query("SELECT id FROM ies_profesor WHERE usuario='".$usuario."' AND pass='".md5($contrasena)."' ;");
					$rowProfesor = $consultaProfesor->fetch_array(MYSQLI_ASSOC);
					break;
				case 'blowfish':
					$hashContrasenaProfesor = $bd->query("SELECT pass FROM ies_profesor WHERE usuario='".$usuario."';");
					$hashContrasenaProfesor = $hashContrasenaProfesor->fetch_array(MYSQLI_NUM);
					if($hashContrasenaProfesor) $hashContrasenaProfesor = $hashContrasenaProfesor[0];
					// Función password_verify()
					// Comprueba que el hash proporcionado coincida con la contraseña facilitada.
					// Observe que password_hash() devuelve el algoritmo, el coste y el salt como parte del hash devuelto. Por lo tanto, toda la información que es necesaria para verificar el hash está incluida. Esto permite a la función de verificación comprobar el hash sin la necesidad de almacenar por separado la información del salt o del algoritmo.
					// Esta función es segura contra ataques basado en tiempo.
					if(password_verify($contrasena, $hashContrasenaProfesor)){
						$consultaProfesor = $bd->query("SELECT id FROM ies_profesor WHERE usuario='".$usuario."';");
						$rowProfesor = $consultaProfesor->fetch_array(MYSQLI_ASSOC);
					}
					break;
			}
			
			if ($rowProfesor != null){
				return self::obtenerProfesor($rowProfesor['id']);
			}else{
				return null; //profesor no encontrado
			}
		}

		public static function obtenerProfesores(){
			global $bd;
			$profesores = array();
			$consultaProfesores = $bd->query("SELECT p.id, p.usuario, p.nombre, p.apellidos, p.email, p.tutor_curso FROM ies_profesor p ORDER BY p.tutor_curso ;");
			$profesores = Profesor::creacionProfesoresPorQuery($consultaProfesores);
			$consultaProfesores->free();
			
			return $profesores;	
		}

		public static function obtenerProfesoresCurso($idCurso){
			global $bd;
			$profesoresCurso = array();
			$consultaProfesores = $bd->query("SELECT p.id, p.usuario, p.nombre, p.apellidos, p.email, p.tutor_curso FROM ies_profesor p WHERE p.tutor_curso='".$idCurso."' ;");
			$profesoresCurso = Profesor::creacionProfesoresPorQuery($consultaProfesores);
			$consultaProfesores->free();
			
			return $profesoresCurso;	
		}

		public static function obtenerProfesoresAsignatura($idAsignatura){
			global $bd;
			$profesoresAsignatura = array();
			$consultaProfesores = $bd->query("SELECT p.id, p.usuario, p.nombre, p.apellidos, p.email, p.tutor_curso FROM ies_profesor p WHERE p.id=(SELECT DISTINCT notas.profesor FROM ies_notas notas WHERE notas.asignatura='".$idAsignatura."') ;");
			
			$profesoresAsignatura = Profesor::creacionProfesoresPorQuery($consultaProfesores);
			// console_log($profesoresAsignatura[1]->getId());
			// console_log($profesoresAsignatura[0]);
			$consultaProfesores->free();
			
			return $profesoresAsignatura;	
		}

		public static function comprobarEsTutorAlumno($idProfesor, $idAlumno){
         global $bd;
			$consultaTutor = $bd->query("SELECT p.tutor_curso FROM ies_profesor p WHERE p.id='".$idProfesor."' ;");
			$consultaAlumno = $bd->query("SELECT al.curso FROM ies_alumno al WHERE al.id='".$idAlumno."' ;");

         if ($rowTutor = $consultaTutor->fetch_array(MYSQLI_ASSOC)){
        		if ($rowAlumno = $consultaAlumno->fetch_array(MYSQLI_ASSOC)){
					$consultaTutor->free();
					$consultaAlumno->free();
					if($rowTutor['tutor_curso'] == $rowAlumno['curso']) return true;
				}
         }else{
				$consultaTutor->free();
            $consultaAlumno->free();
				// No se ha encontrado, se devuele false
				return false;
			}
      }

		// Obtener todos los cursos, primero obteniendo todas las asignaturas de la BD, y después procesando los datos se ahorran muchas consultas, aumenta el procesado. Por cada curso habría que consultar sus asignaturas a la BD si no se hiciese de esta forma.
		public static function obtenerCursos(){
			global $bd;
			$consultaAsignaturas = $bd->query("SELECT asig.id, asig.nombre, asig.nombre_corto, asig.curso FROM ies_asignatura asig ;");
			$todasAsignaturas = Asignatura::creacionAsignaturasPorQuery($consultaAsignaturas);

			$cursos = array();
			$asignaturasCurso = array();
			// Obtengo la información del los curso de la BD
			$consultaCursos = $bd->query("SELECT cur.id, cur.nombre FROM ies_curso cur ;");
			$i = 1; //La cuenta de los cursos debería empezar por 1 para que concuerde con el id. 
			$j = 1; //La cuenta de las asignaturas debería empezar por 1 para que concuerde con el id. 
			while (($rowCurso = $consultaCursos->fetch_array(MYSQLI_ASSOC)) != null){
				foreach($todasAsignaturas as $asig){
					//Probamos todas las asignaturas y solo guardamos las que tengan el mismo 'id curso' del curso a crear.
					if ($asig->getCurso() == $rowCurso['id']){
						$asignaturasCurso[$j] = $asig;
						$j++;
					}
				}
				$cursos[$i] = new Curso($rowCurso['id'], $rowCurso['nombre'], $asignaturasCurso);
				$i++;
			}

			$consultaAsignaturas->free();
			$consultaCursos->free();
			
			return $cursos;
		}
		
		//Obtener todos los cursos mediante consultas selectivas a la BD sabiendo el id del curso
		public static function obtenerCursos2(){
			global $bd;
			$cursos = array();
			// Obtengo la información del los curso de la BD
			$consultaCursos = $bd->query("SELECT cur.id, cur.nombre FROM ies_curso cur ;");
			$consultaMaxIdCursos = $bd->query("SELECT MAX(cur.id) FROM ies_curso cur ;");
			$maxIdCursos = $consultaMaxIdCursos->fetch_array(MYSQLI_NUM);
			$maxIdCursos = $maxIdCursos[0];
			$j = 1; //La cuenta de los cursos debería empezar por 1 para que concuerde con el id. 
			for($i=1; $i<=$maxIdCursos; $i++){
				$consultaAsignaturasCurso = $bd->query("SELECT asig.id, asig.nombre, asig.nombre_corto, asig.curso FROM ies_asignatura asig WHERE asig.curso='".$i."' ;");
				$asignaturasCurso = Asignatura::creacionAsignaturasPorQuery($consultaAsignaturasCurso);
				$rowCurso = $consultaCursos->fetch_array(MYSQLI_ASSOC);
				$cursos[$j] = new Curso($rowCurso['id'], $rowCurso['nombre'], $asignaturasCurso);
				$j++;
			}

			$consultaCursos->free();
			$consultaMaxIdCursos->free();
			$consultaAsignaturasCurso->free();

			return $cursos;
		}

		public static function obtenerCurso($idCurso){
			global $bd;
			$consultaCurso = $bd->query("SELECT cur.id, cur.nombre FROM ies_curso cur WHERE cur.id='".$idCurso."' ;");
			if(($rowCurso = $consultaCurso->fetch_array(MYSQLI_ASSOC)) != null){
				$asignaturasCurso = self::obtenerAsignaturasCurso($idCurso);
				$curso = new Curso($rowCurso['id'], $rowCurso['nombre'], $asignaturasCurso);

				$consultaCurso->free();
				
				return $curso;	
			}else{
				return null;
			}
		}

		public static function obtenerAsignaturas(){
			global $bd;
			$asignaturas = array();
			// Obtengo la información de las asignaturas de la BD   
			$consultaAsignaturas = $bd->query("SELECT asig.id, asig.nombre, asig.nombre_corto, asig.curso FROM ies_asignatura asig ORDER BY asig.curso ;");
			$asignaturas = Asignatura::creacionAsignaturasPorQuery($consultaAsignaturas);

			$consultaAsignaturas->free();

			return $asignaturas;
		}

		public static function obtenerAsignaturasCurso($idCurso){
			global $bd;
			$asignaturas = array();
			$consultaAsignaturas = $bd->query("SELECT asig.id, asig.nombre, asig.nombre_corto, asig.curso FROM ies_asignatura asig WHERE asig.curso='".$idCurso."' ;");
			$asignaturas = Asignatura::creacionAsignaturasPorQuery($consultaAsignaturas);

			$consultaAsignaturas->free();

			return $asignaturas;
		}

		public static function obtenerTrimestres(){
			global $bd;
			$trimestres = array();
			// Obtengo la información de los trimestres de la BD   
			$consultaTrimestres = $bd->query("SELECT id, nombre, nombre2, orden FROM ies_trimestres ;");
			$trimestres = Trimestre::creacionTrimestresPorQuery($consultaTrimestres);

			$consultaTrimestres->free();

			return $trimestres;
		}

		//INCIDENCIAS

      public static function obtenerIncidencia($idIncidencia){
			global $bd;
			// Obtengo la información de los trimestres de la BD   
         $consultaIncidencia = $bd->query("SELECT id, profesor, fecha, estado, detalles FROM ies_incidencias WHERE id='".$idIncidencia."' ;");

			$incidencia = Incidencia::creacionIncidenciaPorQuery($consultaIncidencia);
			$consultaIncidencia->free();
			return $incidencia;
		}

      public static function obtenerIncidencias(){
			global $bd;
			$incidencias = array();
			// Obtengo la información de las incidencias de la BD   
         $consultaIncidencias = $bd->query("SELECT id, profesor, fecha, estado, detalles FROM ies_incidencias ORDER BY fecha DESC;");

			$incidencias = Incidencia::creacionIncidenciasPorQuery($consultaIncidencias);

			$consultaIncidencias->free();
			return $incidencias;
		}

		public static function darAltaIncidencia($incidencia){
         global $bd;
       
         $bd->query("INSERT INTO ies_incidencias (id, profesor, fecha, estado, detalles) VALUES ('".$incidencia->getId()."', '".$incidencia->getIdProfesor()."', '".$incidencia->getFecha()."', '".$incidencia->getEstado()."', '".$incidencia->getDetalles()."') ;");
         if ($bd->affected_rows >= 1){
            //actualizo el log de incidencias
            self::actualizarLog($incidencia, $incidencia->getIdProfesor(), 0, $incidencia->getDetalles());
				redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=11');
			}else{
				redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=18');
			}
      }

      public static function actualizarIncidencia($idIncidencia, $idNuevoProfesor, $nuevoEstado, $nuevosDetalles){
         global $bd;

         //recupero los datos de la incidencia. La incidencia debe de existir previamente en la tabla ies_incidencias 
         $incidencia = self::obtenerIncidencia($idIncidencia);
         //Tengo que el nuevo estado actualizar está dentro de los valores permitidos
         //0 -> Sin resolver
         //1 -> En espera
         //2 -> Resuelta
         if ($nuevoEstado >= 0 && $nuevoEstado <= 2){
            $bd->query("UPDATE ies_incidencias SET estado='".$nuevoEstado."', detalles='".$nuevosDetalles."' WHERE id='".$idIncidencia."' ;");
            if ($bd->affected_rows >= 1){
               //ahora hay que añadir una entrada en el log de las incidencias (tabla ies_log_incidencias) para dejar constancia del cambio. 
               self::actualizarLog($incidencia, $idNuevoProfesor, $nuevoEstado, $nuevosDetalles);
               // Se ha modificado correctamente el estado de la incidencia
               redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&OK=13');
            }else{
               // No se ha modificado el estado de la incidencia
               redireccionar($_SERVER['PHP_SELF'].'?tipoUsuario='.$_SESSION['tipoUsuario'].'&id='.$_SESSION['id'].'&usuario='.$_SESSION['usuario'].'&error=20');
            }
         }
      }

      public static function existeIncidencia($idIncidencia){
         global $bd;
         // Busco la incidencia en la BD y si existe, devuelvo true, de lo contrario devuelvo false
         $consultaIncidencia = $bd->query("SELECT id, profesor, fecha, estado, detalles FROM ies_incidencias WHERE id='".$idIncidencia."' ;");

         if ($consultaIncidencia->fetch_array(MYSQLI_ASSOC)){
            $consultaIncidencia->free();
            return true;
         }
         // No se ha encontrado la incidencia, se devuele un nulo
         return false;
      }

      public static function actualizarLog($incidencia, $idNuevoProfesor, $nuevoEstado, $nuevosDetalles){
         global $bd;
         $fechaModificacion = new DateTime();
         $fechaModificacion = date_format($fechaModificacion, 'Y-m-d H:i:s');

         $ultimoIdIncidencia = $bd->query("SELECT MAX(id) FROM ies_incidencias ;")->fetch_array(MYSQLI_NUM)[0];
         if(empty($incidencia->getId())) $incidencia = self::obtenerIncidencia($ultimoIdIncidencia);

         $bd->query("INSERT INTO ies_log_incidencias (id, incidencia, profesor, estado, detalles, fechaModificacion) VALUES (null, '".$incidencia->getId()."', '".$idNuevoProfesor."', '".$nuevoEstado."', '".$nuevosDetalles."', '".$fechaModificacion."') ;");
         //No se notifica al usuario ya que no es necesario al tratarse de un log
      }

		public static function obtenerIncidenciasLog(){
			global $bd;
			$incidenciasLog = array();
			// Obtengo la información de las incidencias de la BD   
         $consultaIncidenciasLog = $bd->query("SELECT id, incidencia, profesor, estado, detalles, fechaModificacion FROM ies_log_incidencias ORDER BY incidencia DESC, fechaModificacion ASC;");

			$incidenciasLog = Logincidencias::creacionIncidenciasLogPorQuery($consultaIncidenciasLog);

			$consultaIncidenciasLog->free();
			return $incidenciasLog;
		}

		public static function obtenerHistorialIncidencia($idIncidencia){
			global $bd;
			$historialIncidencia = array();
			// Obtengo la información de las incidencias de la BD   
         $consultaIncidenciasLog = $bd->query("SELECT id, incidencia, profesor, estado, detalles, fechaModificacion FROM ies_log_incidencias WHERE incidencia='".$idIncidencia."' ORDER BY incidencia, fechaModificacion ;");

			$historialIncidencia = Logincidencias::creacionIncidenciasLogPorQuery($consultaIncidenciasLog);

			$consultaIncidenciasLog->free();
			return $historialIncidencia;
		}

		// MÉTODOS API METEORED
		public static function obtenerAvisos(){
         global $bd;
         $consultaAvisos = array();
         // Obtengo la información de los trimestres de la BD   
         $consultaAvisos = $bd->query("SELECT id, variable, valor, descripcion FROM ies_avisosmeteo ;");
         $avisos = Aviso::creacionAvisosPorQuery($consultaAvisos);

         $consultaAvisos->free();

         return $avisos;
      }

      public static function comprobarAlertas($temperaturaHoy, $vientoHoy){
         $avisos = self::obtenerAvisos();
         $notificarAvisos = array();

         foreach ($avisos as $aviso){
            switch($aviso->getVariable()){
               case 'temperatura':
                  if($temperaturaHoy >= $aviso->getValor()) $notificarAvisos[] = $aviso;
                  break;
               case 'viento':
                  if($vientoHoy >= $aviso->getValor()) $notificarAvisos[] = $aviso;
                  break;
            }
         }

        return $notificarAvisos;
      }
   }
?>