<?php 
	class Trimestre{
		private $id;
		private $nombre; 
		private $nombre2; 
		private $orden;

		public function __construct($id, $nombre, $nombre2, $orden) {
         self::setId($id);
         self::setNombre($nombre);
         self::setNombre2($nombre2);
         self::setOrden($orden);
      }
		
		public function getId() {
			return $this->id; 
		}
		public function getNombre() {
			return $this->nombre; 
		}
      public function getNombre2() {
			return $this->nombre2; 
		}
		public function getOrden() {
			return $this->orden; 
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
		private function setNombre2($nombre2){
			if (!empty(trim($nombre2)) && $nombre2 != null){
				$this->nombre2 = $nombre2;
			}
      }
		private function setOrden($orden){
			if (!empty(trim($orden)) && $orden != null){
				$this->orden = $orden;
			}
		}

		public static function creacionTrimestrePorQuery($query){
			$query->data_seek(0);
			if(($trimestre=$query->fetch_array(MYSQLI_ASSOC)) == null){
				return null;
			}else{
				return new Trimestre($trimestre['id'], $trimestre['nombre'], $trimestre['nombre2'], $trimestre['orden']);
			}
		}
	
		public static function creacionTrimestresPorQuery($consultaTrimestres){
			$trimestres = array();
			$consultaTrimestres->data_seek(0);
			$i = 1; //La cuenta de trimestres debería empezar por 1 para que concuerde con el curso. 
			while(($trimestre=$consultaTrimestres->fetch_array(MYSQLI_ASSOC)) != null){
			  $trimestres[$i] = new Trimestre($trimestre['id'], $trimestre['nombre'], $trimestre['nombre2'], $trimestre['orden']);
			  $i++;
			}
			return $trimestres;
		}
	}
?>