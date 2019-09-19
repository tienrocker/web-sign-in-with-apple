<?php

namespace Apple;

use \Firebase\JWT\JWK;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key;

class Apple
{
    /**
     * Private key download from your apple developer account
     *
     * @var string
     */
    public static $privateKey = '';

    /**
     * Find the 10-char Key ID value from the portal
     *
     * @var string
     */
    public static $key_id = '';

    /**
     * Your 10-character Team ID
     *
     * @var string
     */
    public static $team_id = '';

    /**
     * Your Services ID, e.g. com.company.product, normally use revert domain namespace
     *
     * @var string
     */
    public static $client_id = '';

    /**
     * Redirect URLs must be registered with Apple. You can register up to 10.
     * Apple will throw an error with IP address URLs on the authorization screen,
     * and will not let you add localhost in the developer portal.
     *
     * @var string
     */
    public static $redirect_uri = '';

    /**
     * Root URI
     *
     * @var string
     */
    public static $original_uri = '';

    /**
     * Generating the JWT, The client secret is a ECDSA signed JWT using the key you get from the developer portal
     *
     * @return \Lcobucci\JWT\Token
     * @see https://developer.apple.com/documentation/signinwithapplerestapi/generate_and_validate_tokens#3262048
     */
    public static function create_client_secret()
    {
        $signer = new Sha256();
        $pKey = new Key(static::$privateKey);
        $time = time();

        $client_secret = (new Builder())
            ->withHeader('alg', 'ES256')
            ->withHeader('kid', static::$key_id)
            ->issuedBy(static::$team_id)
            ->issuedAt($time)
            ->expiresAt($time + 86400)// 1 days
            ->permittedFor('https://appleid.apple.com')
            ->withClaim('sub', static::$client_id)
            ->getToken($signer, $pKey);
        return $client_secret;
    }


    /**
     * @param $data
     *
     * @return mixed
     * @throws \Exception
     */
    public static function validate_tokens($data)
    {
        $response = static::http('https://appleid.apple.com/auth/token', $data);
        if (!isset($response['access_token'])) throw new \Exception('Error getting an access token: ' . json_encode($response));
        return $response;
    }

    /**
     * @param $code
     *
     * @return mixed
     * @throws \Exception
     */
    public static function get_web_sign_in_callback($code)
    {
        $client_secret = static::create_client_secret();

        $response = static::validate_tokens([
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => static::$redirect_uri,
            'client_id'     => static::$client_id,
            'client_secret' => $client_secret->__toString(),
        ]);

        return $response;
    }

    /**
     * In ios app you can get base64 string via apple id credential, example:
     *
     * let token = appleIDCredential.identityToken
     * let base64String = token?.base64EncodeString()
     *
     * @param $base64
     *
     * @return object
     */
    public static function verify_base64($base64)
    {
        return static::verify(base64_decode($base64));
    }

    /**
     * Verify apple jwt
     *
     * @param $jwt
     *
     * @return object
     * @see https://appleid.apple.com/auth/keys
     */
    public static function verify($jwt)
    {
        $data = json_decode(file_get_contents('https://appleid.apple.com/auth/keys'), true);
        $res = JWK::parseKeySet($data);
        $public_key = openssl_pkey_get_details($res[$data['keys'][0]['kid']]);

        $key = openssl_get_publickey($public_key['key']);
        return JWK::decode($jwt, $key, ['RS256']);
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return mixed
     */
    public static function http($url, $params = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!empty($params)) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: curl-php-sign-in-with-apple',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}