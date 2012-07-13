<?php

/**
 * @interface IFilterComposite
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IFilterComposite extends IFilter {

	public function appendFilter($filter);
	public function prependFilter($filter);
	public function addFilter($filter, $where=null);
	public function removeFilters();
	public function setFilters(Array $filters);
	public function getFilters();

}

?>
