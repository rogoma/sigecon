<?php

namespace Tests\Unit;

use App\Libraries\SHAHasher;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * El hasher genera bcrypt y sigue aceptando los hashes SHA1 antiguos.
     */
    public function test_sha_hasher_supports_bcrypt_and_legacy_sha1(): void
    {
        $hasher = new SHAHasher;

        $bcrypt = $hasher->make('secreto');
        $this->assertTrue($hasher->check('secreto', $bcrypt));
        $this->assertFalse($hasher->check('otro', $bcrypt));
        $this->assertFalse($hasher->needsRehash($bcrypt));

        $legacy = sha1('secreto');
        $this->assertTrue($hasher->check('secreto', $legacy));
        $this->assertFalse($hasher->check('otro', $legacy));
        $this->assertTrue($hasher->needsRehash($legacy));
    }
}
