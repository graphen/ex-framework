<?php

/**
 * @class FormElementInputTextPasswordAbstract
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
abstract class FormElementInputTextPasswordAbstract extends FormElementInputAbstract {
	
	/**
	 * Inicjalizuje dostepne atrybuty elementu html
	 * 
	 * @access protected
	 * @return void
	 * 
	 */		
	protected function init() {
		parent::init();
		$this->_attributes['maxlength'] = '';
		$this->_attributes['readonly'] = '';
	}
	
	/**
	 * Ustawia atrybut maxlength elementu html
	 * 
	 * @access public
	 * @param string Maksymalna dlugosc ciagu bedacego wartoscia elementu
	 * @return void
	 * 
	 */		
	public function setMaxLength($maxLength) {
		$this->setAttrib('maxlength', $maxLength);
	}
	
	/**
	 * Ustawia atrybut readonly elementu html
	 * 
	 * @access public
	 * @param bool Wlaczona/wylaczona wartosc tylko do odczytu
	 * @return void
	 * 
	 */		
	public function setReadOnly($readOnly=true) {
		if($readOnly == true) {
			$this->setAttrib('readonly', 'readonly');
		}
		else {
			$this->setAttrib('readonly', '');
		}
	}
	
	/**
	 * Zwraca wartosc atrybutu maxlength elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */
	public function getMaxLength() {
		return $this->getAttrib('maxlength');
	}
	
	/**
	 * Zwraca wartosc atrybutu readonly elementu html
	 * 
	 * @access public
	 * @return string
	 * 
	 */	
	public function getReadOnly() {
		return $this->getAttrib('readonly');
	}
	
}

?>
