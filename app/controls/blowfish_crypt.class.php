<?php
namespace app\controls;
/**
 * Bcrypt hashing class
 */
class blowfish_crypt {
/**
 * Default salt prefix
 * 
 * @see http://www.php.net/security/crypt_blowfish.php
 * 
 * @var string
 */
	protected static $_saltPrefix = '2a';
/**
 * Default hashing cost (4-31)
 * 
 * @var integer
 */
	protected static $_defaultCost = 12;
/**
 * Salt limit length
 * 
 * @var integer
 */
	protected static $_saltLength = 22;
/**
 * Hash a string
 * 
 * @param  string  $string The string
 * @param  integer $cost   The hashing cost
 * 
 * @see    http://www.php.net/manual/en/function.crypt.php
 * 
 * @return string
 */
	public static function generateHash($string, $cost = null) 
    {
		if( empty($cost) ) 
        {
			$cost = self::$_defaultCost;
		}
		// Salt
		$salt = self::generateRandomSalt();
		// Hash string
		$hashString = self::__generateHashString((int)$cost, $salt);
		return crypt($string, $hashString);
	}
/**
 * Check a hashed string
 * 
 * @param  string $string The string
 * @param  string $hash   The hash
 * 
 * @return boolean
 */
	public static function check($string, $hash) 
    {
		return (crypt($string, $hash) === $hash);
	}
/**
 * Generate a random base64 encoded salt
 * 
 * @return string
 */
	public static function generateRandomSalt()
    {
		// Salt seed
		$seed = uniqid(mt_rand(), true);
		// Generate salt
		$salt = base64_encode($seed);
		$salt = str_replace('+', '.', $salt);
		return substr($salt, 0, self::$_saltLength);
    }
/**
 * Build a hash string for crypt()
 * 
 * @param  integer $cost The hashing cost
 * @param  string $salt  The salt
 * 
 * @return string
 */
	private static function __generateHashString($cost, $salt) 
    {
		return sprintf('$%s$%02d$%s$', self::$_saltPrefix, $cost, $salt);
	}
    
}