<?php
/**
 * ItemNotaFiscal Class
 *
 * Essa classe define um objeto do tipo "Item de Nota Fiscal".
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 */

namespace Sped2Json;

class ItemNotaFiscal
{
	private $item_id;
	private $product;
	private $quantity;
	private $price;
	private $total;
	
	function __construct() {
		$this->item_id = "";
		$this->product = "";
		$this->quantity = "";
		$this->price = "";
		$this->total = "";
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