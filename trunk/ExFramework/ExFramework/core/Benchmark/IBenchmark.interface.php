<?php

/**
 * @interface IBenchmark
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IBenchmark {
	
	public function markTime($name);
	public function getElapsedTime($name1=null, $name2=null, $decimals=5);
	public function getTimer();
}

?>
