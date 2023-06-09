<?php

namespace Sandbox\DBEncryption;

class Encryption
{
	private static $method = 'aes-128-ecb';

	/**
	 * @param string $value
	 * 
	 * @return string
	 */
	public static function encrypt($value)
	{
		return openssl_encrypt($value, self::$method, self::getKey(), 0, $iv = '');
	}

	/**
	 * @param string $value
	 * 
	 * @return string
	 */
	public static function decrypt($value)
	{
		return openssl_decrypt($value, self::$method, self::getKey(), 0, $iv = '');
	}

	/**
	 * Get app key for encryption key
	 *
	 * @return string
	 */
	protected static function getKey()
	{
		$key = env('APP_ENCRYPT') ?? env('APP_KEY');
		return substr(hash('sha256', $key), 0, 16);
	}
}
