<?php

require_once INCLUDE_PATH.'Teamnet/Net/Path.php';
require_once INCLUDE_PATH.'Teamnet/Net/Query.php';

class Teamnet_Net_Url {

	private $scheme;
	private $host;
	private $port;
	private $path;
	private $query;
	private $fragment;

	protected static $defaultSchemePorts = array( 'http' => 80, 'https' => 443 );

	public function __construct( $url ) {
		if ( $url instanceof Teamnet_Net_Url ) {
			$this->scheme = $url->scheme;
			$this->host = $url->host;
			$this->port = $url->port;
			$this->path = $url->path;
			$this->query = new Teamnet_Net_Query( $url->getQuery() );
			$this->fragment = $url->fragment;
		}
		else {
			$parts = Teamnet_Net_Url::parseUrl( (string) $url );
			$this->scheme = $parts ['Scheme'];
			$this->host = $parts ['Host'];
			$this->port = empty( $parts ['Port'] ) ? null : (int) $parts ['Port'];
			$this->path = new Teamnet_Net_Path( $parts ['Path'] );
			$this->query = new Teamnet_Net_Query( array_key_exists( 'Query', $parts ) ? $parts ['Query'] : '' );
			$this->fragment = array_key_exists( 'Fragment', $parts ) ? $parts ['Fragment'] : '';
		}
	}

	/**
	 * @return the $query
	 */
	public function getQuery() {
		return $this->query;
	}

	public function query( $query ) {
		$url = new Teamnet_Net_Url( $this );
		$url->query = $url->getQuery()->merge( $query );
		return $url;
	}

	public function path( $path ) {
		$url = new Teamnet_Net_Url( $this );
		$url->path = $url->getPath()->append( $path );
		return $url;
	}

	public function __toString() {
		$url = $this->scheme . '://';
		$url .= $this->getAuthority();
		$url .= $this->path->__toString();
		if ( ! $this->query->isEmpty() ) {
			$url .= '?' . $this->query->__toString();
		}
		if ( ! empty( $this->fragment ) ) {
			$url .= '#' . $this->fragment;
		}
		return $url;
	}

	/**
	 * @return the $port
	 */
	public function getPort() {
		if ( isset( $this->port ) ) {
			return $this->port;
		}
		if ( array_key_exists( $this->scheme, self::$defaultSchemePorts ) ) {
			return self::$defaultSchemePorts [$this->scheme];
		}
		return null;
	}

	/**
	 * @return the $fragment
	 */
	public function getFragment() {
		return $this->fragment;
	}

	/**
	 * @param field_type $scheme
	 */
	public function setScheme( $scheme ) {
		$this->scheme = $scheme;
	}

	/**
	 * @param field_type $host
	 */
	public function setHost( $host ) {
		$this->host = $host;
	}

	/**
	 * @param field_type $port
	 */
	public function setPort( $port ) {
		$this->port = $port;
	}

	/**
	 * @param field_type $path
	 */
	public function setPath( $path ) {
		if ( ! $path instanceof Teamnet_Net_Path ) {
			$path = new Teamnet_Net_Path( $path );
		}
		$this->path = $path;
	}

	/**
	 * @param field_type $query
	 */
	public function setQuery( $query ) {
		if ( ! $query instanceof Teamnet_Net_Query ) {
			$query = new Teamnet_Net_Query( $query );
		}
		$this->query = $query;
	}

	/**
	 * @param field_type $fragment
	 */
	public function setFragment( $fragment ) {
		$this->fragment = $fragment;
	}

	/**
	 * @return the $scheme
	 */
	public function getScheme() {
		return $this->scheme;
	}

	public function getAuthority() {
		$authority = $this->host;
		if ( ! empty( $this->port ) ) {
			$authority .= ':' . $this->port;
		}
		return $authority;
	}

	/**
	 * @return the $host
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * @return the $path
	 */
	public function getPath() {
		return $this->path;
	}

	protected static function parseUrl( $url ) {
		if ( preg_match( '/^(?P<Scheme>.*?):\\/\\/(?P<Host>[^\\/\\?:;]*)(:(?P<Port>[0-9]+))?(?P<Path>[^\\?;]*)(\\?(?P<Query>[^#]*))?(#(?P<Fragment>.*))?$/', $url, $matches ) != 1 ) {
			throw new ErrorException( 'Illegal URL \'' . $url . '\.' );
		}
		return $matches;
	}

}
