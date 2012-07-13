<?php

/**
 * @interface IInflector
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
interface IInflector {
	
	/**
	 * Tworzy nazwe klasy obiektow biznesowych na podstawie nazw klas mapperow
	 * 
	 * @access public
	 * @param string Nazwa klasy mappera
	 * @return string 
	 * 
	 */
	public function makeEntityClassNameFromMapperClassName($mapperClassName);
	
	/**
	 * Tworzy id obiektu biznesowego na podstawie jego nazwy klasy
	 * 
	 * @access public
	 * @param string Nazwa klasy obiektu biznesowego
	 * @return string
	 * 
	 */
	public function makeEntityNameFromEntityClassName($className);
	
	/**
	 * Tworzy nazwe tabeli obiektu biznesego na podstawie nazwy klasy tego obiektu
	 * 
	 * @access public
	 * @param string Nazwa klasy obiektu biznesowego
	 * @return string
	 * 
	 */
	public function makeEntityTableNameFromEntityClassName($className);
	
	/**
	 * Tworzy nazwe klasy obiektow mapperow na podstawie nazw klas obiektow biznesowych
	 * 
	 * @access public
	 * @param string Nazwa klasy obiektu biznesowego
	 * @return string 
	 * 
	 */
	public function makeMapperClassNameFromEntityClassName($entityClassName);
	
	/**
	 * Tworzy ciag znakow, w ktorym kazdy wyraz zaczyna sie duza litera, z ciagu w ktorym wyrazy oddzielone sa znakami _
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */
	public function camelize($name);
	
	/**
	 * Tworzy ciag znakow, w ktorym poszczegolne wyrazy oddzielone sa znakami _, z ciagu wejsciowego w ktorym wyrazy zaczynaja sie duzymi literami
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */
	public function uncamelize($name);
	 
	/**
	 * Zwraca liczbe mnoga dla podanego wyrazu w l.poj. dla wyrazow angielskich
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */	 
	 public function pluralize($word);
	
	/**
	 * Zwraca liczbe pojedyncza dla podanego wyrazu w l.mn. dla wyrazow angielskich
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */	
    public function singularize($word);
	
}

?>
