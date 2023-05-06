<?php

namespace Sandbox\DBEncryption\Builders;

use Illuminate\Database\Eloquent\Builder;

class EncryptionEloquentBuilder extends Builder
{
	public function whereEncrypted($field, $operation, $value = null)
	{
		$filter            = new \stdClass();
		$filter->field     = $field;
		$filter->operation = isset($value) ? $operation : '=';
		$filter->value     = isset($value) ? $value : $operation;
		$key = env('APP_ENCRYPT') ?? env('APP_KEY');
		$encryptKey = substr(hash('sha256', $key), 0, 16);

		return self::whereRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$filter->field}`), '{$encryptKey}') USING utf8mb4) {$filter->operation} ? ", [$filter->value]);
	}

	public function orWhereEncrypted($field, $operation, $value = null)
	{
		$filter            = new \stdClass();
		$filter->field     = $field;
		$filter->operation = isset($value) ? $operation : '=';
		$filter->value     = isset($value) ? $value : $operation;
		$key = env('APP_ENCRYPT') ?? env('APP_KEY');
		$encryptKey = substr(hash('sha256', $key), 0, 16);

		return self::orWhereRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$filter->field}`), '{$encryptKey}') USING utf8mb4) {$filter->operation} ? ", [$filter->value]);
	}
}
