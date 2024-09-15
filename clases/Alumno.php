<?php 
	class Alumno{
		private $id;
		private $usuario;
		private $nombre;
		private $apellidos;
		private $telefono;
		private $email;
		private $curso;
		private $activo;
		private $notas;

		public function __construct($id, $usuario, $nombre, $apellidos, $telefono, $email, $curso, $activo, $notas) {
         self::setId($id);
         self::setUsuario($usuario);
         self::setNombre($nombre);
         self::setApellidos($apellidos);
         self::setTelefono($telefono);
         self::setEmail($email);
         self::setCurso($curso);
         self::setActivo($activo);
         self::setNotas($notas);
		}
		
		public function getId() {
			return $this->id; 
		}
		public function getUsuario() {
			return $this->usuario; 
		}
		public function getNombre() {
			return $this->nombre; 
		}
		public function getApellidos() {
			return $this->apellidos; 
		}
		public function getTelefono() {
			return $this->telefono; 
		}
		public function getEmail() {
			return $this->email; 
		}
		public function getCurso() {
			return $this->curso; 
		}
		public function getActivo() {
			return $this->activo; 
		}
		public function getNotas() {
			return $this->notas; 
		}

		//Obtener todas las notas de una misma asignatura, de todos los cursos y todos los trimestres
		public function getNotasAsignatura($idAsignatura) {
			$notasEncontradas = Array();
			if(!empty($this->notas)){
				foreach($this->notas as $nota){
					if($nota->getIdAsignatura() == $idAsignatura) $notasEncontradas[] = $nota;
				}
			}
			return $notasEncontradas;
      }

		//Obtener todas las notas de una misma asignatura y un mismo curso, de todos los trimestres
		public function getNotasAsignaturaCurso($idAsignatura, $idCurso) {
			$notasEncontradas = Array();
			if(!empty($this->notas)){
				foreach($this->notas as $nota){
					if($nota->getIdAsignatura() == $idAsignatura && $nota->getIdCurso() == $idCurso) $notasEncontradas[] = $nota;
				}
			}
			return $notasEncontradas;
      }

		//Obtener todas las notas de un mismo curso, de todos las asignaturas y trimestres
		//El alumno solo pertenece a un curso por año, pero su historial de notas se va acumulando conforme pasan los cursos. Por tanto hay que filtrar también por curso.
		public function getNotasCurso($idCurso) {
			$notasEncontradas = Array();
			if(!empty($this->notas)){
				foreach($this->notas as $nota){
					if($nota->getIdCurso() == $idCurso) $notasEncontradas[] = $nota;
				}
			}
			return $notasEncontradas;
      }

		//Obtener todas las notas de un mismo trimestre, de todos los cursos y todas las asignaturas
		public function getNotasTrimestre($idTrimestre) {
			$notasEncontradas = Array();
			if(!empty($this->notas)){
				foreach($this->notas as $nota){
					if($nota->getIdTrimestre() == $idTrimestre) $notasEncontradas[] = $nota;
				}
			}
			return $notasEncontradas;
      }

		//Obtener todas las notas de un mismo trimestre y curso, de todos las asignaturas
		public function getNotasTrimestreCurso($idTrimestre, $idCurso) {
			$notasEncontradas = Array();
			if(!empty($this->notas)){
				foreach($this->notas as $nota){
					if($nota->getIdTrimestre() == $idTrimestre && $nota->getIdCurso() == $idCurso) $notasEncontradas[] = $nota;
				}
			}
			return $notasEncontradas;
      }

		private function setId($id){
			if (!empty(trim($id)) && is_numeric($id) && $id >= 0){
				$this->id = $id;
			}else{
				$this->id = 0;
			}
		}
		private function setUsuario($usuario){
			if (!empty(trim($usuario)) && $usuario != null){
				$this->usuario = $usuario;
			}
		}
		private function setNombre($nombre){
			if (!empty(trim($nombre)) && $nombre != null){
				$this->nombre = $nombre;
			}
		}
		private function setApellidos($apellidos){
			if (!empty(trim($apellidos)) && $apellidos != null){
				$this->apellidos = $apellidos;
			}
		}
		private function setTelefono($telefono){
			if (!empty(trim($telefono)) && $telefono != null){
				$this->telefono = $telefono;
			}else{
				$this->telefono = null;
			}
		}
		private function setEmail($email){
			if (!empty(trim($email)) && $email != null){
				$this->email = $email;
			}else{
				$this->email = null;
			}
		}
		private function setCurso($curso){
			if (!empty($curso) && $curso != null){
				$this->curso = $curso;
			}else{
				$this->curso = null;
			}
		}
		private function setActivo($activo){
			if ($activo == 1){
				$this->activo = 1;
			}else{
				$this->activo = 0;
			}
		}
		private function setNotas($notas){
			if (!empty($notas) && $notas != null){
				$this->notas = $notas;
			}else{
				$this->notas = null;
			}
		}

		//El alumno solo pertenece a un curso, pero su historial de notas se va acumulando conforme pasan los cursos. Por tanto hay que filtrar también por curso. No es necesario indicar el profesor ya que aunque varios profesores puedan impartir una misma asignatura, en la nota solo se reflejará el profesor que le impartió clase
		public function buscarNotaAlumno($idCurso, $idAsignatura, $idTrimestre) {
			if(!empty($this->notas)){
				foreach($this->notas as $nota){
					if($nota->getIdCurso() == $idCurso && $nota->getIdAsignatura() == $idAsignatura && $nota->getIdTrimestre() == $idTrimestre) return $nota;
				}
			}
			return null;
		}

		// public static function creacionAlumnoPorQuery($query){
		// 	$query->data_seek(0);
		// 	$alumno=$query->fetch_array(MYSQLI_ASSOC);
		// 	return new Alumno($alumno['id'], $alumno['usuario'], $alumno['nombre'], $alumno['apellidos'], $alumno['telefono'], $alumno['email'], $alumno['curso'], $alumno['activo']);
		// }
	
		// public static function creacionAlumnosPorQuery($consultaAlumnos){
		// 	$alumnos = array();
		// 	$consultaAlumnos->data_seek(0);
		// 	$alumno=$consultaAlumnos->fetch_array(MYSQLI_ASSOC);
		// 	$i = 1; //La cuenta de alumnos debería empezar por 1 para que concuerde con el id. 
		// 	while($alumno!=null){
		// 	  $alumnos[$i] = new Alumno($alumno['id'], $alumno['usuario'], $alumno['nombre'], $alumno['apellidos'], $alumno['telefono'], $alumno['email'], $alumno['curso'], $alumno['activo']);
		// 	  $alumno=$consultaAlumnos->fetch_array(MYSQLI_ASSOC);
		// 	  $i++;
		// 	}
		// 	return $alumnos;
		// }
	}
	
?>