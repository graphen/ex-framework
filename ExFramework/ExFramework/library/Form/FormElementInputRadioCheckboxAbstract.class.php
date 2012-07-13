<?php

/**
 * @class FormElementInputRadioCheckboxAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class FormElementInputRadioCheckboxAbstract extends FormElementInputAbstract {
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		parent::init();
		$this->_attributes['checked'] = '';
	}
	
	/**
	 * Ustawia atrybut checked elementu html
	 * 
	 * @access public
	 * @param bool Kontrolka Wlaczona/wylaczona
	 * @return void
	 * 
	 */		
	public function setChecked($checked=true) {
		if($checked == true) {
			$this->setAttrib('checked', 'checked');
		}
		else {
			$this->setAttrib('checked', '');
		}
	}
	
	/**
	 * Zwraca wartosc atrybutu checked elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getChecked() {
		return $this->getAttrib('checked');
	}
		
}

?>
