<?php
/**
 * ProdutoServico Class
 *
 * Essa classe define um objeto do tipo "Produto ou Serviço".
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 */

namespace Sped2Json;

class ProdutoServico
{
	private $product;
	private $description;
	private $unit;

	function __construct() {
		$this->product = "";
		$this->description = "";
		$this->unit = "";
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