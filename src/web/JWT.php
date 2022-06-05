<?php

namespace mgine\web;

use mgine\base\Component;
use mgine\helpers\{ClassHelper,FileHelper};
use Firebase\JWT\{Key, JWT as BaseJWT};
use stdClass;
use OpenSSLAsymmetricKey;

/**
 * JWT
 *
 * @link https://jwt.io
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class JWT extends Component
{
    public ?string $jwtToken;

    public Key $key;

    public string|OpenSSLAsymmetricKey|false $keyMaterial;

    public string $alg = 'RS256'; // HS256

    public bool $jwtCookieValidation = true;

    public string $jwtCookieName = 'jwt';

    public ?stdClass $payload = null;

    public string $publicKeyFilename = 'public.pem';

    /**
     * @param HttpHeaders $headers
     * @param string|null $state
     * @param $alg
     * @throws \mgine\base\InvalidConfigException
     */
    public function __construct(HttpHeaders $headers, ?string $state = null, $alg = 'RS256')
    {
        $this->alg = $alg;

        $this->setKeyObject();

        $this->jwtToken = $state ?? $headers->getAuthorization('Bearer');

        parent::__construct();
    }

    /**
     * @return bool
     */
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

    /**
     * @return void
     * @throws \Exception
     */
    protected function setKeyObject(): void
    {
        if(str_starts_with($this->alg, 'RS')){
            $this->tryToLoadPublicKey();
        }

        $this->key = new Key($this->keyMaterial, $this->alg);
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function tryToLoadPublicKey(): void
    {
        $filename = ClassHelper::getNamespacePath($this) . DIRECTORY_SEPARATOR . $this->publicKeyFilename;

        $this->keyMaterial = FileHelper::readPublicKeyFile($filename);

        if($this->keyMaterial === false){
            throw new \Exception('Invalid Public Key.');
        }
    }

    /**
     * @return string|null
     */
    private function cookieToken(): ?string
    {
        if(!isset($_COOKIE[$this->jwtCookieName])){
            return null;
        }

        return $_COOKIE[$this->jwtCookieName];
    }

    /**
     * @param $cookieName
     * @param $state
     * @param stdClass $payload
     * @return bool
     */
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