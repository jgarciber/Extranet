<?php 
	class Nota{
		private $idCurso;
		private $idAsignatura; 
		private $idProfesor; 
      private $idTrimestre; 
      private $nota; 
      
      public function __construct($idCurso, $idAsignatura, $idProfesor, $idTrimestre, $nota) {
         self::setIdCurso($idCurso);
         self::setIdAsignatura($idAsignatura);
         self::setIdProfesor($idProfesor);
         self::setIdTrimestre($idTrimestre);
         self::setNota($nota);
      }
		
		public function getIdCurso() {
			return $this->idCurso; 
		}
		public function getIdAsignatura() {
			return $this->idAsignatura; 
		}
		public function getIdProfesor() {
			return $this->idProfesor; 
      }
		public function getIdTrimestre() {
			return $this->idTrimestre; 
      }
		public function getNota() {
			return $this->nota; 
      }
      
		private function setIdCurso($idCurso){
			if (!empty(trim($idCurso)) && $idCurso != null){
				$this->idCurso = $idCurso;
         }else{
				$this->idCurso = null;
			}
		}
		private function setIdAsignatura($idAsignatura){
			if (!empty(trim($idAsignatura)) && $idAsignatura != null){
				$this->idAsignatura = $idAsignatura;
			}else{
				$this->idAsignatura = null;
			}
      }
		private function setIdProfesor($idProfesor){
			//Si no existe el profesor se indica con el IdProfesor = 0
			if (!empty(trim($idProfesor)) && $idProfesor != null && $idProfesor != 0){
				$this->idProfesor = $idProfesor;
			}else{
				$this->idProfesor = '';
			}
      }
		private function setIdTrimestre($idTrimestre){
			if (!empty(trim($idTrimestre)) && $idTrimestre != null){
				$this->idTrimestre = $idTrimestre;
			}else{
				$this->idTrimestre = null;
			}
      }
		private function setNota($nota){
			if (!empty(trim($nota)) && $nota >= 0 && $nota <= 10){
				$this->nota = $nota;
			}else{
				$this->nota = null;
			}
      }
      
      public static function creacionNotaPorQuery($query){
			$query->data_seek(0);
			if (($nota=$query->fetch_array(MYSQLI_ASSOC)) == null){
				return null;
			}else{
				return new Nota($nota['curso'], $nota['asignatura'], $nota['profesor'], $nota['trimestre'], $nota['nota']);
			}
		}
	
		public static function creacionNotasPorQuery($consultaNotas){
			$notas = array();
			$consultaNotas->data_seek(0);
			$i = 1; //La cuenta de asignaturas debería empezar por 1 para que concuerde con el curso. 
			while(($nota = $consultaNotas->fetch_array(MYSQLI_ASSOC)) != null){
			  $notas[$i] = new Nota($nota['curso'], $nota['asignatura'], $nota['profesor'], $nota['trimestre'], $nota['nota']);
			  $i++;
			}
			//Si el array está vacío se devolverá un array vacío en vez de un null. Es mejor opción que devolver un null, ya que cuando se pinten en pantalla los datos mediante bucles foreach, si el array está vació no aparecerá un error. Si el array es un null si que se producirá un error.
			return $notas;
		}
	}
?>