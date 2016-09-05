<?php

/**
 * Splits path in segments and normalize it.
 *
 * Normalization examples:
 * 
 *  'team/net' => 'team/net'
 *  'team/net/' => 'team/net/'
 *  '/team/net' => '/team/net'
 *  './team/net' => './team/net'
 *  '../team/net' => '../team/net'
 *  '../../team/net' => '../../team/net'
 *  'team/net/.' => 'team/net/'
 *  'team/./net' => 'team/net'
 *  'team//net' => 'team/net'
 *  'team////net' => 'team/net'
 *  '///team/net' => '/team/net'
 *  'team/net///' => 'team/net/'
 *  'team/individual/../net' => 'team/net'
 *  'team/individual/../../net' => 'net'
 *  'team/individual/.././../net' => 'net'
 *  'team/individual/../../../net' => '../net'
 * 
 * Enter description here ...
 * @author rattke
 *
 */

class Teamnet_Net_Path {

	private $pathList;
	
	public function __construct( $path ) {
		if ( is_array( $path ) ) {
			$this->pathList = self::normalizePathList( $path );
		}
		else if ( $path instanceof Teamnet_Net_Path ) {
			$this->pathList = $path->getPathList();
		} 
		else {
			$this->pathList =  self::parsePath( ( string ) $path );
		}
	}
	
	protected static function parsePath( $path ) {
		$pathList = empty( $path ) ? array() : explode( '/', $path );
		return self::normalizePathList( $pathList );
	}
	
	protected static function normalizePathList( $pathList ) {
		$canonicalPathList = array();
		foreach( $pathList as $pos => $segment ) {
			if ( $segment == '..' && count( $canonicalPathList ) > 0 && end( $canonicalPathList ) != '..' && end( $canonicalPathList ) != '' ) {
				if ( array_pop( $canonicalPathList ) == '.' ) {
					// Because of path normalization, the dot must be the first element of the canonical path.
					array_push( $canonicalPathList, $segment );	
				}
			}
			else if ( $segment == '.'  && $pos != 0 ) {
				if ( $pos == count( $pathList )-1 ) {
					array_push( $canonicalPathList, '' );
				}
			}
			else if ( ! $segment == '' || $pos == 0 || $pos == count( $pathList )-1 ) {
				array_push( $canonicalPathList, $segment );
			} 
		}
		return $canonicalPathList;
	}

	public function __toString() {
		$path = '';
		foreach( $this->pathList as $position => $value ) {
			if ( $position > 0 ) {
				$path .= '/';
			}
			$path .= $value;
		}
		return $path;
	}
	
	public function append( $localPath ) {
		if ( ! $localPath instanceof Teamnet_Net_Path ) {
			$localPath = new Teamnet_Net_Path( $localPath );
		}
		$localPathList = $localPath->pathList;
		if ( empty( $localPathList ) ) {
			$pathList = $this->pathList;
		}
		else if ( $localPathList[0] == '' ) {
			$pathList = $localPathList;
		}
		else {
			$pathList = self::normalizePathList( array_merge( $this->pathList, $localPathList )  ); 
		}
		return new Teamnet_Net_Path( $pathList );
	}
	
	protected function getPathList() {
		return $this->pathList;
	}

	public function isEmpty() {
		return empty( $this->pathList );
	}

}
