<?php

namespace Sandbox\DBEncryption\Builders;

use Illuminate\Database\Eloquent\Builder;

class EncryptionEloquentBuilder extends Builder
{
    public function selectEncrypted($fields = ['*'])
    {
        $key = env('APP_ENCRYPT') ?? env('APP_KEY');
        $encryptKey = substr(hash('sha256', $key), 0, 16);

        $fields = array_map(function ($field) use ($encryptKey) {
            return "AES_DECRYPT(FROM_BASE64(`{$field}`), '{$encryptKey}') as `{$field}`";
        }, $fields);

        return parent::selectRaw(implode(',', $fields));
    }

    public function selectDecrypted($fields = ['*'])
    {
        $key = env('APP_ENCRYPT') ?? env('APP_KEY');
        $encryptKey = substr(hash('sha256', $key), 0, 16);

        $fields = array_map(function ($field) use ($encryptKey) {
            return "AES_DECRYPT(FROM_BASE64(`{$field}`), '{$encryptKey}') as `{$field}`";
        }, $fields);

        return parent::selectRaw(implode(',', $fields));
    }

    public function addSelectEncrypted($fields = ['*']) {
        $key = env('APP_ENCRYPT') ?? env('APP_KEY');
        $encryptKey = substr(hash('sha256', $key), 0, 16);

        $fields = array_map(function ($field) use ($encryptKey) {
            return "AES_DECRYPT(FROM_BASE64(`{$field}`), '{$encryptKey}') as `{$field}`";
        }, $fields);

        return parent::addSelectRaw(implode(',', $fields));
    }

    public function addSelectDecrypted($fields = ['*']) {
        $key = env('APP_ENCRYPT') ?? env('APP_KEY');
        $encryptKey = substr(hash('sha256', $key), 0, 16);

        $fields = array_map(function ($field) use ($encryptKey) {
            return "AES_DECRYPT(FROM_BASE64(`{$field}`), '{$encryptKey}') as `{$field}`";
        }, $fields);

        return parent::addSelectRaw(implode(',', $fields));
    }

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

    public function whereEncryptedLike($field, $value)
    {
        $key = env('APP_ENCRYPT') ?? env('APP_KEY');
        $encryptKey = substr(hash('sha256', $key), 0, 16);

        return self::whereRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$field}`), '{$encryptKey}') USING utf8mb4) LIKE ? ", [$value]);
    }

    public function orWhereEncryptedLike($field, $value)
    {
        $key = env('APP_ENCRYPT') ?? env('APP_KEY');
        $encryptKey = substr(hash('sha256', $key), 0, 16);

        return self::orWhereRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$field}`), '{$encryptKey}') USING utf8mb4) LIKE ? ", [$value]);
    }
}
