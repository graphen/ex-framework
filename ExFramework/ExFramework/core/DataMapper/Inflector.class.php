<?php

/**
 * @class Inflector
 *
 * @author Przemysław Szamraj [BORG]
 * @version 1
 * @copyright Przemysław Szamraj
 * 
 */
class Inflector implements IInflector {
	
	/**
	 * Postfix dla klas mapperow
	 *
	 * @var string
	 * 
	 */		
	protected $_mapperClassPostfix = 'Mapper';
	
	/**
	 * Konstruktor
	 * 
	 * @access public
	 * 
	 */			
	public function __construct() {
		//
	}
		
	/**
	 * Tworzy nazwe klasy obiektow biznesowych na podstawie nazw klas mapperow
	 * 
	 * @access public
	 * @param string Nazwa klasy mappera
	 * @return string 
	 * 
	 */
	public function makeEntityClassNameFromMapperClassName($mapperClassName) {
		return str_replace($this->_mapperClassPostfix, '', $mapperClassName);
	}
	
	/**
	 * Tworzy id obiektu biznesowego na podstawie jego nazwy klasy
	 * 
	 * @access public
	 * @param string Nazwa klasy obiektu biznesowego
	 * @return string
	 * 
	 */
	public function makeEntityNameFromEntityClassName($className) {
		return strtolower($className);
	}
	
	/**
	 * Tworzy nazwe tabeli obiektu biznesego na podstawie nazwy klasy tego obiektu
	 * 
	 * @access public
	 * @param string Nazwa klasy obiektu biznesowego
	 * @return string
	 * 
	 */
	public function makeEntityTableNameFromEntityClassName($className) {
		return $this->pluralize(strtolower($className));
	}
	
	/**
	 * Tworzy nazwe klasy obiektow mapperow na podstawie nazw klas obiektow biznesowych
	 * 
	 * @access public
	 * @param string Nazwa klasy obiektu biznesowego
	 * @return string 
	 * 
	 */
	public function makeMapperClassNameFromEntityClassName($entityClassName) {
		return $entityClassName . $this->_mapperClassPostfix;
	}
	
	/**
	 * Tworzy ciag znakow, w ktorym kazdy wyraz zaczyna sie duza litera, z ciagu w ktorym wyrazy oddzielone sa znakami _
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */
	public function camelize($name) {
		$name = str_replace('_', ' ', $name); 
		$name = ucwords($name);
		$name = str_replace(' ', '', $name);
		return $name;
	}
	
	/**
	 * Tworzy ciag znakow, w ktorym poszczegolne wyrazy oddzielone sa znakami _, z ciagu wejsciowego w ktorym wyrazy zaczynaja sie duzymi literami
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */
	public function uncamelize($name) {
		$name = lcfirst($name);
		$name = preg_replace('/([A-Z][a-z]+)/', '_\\1', $name);
		$name = preg_replace('/([a-z]*)([A-Z]+)(_)/', '\\1_\\2\\3', $name);
		$name = strtolower($name);
		return $name;
	}
	
	/**
	 * Ponizsze funkcje:
	 * inflector.php 101 2005-11-26 10:20:49Z flinn $
	 * Copyright (c) Flinn Mueller
	 * This file is MIT Licensed - http://www.opensource.org/licenses/mit-license.php
	 * 
	 */
	 
	/**
	 * Zwraca liczbe mnoga dla podanego wyrazu w l.poj. dla wyrazow angielskich
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */	 
	 public function pluralize($word) {
        $result = strval($word);
        if (in_array(strtolower($result), $this->uncountableWords())) {
            return $result;
        } else {
			$pluralRules = $this->pluralRules();
            foreach($pluralRules AS $rule => $replacement) {
                if (preg_match($rule, $result)) {
                    $result = preg_replace($rule, $replacement, $result);
                    break;
                }
            }
            return $result;
        }
    }
	
	/**
	 * Zwraca liczbe pojedyncza dla podanego wyrazu w l.mn. dla wyrazow angielskich
	 * 
	 * @access public
	 * @param string ciag wejsciowy
	 * @return string 
	 * 
	 */	
    public function singularize($word) {
        $result = strval($word);
        if (in_array(strtolower($result), $this->uncountableWords())) {
            return $result;
        } else {
			$singularRules = $this->singularRules();
            foreach($singularRules as $rule => $replacement) {
                if (preg_match($rule, $result)) {
                    $result = preg_replace($rule, $replacement, $result);
                    break;
                }
            }
            return $result;
        }
    }	
	
	/**
	 * Zwraca tablice wyrazow nieodmiennych dla j. angielskiego
	 * 
	 * @access protected
	 * @return array 
	 * 
	 */
    protected function uncountableWords() {
        return array( 'equipment', 'information', 'rice', 'money', 'species', 'series', 'fish' );
    }
	
	/**
	 * Zwraca tablice regul tworzenia l.mn. dla j. angielskiego
	 * 
	 * @access protected
	 * @return array 
	 * 
	 */
    protected function pluralRules() {
        return array(
            '/^(ox)$/'                => '\1\2en',     # ox
            '/([m|l])ouse$/'          => '\1ice',      # mouse, louse
            '/(matr|vert|ind)ix|ex$/' => '\1ices',     # matrix, vertex, index
            '/(x|ch|ss|sh)$/'         => '\1es',       # search, switch, fix, box, process, address
            #'/([^aeiouy]|qu)ies$/'    => '\1y', -- seems to be a bug(?)
            '/([^aeiouy]|qu)y$/'      => '\1ies',      # query, ability, agency
            '/(hive)$/'               => '\1s',        # archive, hive
            '/(?:([^f])fe|([lr])f)$/' => '\1\2ves',    # half, safe, wife
            '/sis$/'                  => 'ses',        # basis, diagnosis
            '/([ti])um$/'             => '\1a',        # datum, medium
            '/(p)erson$/'             => '\1eople',    # person, salesperson
            '/(m)an$/'                => '\1en',       # man, woman, spokesman
            '/(c)hild$/'              => '\1hildren',  # child
            '/(buffal|tomat)o$/'      => '\1\2oes',    # buffalo, tomato
            '/(bu)s$/'                => '\1\2ses',    # bus
            '/(alias|status)/'        => '\1es',       # alias
            '/(octop|vir)us$/'        => '\1i',        # octopus, virus - virus has no defined plural (according to Latin/dictionary.com), but viri is better than viruses/viruss
            '/(ax|cri|test)is$/'      => '\1es',       # axis, crisis
            '/s$/'                    => 's',          # no change (compatibility)
            '/$/'                     => 's'
        );
    }	
	
	/**
	 * Zwraca tablice regul tworzenia l.poj. dla j. angielskiego
	 * 
	 * @access protected
	 * @return array 
	 * 
	 */		
    protected function singularRules() { 
        return array(
            '/(matr)ices$/'         =>'\1ix',
            '/(vert|ind)ices$/'     => '\1ex',
            '/^(ox)en/'             => '\1',
            '/(alias)es$/'          => '\1',
            '/([octop|vir])i$/'     => '\1us',
            '/(cris|ax|test)es$/'   => '\1is',
            '/(shoe)s$/'            => '\1',
            '/(o)es$/'              => '\1',
            '/(bus)es$/'            => '\1',
            '/([m|l])ice$/'         => '\1ouse',
            '/(x|ch|ss|sh)es$/'     => '\1',
            '/(m)ovies$/'           => '\1\2ovie',
            '/(s)eries$/'           => '\1\2eries',
            '/([^aeiouy]|qu)ies$/'  => '\1y',
            '/([lr])ves$/'          => '\1f',
            '/(tive)s$/'            => '\1',
            '/(hive)s$/'            => '\1',
            '/([^f])ves$/'          => '\1fe',
            '/(^analy)ses$/'        => '\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
            '/([ti])a$/'            => '\1um',
            '/(p)eople$/'           => '\1\2erson',
            '/(m)en$/'              => '\1an',
            '/(s)tatuses$/'         => '\1\2tatus',
            '/(c)hildren$/'         => '\1\2hild',
            '/(n)ews$/'             => '\1\2ews',
            '/s$/'                  => ''
        );
    }
	
}

?>
