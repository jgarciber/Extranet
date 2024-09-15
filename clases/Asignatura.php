<?php 
	class Asignatura{
		private $id;
		private $nombre; 
		private $nombreCorto; 
      private $curso; 
      
      public function __construct($id, $nombre, $nombreCorto, $curso) {
         self::setId($id);
         self::setNombre($nombre);
         self::setNombreCorto($nombreCorto);
         self::setCurso($curso);
      }
		
		public function getId() {
			return $this->id; 
		}
		public function getNombre() {
			return $this->nombre; 
		}
      public function getNombreCorto() {
			return $this->nombreCorto; 
		}
		public function getCurso() {
			return $this->curso; 
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
		private function setNombreCorto($nombreCorto){
			if (!empty(trim($nombreCorto)) && $nombreCorto != null){
				$this->nombreCorto = $nombreCorto;
			}
      }
		private function setCurso($curso){
			if (!empty(trim($curso)) && $curso != null){
				$this->curso = $curso;
			}else{
				$this->curso = null;
			}
      }
      
      public static function creacionAsignaturaPorQuery($query){
			$query->data_seek(0);
			if (($asignatura=$query->fetch_array(MYSQLI_ASSOC)) == null){
				return null;
			}else{
				return new Asignatura($asignatura['id'], $asignatura['nombre'], $asignatura['nombre_corto'], $asignatura['curso']);
			}
		}
	
		public static function creacionAsignaturasPorQuery($consultaAsignaturas){
			$asignaturas = array();
			$consultaAsignaturas->data_seek(0);
			$i = 1; //La cuenta de asignaturas debería empezar por 1 para que concuerde con el curso. 
			while(($asignatura = $consultaAsignaturas->fetch_array(MYSQLI_ASSOC)) != null){
			  $asignaturas[$i] = new Asignatura($asignatura['id'], $asignatura['nombre'], $asignatura['nombre_corto'], $asignatura['curso']);
			  $i++;
			}
			//Si el array está vacío se devolverá un array vacío en vez de un null. Es mejor opción que devolver un null, ya que cuando se pinten en pantalla los datos mediante bucles foreach, si el array está vació no aparecerá un error. Si el array es un null si que se producirá un error.
			return $asignaturas;
		}
	}
?>