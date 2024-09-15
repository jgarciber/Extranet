<?php 
	class Aviso{
		private $id;
		private $variable;
		private $valor;
		private $descripcion;

		public function __construct($id, $variable, $valor, $descripcion) {
         self::setId($id);
         self::setVariable($variable);
         self::setValor($valor);
         self::setDescripcion($descripcion);
		}
		
		public function getId() {
			return $this->id; 
		}
		public function getVariable() {
			return $this->variable; 
		}
		public function getValor() {
			return $this->valor; 
		}
		public function getDescripcion() {
			return $this->descripcion; 
		}
		
		private function setId($id){
			if (!empty(trim($id)) && $id != null){
				$this->id = $id;
			}else{
				$this->id = null;
			}
		}
		private function setVariable($variable){
			if (!empty(trim($variable)) && $variable != null){
				$this->variable = $variable;
			}
		}
		private function setValor($valor){
			if (!empty(trim($valor)) && $valor != null){
				$this->valor = $valor;
			}
		}
		private function setDescripcion($descripcion){
			if (!empty(trim($descripcion)) && $descripcion != null){
				$this->descripcion = $descripcion;
			}
      }

      public static function creacionAvisosPorQuery($consultaAvisos){
         $avisos = array();
			$consultaAvisos->data_seek(0);
         while(($aviso=$consultaAvisos->fetch_array(MYSQLI_ASSOC)) != null){
				$avisos[] = new Aviso($aviso['id'], $aviso['variable'], $aviso['valor'], $aviso['descripcion']);
			}
         return $avisos;
		}
	}
?>