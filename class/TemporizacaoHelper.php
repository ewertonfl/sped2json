<?php

namespace Sped2Json;

class TemporizacaoHelper {

    private $inicio;

    public function __construct() {
        $this->inicio = microtime(true);
    }

    public function iniciar() {
        $this->inicio = microtime(true);
    }

    public function segundos() {
        return microtime(true) - $this->inicio;
    }

    public function tempo() {
        $segs = $this->segundos();
        $dias = floor($segs / 86400);
        $segs -= $dias * 86400;
        $horas = floor($segs / 3600);
        $segs -= $horas * 3600;
        $minutos = floor($segs / 60);
        $segs -= $minutos * 60;
        $microsegs = ($segs - floor($segs)) * 1000;
        $segs = floor($segs);

        return 
            (empty($dias) ? "" : $dias . "d ") . 
            (empty($horas) ? "" : $horas . "h ") . 
            (empty($minutos) ? "" : $minutos . "m ") . 
            $segs . "s " .
            $microsegs . "ms";
    }

}

?>