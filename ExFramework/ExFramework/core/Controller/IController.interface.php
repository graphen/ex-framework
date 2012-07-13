<?php

/**
 * @interface IController
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IController {
	
	/**
	 * Wykonuje zadanie, wywolujac odpowiedni kontroler akcji i przekzujac uzyskane dane do obiektu odpowiedzi, oraz wywywoluje akcje tego obiektu
	 * 
	 * @access public
	 * @return void
	 * 
	 */			
	public function execute();
	
}

?>
