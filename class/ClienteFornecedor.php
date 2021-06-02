<?php
/**
 * ClienteFornecedor Class
 *
 * Essa classe define um objeto do tipo "Cliente ou Fornecedor".
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 */

namespace Sped2Json;

class ClienteFornecedor
{
	private $participant;
	private $cnpj;
	private $name;

	function __construct() {
		$this->participant = "";
		$this->cnpj = "";
		$this->name = "";
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