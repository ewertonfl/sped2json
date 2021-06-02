<?php
/**
 * NotaFiscal Class
 *
 * Essa classe define um objeto do tipo "Nota fiscal".
 *
 * @author     Ewerton de Oliveira Florencio <ewerton.florencio@yahoo.com.br>
 */

namespace Sped2Json;

class NotaFiscal
{
	private $id;
	private $number;
	private $series;
	private $operation;
	private $emission;
	private $participant;
	private $total;
	private $year;
	private $month;
	private $day;
	private $items;
	
	function __construct() {
		$this->number = "";
		$this->series = "";
		$this->operation = "";
		$this->emission = "";
		$this->participant = "";
		$this->total = "";
		$this->year = "";
		$this->month = "";
		$this->day = "";
		$this->items = array();
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
}

?>