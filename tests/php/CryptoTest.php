<?php

class CryptoTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->testData = json_decode(file_get_contents(dirname(__DIR__) . "/data.json"), true);
    }

    /**
     * @expectedException \Readdle\Crypt\InvalidArgumentException
     */
    public function testInvalidSecretLength1WithEncrypt()
    {
        \Readdle\Crypt\Crypto::encrypt($this->testData['decrypted'], $this->testData['invalidSecret1']);
    }

    /**
     * @expectedException \Readdle\Crypt\InvalidArgumentException
     */
    public function testInvalidSecretLength1WithDecrypt()
    {
        \Readdle\Crypt\Crypto::decrypt($this->testData['decrypted'], $this->testData['invalidSecret1']);
    }

    /**
     * @expectedException \Readdle\Crypt\InvalidArgumentException
     */
    public function testInvalidSecretLength2WithEncrypt()
    {
        \Readdle\Crypt\Crypto::encrypt($this->testData['decrypted'], $this->testData['invalidSecret2']);
    }

    /**
     * @expectedException \Readdle\Crypt\InvalidArgumentException
     */
    public function testInvalidSecretLength2WithDecrypt()
    {
        \Readdle\Crypt\Crypto::decrypt($this->testData['decrypted'], $this->testData['invalidSecret2']);
    }

    public function testEncrypt()
    {
        $encrypted = \Readdle\Crypt\Crypto::encrypt($this->testData['decrypted'], $this->testData['secret']);
        $this->assertEquals($this->testData['encrypted'], $encrypted);
        return $encrypted;
    }

    /**
     * @depends testEncrypt
     * @param $encrypted
     * @return string
     */
    public function testDecrypt($encrypted)
    {
        $encrypted = \Readdle\Crypt\Crypto::decrypt($encrypted, $this->testData['secret']);
        $this->assertEquals($this->testData['decrypted'], $encrypted);

        $encrypted = \Readdle\Crypt\Crypto::decrypt($this->testData['wrongEncrypted'], $this->testData['secret']);
        $this->assertEquals($this->testData['wrongEncrypted'], $encrypted);

    }

    public function testSecure()
    {
        $decrypted = \Readdle\Crypt\Crypto::decrypt($this->testData['encrypted'], $this->testData['wrongSecret']);
        $this->assertNotEquals($decrypted, $this->testData['decrypted']);
    }
}
