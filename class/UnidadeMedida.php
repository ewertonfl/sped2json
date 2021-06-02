<?php
/**
 * UnidadeMedida Class
 *
 * Essa classe define um objeto do tipo "Unidade de Medida".
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 */

namespace Sped2Json;

class UnidadeMedida
{
	private $unit;
	private $description;

	function __construct() {
		$this->unit = "";
		$this->description = "";
	}

	// Criação dos métodos __Get e __Set (overloading)
	public function __get($value) {
		return $this->$value;
	}
	public function __set($property,$value) {
		$this->$property = $value;
	}
    public function __isset($name) {
        return isset($this->$name);
    }
}

?>