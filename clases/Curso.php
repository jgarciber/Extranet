<?php 
	class Curso{
		private $id;
		private $nombre;
		private $asignaturas; //Array de objetos asignatura

		public function __construct($id, $nombre, $asignaturas) {
         self::setId($id);
         self::setNombre($nombre);
         self::setAsignaturas($asignaturas);
		}
		
		public function getId() {
			return $this->id; 
		}
		public function getNombre() {
			return $this->nombre; 
		}
		public function getAsignaturas() {
			return $this->asignaturas; 
		}
		public function getAsignaturaId($idAsignatura) {
			if(!empty($this->asignaturas)){
				foreach($this->asignaturas as $asig){
					if($asig->getId() == $idAsignatura) return $asig;
				}
			}
			return null; 
		}
		
		private function setId($id){
			if (!empty(trim($id)) && $id != null){
				$this->id = $id;
			}else{
				$this->id = null;
			}
		}
		private function setNombre($nombre){
			if (!empty(trim($nombre)) && $nombre != null){
				$this->nombre = $nombre;
			}
		}
		private function setAsignaturas($asignaturas){
			if (!empty($asignaturas) && $asignaturas != null){
				$this->asignaturas = $asignaturas;
			}else{
				$this->asignaturas = null;
			}
		}
		
		public static function encontrarCurso ($idCurso, $arrayCursos){
			foreach ($arrayCursos as $curso){
				if($curso->getId() == $idCurso){
					return $curso;
				}
			}
			return null;
		}

		// public static function creacionCursoPorQuery($query){
		// 	$query->data_seek(0);
		// 	$curso=$query->fetch_array(MYSQLI_ASSOC);
		// 	return new Curso($curso['id'], $curso['nombre']);
		// }
	
		// public static function creacionCursosPorQuery($consultaCursos){
		// 	$cursos = array();
		// 	$consultaCursos->data_seek(0);
		// 	$curso=$consultaCursos->fetch_array(MYSQLI_ASSOC);
		// 	$i = 1; //La cuenta de los cursos debería empezar por 1 para que concuerde con el id. 
		// 	while($curso!=null){
		// 	  $cursos[$i] = new Curso($curso['id'], $curso['nombre']);
		// 	  $curso=$consultaCursos->fetch_array(MYSQLI_ASSOC);
		// 	  $i++;
		// 	}
		// 	return $cursos;
		// }
	}
?>