<?php
namespace Configloader\Utility;

/**
 * Class Crypto
 *
 * Encrypted and decrypt credentials for use in .env file
 */
class Crypto
{
    public static $keys = [];

    /**
     * Crypto constructor
     */
    public static function init($keyFilePath)
    {
        $keyFile = fopen($keyFilePath, 'r');
        $keyAscii = fread($keyFile, filesize($keyFilePath));
        self::$keys[base64_encode($keyFilePath)] = \Defuse\Crypto\Key::loadFromAsciiSafeString($keyAscii);
        fclose($keyFile);
    }

    /**
     * Encrypt string
     *
     * @param $value
     * @param string $keyFilePath
     * @return string
     */
    public static function encryptCredential($value, $keyFilePath)
    {
        if (empty(self::$keys[$keyFilePath])) {
            self::init($keyFilePath);
        }
        return \Defuse\Crypto\Crypto::encrypt($value, self::$keys[base64_encode($keyFilePath)]);
    }

    /**
     * Decrypt credential
     *
     * @param $value
     * @param string $keyFilePath
     * @return string
     */
    public static function decryptCredential($value, $keyFilePath)
    {
        if (empty($value)) {
            return '';
        }
        if (substr($value, 0, 3) !== 'def' || empty($keyFilePath)) {
            // no encrypted value given or key file empty. Return value.
            return $value;
        }

        if (empty(self::$keys[base64_encode($keyFilePath)])) {
            self::init($keyFilePath);
        }
        return \Defuse\Crypto\Crypto::decrypt($value, self::$keys[base64_encode($keyFilePath)]);
    }
}
