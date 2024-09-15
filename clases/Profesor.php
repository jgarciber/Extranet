<?php 
	class Profesor{
		private $id;
		private $usuario;
		private $nombre; 
      private $apellidos;
		private $email;
		private $tutorCurso; //Solo guarda el id del curso, no tiene sentido que guarde las asignaturas ya que un profesor no tiene que cursar asignaturas. Si acaso impartirá algunas asignaturas. No tiene el rol de 'alumno'.

		public function __construct($id, $usuario, $nombre, $apellidos, $email, $tutorCurso) {
         self::setId($id);
         self::setUsuario($usuario);
         self::setNombre($nombre);
         self::setApellidos($apellidos);
         self::setEmail($email);
         self::setTutorCurso($tutorCurso);
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
		public function getEmail() {
			return $this->email; 
		}
		public function getTutorCurso() {
			return $this->tutorCurso; 
		}
	
		private function setId($id){
			if (!empty(trim($id)) && $id != null){
				$this->id = $id;
			}else{
				$this->id = null;
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
		private function setEmail($email){
			if (!empty(trim($email)) && $email != null){
				$this->email = $email;
			}else{
				$this->email = null;
			}
		}
		private function setTutorCurso($tutorCurso){
			if (!empty(trim($tutorCurso)) && $tutorCurso != null){
				$this->tutorCurso = $tutorCurso;
			}else{
				$this->tutorCurso = null;
			}
		}

		public static function creacionProfesorPorQuery($query){
			$query->data_seek(0);
			if (($profesor=$query->fetch_array(MYSQLI_ASSOC)) == null){
				return null;
			}else{
				return new Profesor($profesor['id'], $profesor['usuario'], $profesor['nombre'], $profesor['apellidos'], $profesor['email'], $profesor['tutor_curso']);
			}
		}
	
		public static function creacionProfesoresPorQuery($consultaProfesores){
			$profesores = array();
			$consultaProfesores->data_seek(0);
			$i = 1; //La cuenta de profesores debería empezar por 1 para que concuerde con el id. 
			while(($profesor=$consultaProfesores->fetch_array(MYSQLI_ASSOC)) != null){
			  $profesores[$i] = new Profesor($profesor['id'], $profesor['usuario'], $profesor['nombre'], $profesor['apellidos'], $profesor['email'], $profesor['tutor_curso']);
			  $i++;
			}
			//Si el array está vacío se devolverá un array vacío en vez de un null. Es mejor opción que devolver un null, ya que cuando se pinten en pantalla los datos mediante bucles foreach, si el array está vació no aparecerá un error. Si el array es un null si que se producirá un error.
			return $profesores;
		}
	}
	
?>