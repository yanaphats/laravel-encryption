<?php

namespace SandboxDev\DBEncryption\Tests;

use Illuminate\Database\Eloquent\Model;
use SandboxDev\DBEncryption\Traits\EncryptedAttribute;
use SandboxDev\DBEncryption\Tests\Database\Factories\TestUserFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestUser extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $fillable = ['email', 'name', 'password'];
    protected $encryptable = ['email', 'name'];
    protected $camelcase = ['name'];

    protected static function newFactory()
    {
        return TestUserFactory::new();
    }
}