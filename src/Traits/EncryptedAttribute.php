<?php

namespace Sandbox\DBEncryption\Traits;

use Sandbox\DBEncryption\Builders\EncryptionEloquentBuilder;
use Sandbox\DBEncryption\Encryption;

trait EncryptedAttribute
{

	public static $enableEncryption = true;

	/**
	 * @param $key
	 * @return bool
	 */
	public function isEncryption($key)
	{
		if (self::$enableEncryption && env('APP_ENCRYPTION', false)) {
			return in_array($key, $this->encryption);
		}
		return false;
	}

	/**
	 * @return mixed
	 */
	public function getEncryptionAttributes()
	{
		return $this->encryption;
	}

	public function getEncryptionAttribute($key)
	{
		$value = parent::getEncryptionAttribute($key);
		if ($this->isEncryption($key) && (!is_null($value) && $value != '')) {
			try {
				$value = Encryption::decrypt($value);
			} catch (\Exception $th) {
			}
		}
		return $value;
	}

	public function setAttribute($key, $value)
	{
		if ($this->isEncryption($key)) {
			try {
				$value = Encryption::encrypt($value);
			} catch (\Exception $th) {
			}
		}
		return parent::setAttribute($key, $value);
	}

	public function attributesToArray()
	{
		$attributes = parent::attributesToArray();
		if ($attributes) {
			foreach ($attributes as $key => $value) {
				if ($this->isEncryption($key) && (!is_null($value)) && $value != '') {
					$attributes[$key] = $value;
					try {
						$attributes[$key] = Encryption::decrypt($value);
					} catch (\Exception $th) {
					}
				}
			}
		}
		return $attributes;
	}

	public function newEloquentBuilder($query)
	{
		return new EncryptionEloquentBuilder($query);
	}

	public function decryptAttribute($value)
	{
		return $value ? Encryption::decrypt($value) : '';
	}

	public function encryptAttribute($value)
	{
		return $value ? Encryption::encrypt($value) : '';
	}
}
