<?php 
//  Definir una nueva tabla en la BBDD llamada ies_log_incidencias para llevar un registro de los cambios de estado que sufre cada incidencia. Es decir, un profesor podrá dar de alta una incidencia y su estado inicialmente estará a “0 (Sin resolver)”, y otro profesor podrá cambiar el estado de la incidencia a “2 (Resuelta)”. Se pide: i.Definir la nueva tabla para almacenar sólo el profesor que cambia el estado, el nuevo estado, el estado antiguo, y la fecha de la modificación.
	class Logincidencias{
      private $id;
		private $idIncidencia;
      private $idProfesor;
      private $estado;
      private $fechaModificacion;

		public function __construct($id, $idIncidencia, $idProfesor, $estado, $fechaModificacion) {
         self::setId($id);
         self::setIdIncidencia($idIncidencia);
         self::setIdProfesor($idProfesor);
         self::setEstado($estado);
         self::setFechaModificacion($fechaModificacion);
		}
		
		public function getId() {
			return $this->id; 
		}
		public function getIdIncidencia() {
			return $this->idIncidencia; 
		}
		public function getIdProfesor() {
			return $this->idProfesor; 
		}
		public function getEstado() {
			return $this->estado; 
		}
		public function getFechaModificacion() {
			return $this->fechaModificacion; 
		}

		private function setId($id){
			if (!empty(trim($id)) && is_numeric($id) && $id >= 0){
				$this->id = $id;
			}else{
				$this->id = null;
			}
		}

		private function setIdIncidencia($idIncidencia){
			if (!empty(trim($idIncidencia)) && $idIncidencia != null){
				$this->idIncidencia = $idIncidencia;
			}else{
				$this->idIncidencia = null;
			}
		}

		private function setIdProfesor($idProfesor){
			if (!empty(trim($idProfesor)) && $idProfesor != null){
				$this->idProfesor = $idProfesor;
			}else{
				$this->idProfesor = null;
			}
		}

		private function setEstado($estado){
			if (!empty(trim($estado)) && $estado != null){
				$this->estado = $estado;
			}else{
				$this->estado = null;
			}
		}
            
		private function setFechaModificacion($fechaModificacion){
			if (!empty(trim($fechaModificacion)) && $fechaModificacion != null){
				$this->fechaModificacion = $fechaModificacion;
			}else{
				$this->fechaModificacion = null;
			}
		}

		public static function creacionIncidenciasLogPorQuery($consultaIncidenciasLog){
			$incidenciasLog = array();
			$consultaIncidenciasLog->data_seek(0);
			$i = 0;
			while(($incidencia=$consultaIncidenciasLog->fetch_array(MYSQLI_ASSOC)) != null){
			  $incidenciasLog[$i] = new Incidencia($incidencia['incidencia'], $incidencia['profesor'], $incidencia['fechaModificacion'], $incidencia['estado'], $incidencia['detalles']);
			  $i++;
			}
			return $incidenciasLog;
		}
   }
?>