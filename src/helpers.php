<?php

use SandboxDev\DBEncryption\Encryption;

if (!function_exists('isEncryption')) {
	/**
	 * @return bool
	 */
	function isEncryption()
	{
		return config('app.encryption');
	}
}

if (!function_exists('encryptString')) {
	/**
	 * @param string|null $data
	 * @return string
	 */
	function encryptString(string $data = null)
	{
		return app(Encryption::class)->encrypt($data);
	}
}

if (!function_exists('decryptString')) {
	/**
	 * @param string|null $data
	 * @return string
	 */
	function decryptString(string $data = null)
	{
		return app(Encryption::class)->decrypt($data);
	}
}

if (!function_exists('encryptField')) {
	/**
	 * @param array $data
	 * @param array $fields
	 * @return array
	 */
	function encryptField(array $data = [], array $fields = [])
	{
		if (empty($fields)) return $data;

		foreach ($fields as $field) {
			if (isset($data[$field])) {
				$data[$field] = encryptString($data[$field]);
			}
		}

		return $data;
	}
}

if (!function_exists('decryptField')) {
	/**
	 * @param array $data
	 * @param array $fields
	 * @return array
	 */
	function decryptField(array $data = [], array $fields = [])
	{
		if (empty($fields)) return $data;

		foreach ($fields as $field) {
			if (isset($data[$field])) {
				$data[$field] = decryptString($data[$field]);
			}
		}

		return $data;
	}
}