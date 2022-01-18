<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/*

// Basic usage
CookieManager::set('testcookie', 'test');
print(CookieManager::get('testcookie'));

// You can set 'array' cookies in two different ways:
CookieManager::set('array[one]', 'item one');
CookieManager::set(array('array' => 'two'), 'item two');

// Likewise, you can also get 'array' cookies in two different ways:
print(CookieManager::get('array[one]'));
print(CookieManager::get(array('array' => 'one')));

// Or you can grab the whole array at once:
print_r(CookieManager::get('array'));
 
// Deleting cookies is done in the same way:
CookieManager::del('array[one]');
CookieManager::del(array('array' => 'two'));

// Delete the entire array:
CookieManager::del('array');
 
// Print contents of $_COOKIE (refresh for this)
print '<pre>';
print_r(CookieManager::contents());
print '<pre>';

*/

class CookieManager {

	// Reserved session keys
	private static $_reserved = array();

	// Static class cannot be initialized
	private function __construct() {}

	// Alias for delete() function
	public static function del ($key) {
		self::delete($key);
	}

	// Delete a cookie
	public static function delete ($key) {
		// Change string representation array to key/value array
		$key = self::_scrubKey($key);
		// Make sure the cookie exists
		if (self::exists($key)) {                  
			// Check for key array
			if (is_array($key)) {
				// Grab key/value pair
				list ($k, $v) = each($key);
				// Set string representation
				$key = $k . '[' . $v . ']';
				// Set expiration time to -1hr (will cause browser deletion)
				setcookie($key, false, time() - 3600);
				// Unset the cookie
				unset($_COOKIE[$k][$v]);
			}
			// Check for cookie array
			else if (is_array($_COOKIE[$key])) {
				foreach ($_COOKIE[$key] as $k => $v) {
					// Set string representation
					$cookie = $key . '[' . $k . ']';
					// Set expiration time to -1hr (will cause browser deletion)
					setcookie($cookie, false, time() - 3600);
					// Unset the cookie
					unset($_COOKIE[$key][$k]);
				}
			}
			// Unset single cookie
			else {
				// Set expiration time to -1hr (will cause browser deletion)
				setcookie($key, false, time() - 3600);
				// Unset key
				unset($_COOKIE[$key]);
			}
		}
	}
	
	// See if a cookie key exists
	public static function exists ($key) {
		// Change string representation array to key/value array
		$key = self::_scrubKey($key);
		// Check for array
		if (is_array($key)) {
			// Grab key/value pair
			list ($k, $v) = each($key);
			// Check for key/value pair and return
			if (isset($_COOKIE[$k][$v]))
				return true;
		}
		// If key exists, return true
		else if (isset($_COOKIE[$key]))
			return true;
		// Key does not exist
		return false;
	}
	
	// Get cookie information
	public static function get ($key) {
		// Change string representation array to key/value array
		$key = self::_scrubKey($key);
		// Check for array
		if (is_array($key)) {
			// Grab key/value pair
			list ($k, $v) = each($key);
			// Check for key/value pair and return
			if (isset($_COOKIE[$k][$v])) return $_COOKIE[$k][$v];
		}
		// Return single key if it's set
		else if (isset($_COOKIE[$key]))
			return $_COOKIE[$key];
		// Otherwise return null
		else return null;
	}

	// Return the cookie array
	public static function contents () {
		return $_COOKIE;
	}
	
	// Set cookie information
	public static function set ($key, $value, $expire=0, $path='', $domain='', $secure=false, $httponly=true) {          
		// Make sure they aren't trying to set a reserved word
		if (!in_array($key, self::$_reserved)) {          
			// If $key is in array format, change it to string representation
			$key = self::_scrubKey($key, true);
			// Store the cookie
			setcookie($key, $value, $expire, $path, $domain, $secure, $httponly);  
		}
		// Otherwise, throw an error
		else Error::warning('Could not set key -- it is reserved.', __CLASS__);
	}

	public static function create ($name, $value='', $maxage=0, $path='', $domain='', $secure=false, $HTTPOnly=false) {
		$ob = ini_get('output_buffering');
		// Abort the method if headers have already been sent, except when output buffering has been enabled
		if ( headers_sent() && (bool) $ob === false || strtolower($ob) == 'off') {
			echo 'Error while setting '.$name.' cookie : headers already sent...<br/>';
			return false;
		}		
		if (!empty($domain)) {
			// Fix the domain to accept domains with and without 'www.'.
			if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
				$domain = substr($domain, 4);
			// Add the dot prefix to ensure compatibility with subdomains
			if ( substr($domain, 0, 1) != '.' )
				$domain = '.'.$domain;
			// Remove port information.
			$port = strpos($domain, ':');
			if ( $port !== false )
				$domain = substr($domain, 0, $port);
		}
		// Prevent "headers already sent" error with utf8 support (BOM)
		//if ( utf8_support ) header('Content-Type: text/html; charset=utf-8');
		
		header('Set-Cookie: '	.rawurlencode($name).'='.rawurlencode($value)
					.(empty($domain) ? '' : '; Domain='.$domain)
					.(empty($maxage) ? '' : '; Max-Age='.$maxage)
					.(empty($path) ? '' : '; Path='.$path)
					.(!$secure ? '' : '; Secure')
					.(!$HTTPOnly ? '' : '; HttpOnly'), false);
		return true;
	} 

	// Converts strings to arrays (or vice versa if toString = true)
	private static function _scrubKey ($key, $toString=false) {
		// Converting from array to string
		if ($toString) {
			// If $key is in array format, change it to string representation
			if (is_array($key)) {
				// Grab key/value pair
				list ($k, $v) = each($key);
				// Set string representation
				$key = $k . '[' . $v . ']';
			}
		}
		// Converting from string to array
		else if (!is_array($key)) {
			// is this a string representation of an array?
			if (preg_match('/([\w\d]+)\[([\w\d]+)\]$/i', $key, $matches)) {
			        // Store as key/value pair
			        $key = array($matches[1] => $matches[2]);
			}
		}
		// Return key
		return $key;
	}
}

?>
