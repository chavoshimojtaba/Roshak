<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleWare
{
	protected static $token;
	public static $uid;

	/**
	 * JWTSecret
	 *
	 * Returns a JWT Secret...
	 *
	 * @param   void
	 * @return  string
	 */

	private static function JWTSecret()
	{
		return 'K-lyniEXe8Gm-WOA7IhUd5xMrqCBSPzZFpv02Q6sJcVtaYD41wfHRL3';
	}

	/**
	 * getToken
	 *
	 * Fetches and return the JWT Token from the request Header
	 *
	 * @param   void
	 * @return  string
	 */
	protected static function getToken()
	{ 
		$headers = getallheaders();
		$header_auth = (isset($headers['authorization']))?$headers['authorization']:$headers['Authorization']; 
		Self::$token = $header_auth;
		return $header_auth;
	}


	/**
	 * validateToken
	 *
	 * Validates the JWT Token and returns a boolean true...
	 *
	 * @param   void
	 * @return  boolean
	 */
	protected static function validateToken()
	{
		Self::getToken();
		if (Self::$token == '' || Self::$token == null) {
			return false;
		}

		try {
			$Token = explode('Bearer ', Self::$token);

			if (isset($Token[1]) && $Token == '') {
				return false;
			} 
			
			return $Token[1];
		} catch (Exception $e) {
			return false;
		}
	}


	/**
	 * createToken
	 *
	 * creates the JWT Token and returns a String...
	 *
	 * @param   void
	 * @return  String
	 */

	public function createToken($uid,$fullname,$userType)
	{
		Self::$uid = $uid;
        $current_time_base_second = time();
        $nbf = $current_time_base_second + 0;
        $exp = $current_time_base_second + 161800 * 1; 
        $payload = array(
            "iss"  => HOST,
            "aud"  => HOST,
            "iat"  => $current_time_base_second, 
            "nbf"  => $nbf, 
            "nbf"  => $nbf, 
            "exp"  => $exp,
            "data" => array( 
				"ut" => $userType, //1==>admin , 2==>member
				"full_name" => $fullname, 
                "uid" => $uid
            )
        );  
		
        try{
            return JWT::encode($payload , Self::JWTSecret() , "HS256");   
        }catch(Exception $e){
            return false;
        } 
	}

	/**
	 	* getAndDecodeToken
	 	*
	 	* Decodes and returns a boolean true or the uid.
	 	*
	 	* @param   void
	 	* @return  mixed
	*/
	public function getAndDecodeToken()
	{
		$token = Self::validateToken(); 
		try {
			if ($token !== false) {
				$decodedToken =  (array) JWT::decode($token, new Key(Self::JWTSecret(), 'HS256')); 
				if (isset($decodedToken['data'])) {
					$user = (array) $decodedToken['data']; 
					if (isset($user['uid']) ) {
						Self::$uid = $user['uid'];
						return $user['uid'];
					} 
					return false;
				} 
				return false;
			} 
			return false;
		} catch (Exception $e) { 
			if($e->getMessage() == 'Expired token'){
				return 'expired';
			}
			return false;
		}
	} 
}
