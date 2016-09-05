<?php

class Teamnet_Net_Query {

	private $queryMap;
	
	public function __construct( $query ) {
		if ( is_array( $query ) ) {
			$this->queryMap = $query;
		}
		else if ( $query instanceof Teamnet_Net_Query ) {
			$this->queryMap = $query->queryMap;
		} 
		else {
			$this->queryMap = Teamnet_Net_Query::parseQuery( (string ) $query );
		}
	}
	
	public function __toString() {
		$query = '';
		foreach( $this->queryMap as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach( $value as $subvalue ) {
					$this->appendQuery( $query, $key, $subvalue );
				}
			} else {
				$this->appendQuery( $query, $key, $value );
			}
		}
		return $query;
	}

	/**
	 * Enter description here ...
	 * @param query
	 */
	protected function appendQuery( &$query, $key, $value ) {
		if ( strlen( $query > 0 ) ) {
			$query .= '&';
		}
		$query .= $key;
		if ( ! empty( $value ) ) {
			$query .= '=' . $value;
		}
	}

	protected static function parseQuery( $query ) {
		$queryMap = array();
		if ( empty( $query ) ) {
			return $queryMap;
		}
		$pairs = explode( '&', $query );
		foreach( $pairs as $pair ) {
			$parts = explode( '=', $pair );
			if ( count( $parts ) > 2 ) {
				throw new ErrorException( 'Illegal Query \''.$pair.'\'.' );
			}
			self::addValue( $queryMap, $parts[0], array_key_exists( 1, $parts ) ? $parts[1] : null );
		}
		return $queryMap;
	}

	/**
	 * Enter description here ...
	 * @param queryMap
	 * @param key
	 * @param value
	 * @param currentValue
	 */
	protected static function addValue( &$queryMap, $key, $value ) {
		if ( array_key_exists( $key, $queryMap ) ) {
			$currentValue = $queryMap[$key];
			if ( is_array( $currentValue ) ) {
				array_push( $currentValue, $value );
			}
			else {
				$currentValue = array( $currentValue, $value );
			}
			$value = $currentValue;
		}
		$queryMap[$key] = $value;
	}

	public function merge( $query ) {
		if ( ! $query instanceof Teamnet_Net_Query ) {
			$query = new Teamnet_Net_Query( $query );
		}
		$queryMap = array();
		foreach( $query->queryMap as $key => $value ) {
			self::addValue( $queryMap, $key, $value );
		}
		return new Teamnet_Net_Query( $queryMap );
	}
	
	public function isEmpty() {
		return empty( $this->queryMap );
	}
	
	public function getKeys() {
		return array_keys( $this->queryMap );
	}
	
	public function getValue( $key ) {
		return $this->queryMap[$key];
	} 
	
}

