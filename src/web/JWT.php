<?php

namespace mgine\web;

use mgine\base\Component;
use mgine\helpers\FileHelper;
use Firebase\JWT\JWT as BaseJWT;
use Firebase\JWT\Key;
use stdClass;
use OpenSSLAsymmetricKey;

class JWT extends Component
{
    public ?string $jwtToken;

    public Key $key;

    public string|OpenSSLAsymmetricKey|false $keyMaterial;

    public string $alg = 'RS256'; // HS256

    public bool $jwtCookieValidation = true;

    public string $jwtCookieName = 'jwt';

    public ?stdClass $payload = null;

    public $publicKeyFilename = 'public.pem';

    public function __construct(HttpHeaders $headers, ?string $state = null, $alg = 'RS256')
    {
        $this->alg = $alg;

        $this->setKeyObject();

        $this->jwtToken = $state ?? $headers->getAuthorization('Bearer');

        parent::__construct();
    }

    public function auth(): bool
    {
        if($this->jwtCookieValidation && $this->jwtToken === null){
            $this->jwtToken = $this->cookieToken();
        }

        if($this->jwtToken === null){
            return false;
        }

        try {
            $this->payload = @BaseJWT::decode($this->jwtToken, $this->key);

            if($this->payload instanceof \stdClass){
                if($this->jwtCookieValidation){
                    $this->setAuthCookie($this->jwtCookieName, $this->jwtToken, $this->payload);
                }

                return true;
            }

        } catch (\Exception){}

        return false;
    }

    protected function setKeyObject(): void
    {
        if(str_starts_with($this->alg, 'RS')){
            $this->tryToLoadPublicKey();
        }

        $this->key = new Key($this->keyMaterial, $this->alg);
    }

    protected function tryToLoadPublicKey(): void
    {
        $filename = $this->getNamespacePath() . DIRECTORY_SEPARATOR . $this->publicKeyFilename;

        $this->keyMaterial = FileHelper::readPublicKeyFile($filename);

        if($this->keyMaterial === false){
            throw new \Exception('Invalid Public Key.');
        }
    }

    private function cookieToken(): ?string
    {
        if(!isset($_COOKIE[$this->jwtCookieName])){
            return null;
        }

        return $_COOKIE[$this->jwtCookieName];
    }

    private function setAuthCookie($cookieName, $state, \stdClass $payload): bool
    {
        $domain = '.' . $_SERVER['SERVER_NAME'];
        $exp = $payload->exp ?? time() + 3600;

        if (PHP_VERSION_ID < 70300) {
            return setcookie($cookieName, $state, $exp, "/; samesite=None", $domain, true, false);
        } else {
            return setcookie($cookieName, $state, [
                'expires' => $exp,
                'path' => '/',
                'domain' => $domain,
                'samesite' => 'None',
                'secure' => true,
                'httponly' => true,
            ]);
        }
    }

}