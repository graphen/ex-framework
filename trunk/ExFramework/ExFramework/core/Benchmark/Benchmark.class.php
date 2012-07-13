<?php

/**
 * @class Benchmark
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Benchmark implements IBenchmark {
	
	/**
	 * Tablica z czasami
	 *
	 * @var array
	 */
	protected $_timer = array();
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 *
	 */
	public function __construct() {
		$this->markTime("start");
	}
	
	/**
	 * Ustawienie znacznika czasu
	 * 
	 * @access public
	 * @param string Nazwa znacznika czasowego
	 * @return void
	 * 
	 */
	public function markTime($name) {
		$this->_timer[$name] = microtime();
	}
	
	/**
	 * Pobranie roznicy miedzy dwoma ustawionymi znacznikami czasu
	 * 
	 * @param string Nazwa pierwszego znacznika czasu
	 * @param string Nazwa drugiego znacznika czasu
	 * @param int Liczba cyfr po przecinku w zwracanej roznicy czasow
	 * @access public
	 * @return double
	 * 
	 */
	public function getElapsedTime($name1=null, $name2=null, $decimals=5) {
		if((is_null($name1)) || (!isset($this->_timer[$name1]))) {
			$name1 = "start";
		}
		if((is_null($name2)) || (!isset($this->_timer[$name2]))) {
			$name2 = "end";
			$this->markTime($name2);
		}
		$timeArray1 = explode(' ', $this->_timer[$name1]);
		$timeArray2 = explode(' ', $this->_timer[$name2]);
		$time1 = $timeArray1[0] + $timeArray1[1];
		$time2 = $timeArray2[0] + $timeArray2[1];
		$elapsedTime = number_format(($time2 - $time1), $decimals);
		return $elapsedTime;
	}

	/**
	 * Zwrocenie tablicy z czasami
	 * 
	 * @access public
	 * @return array
	 * 
	 */		
	public function getTimer() {
		return $this->_timer;
	}

	/**
	 * Zrzut zawartosci tablicy z czasami
	 * 
	 * @access public
	 * @return void
	 * 
	 */	
	public function __toString() {
		$out = "";
		$out .= "<pre>\n";
		$out .= print_r($this->_timer, true);
		$out .= "</pre>\n";
		return $out;
	}
}
?>
