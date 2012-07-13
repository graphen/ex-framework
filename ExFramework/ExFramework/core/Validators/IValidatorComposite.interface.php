<?php

/**
 * @interface IValidatorComposite
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IValidatorComposite extends IValidator {
	
	public function addValidator($validator);
	public function removeValidators();
	public function setValidators(Array $validators);
	public function getValidators();

}

?>
