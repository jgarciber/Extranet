<?php 
	class Incidencia{
		private $id;
      private $idProfesor;
      private $fecha;
      private $estado;
      private $detalles;

		public function __construct($id, $idProfesor, $fecha, $estado, $detalles) {
         self::setId($id);
         self::setIdProfesor($idProfesor);
         self::setFecha($fecha);
         self::setEstado($estado);
         self::setDetalles($detalles);
		}
		
		public function getId() {
			return $this->id; 
		}
		public function getIdProfesor() {
			return $this->idProfesor; 
		}
		public function getFecha() {
			return $this->fecha; 
		}
		public function getEstado() {
			return $this->estado; 
		}
		public function getDetalles() {
			return $this->detalles; 
		}


		private function setId($id){
			if (!empty(trim($id)) && is_numeric($id) && $id >= 0){
				$this->id = $id;
			}else{
            //En la base de datos el id es AutoIncrement, se le pasa 0 y ya se cambia solo.
				$this->id = 0;
			}
		}

		private function setIdProfesor($idProfesor){
			if (!empty(trim($idProfesor)) && is_numeric($idProfesor) && $idProfesor >= 0){
				$this->idProfesor = $idProfesor;
			}else{
				$this->idProfesor = 0;
			}
		}
		private function setFecha($fecha){
			if (!empty(trim($fecha)) && $fecha != null){
				$this->fecha = $fecha;
			}else{
            $fecha = new DateTime();
				$this->fecha = $fecha->format('Y-m-d H:i:s');
			}
		}
      
		private function setEstado($estado){
			if (!empty(trim($estado)) && is_numeric($estado) && $estado >= 0 && $estado <= 2){
				$this->estado = $estado;
			}else{
				$this->estado = 0;
			}
		}

		private function setDetalles($detalles){
			if (!empty(trim($detalles)) && $detalles != null){
				$this->detalles = $detalles;
			}else{
				$this->detalles = '';
			}
		}

      public static function creacionIncidenciaPorQuery($query){
			$query->data_seek(0);
			if(($incidencia=$query->fetch_array(MYSQLI_ASSOC)) == null){
				return null;
			}else{
				return new Incidencia($incidencia['id'], $incidencia['profesor'], $incidencia['fecha'], $incidencia['estado'], $incidencia['detalles']);
			}
		}
	
		public static function creacionIncidenciasPorQuery($consultaIncidencias){
			$incidencias = array();
			$consultaIncidencias->data_seek(0);
			$i = 0;
			while(($incidencia=$consultaIncidencias->fetch_array(MYSQLI_ASSOC)) != null){
			  $incidencias[$i] = new Incidencia($incidencia['id'], $incidencia['profesor'], $incidencia['fecha'], $incidencia['estado'], $incidencia['detalles']);
			  $i++;
			}
			return $incidencias;
		}
   }
?>