<?php
/**
 * UnidadeNegocio Class
 *
 * Essa classe define um objeto do tipo "Unidade de Negócio".
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 */

namespace Sped2Json;

class UnidadeNegocio
{
	private $cnpj;
	private $name;
	private $units;
	private $clients;
	private $products;
	private $orders;

	function __construct() {
		$this->cnpj = "";
		$this->name = "";
		$this->units = array();
		$this->clients = array();
		$this->products = array();
		$this->orders = array();
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
	public function addArray($array,$new) {
		$this->$array[] = $new;
	}
	public function replaceArray($array,$position,$new) {
		$this->$array[$position] = $new;
	}
	
}

?>