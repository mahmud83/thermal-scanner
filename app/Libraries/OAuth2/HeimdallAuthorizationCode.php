<?php namespace App\Libraries\OAuth2;

use Heimdall\Heimdall;
use Heimdall\Server\HeimdallAuthorizationServer;
use App\Libraries\OAuth2\Repositories\AccessTokenRepository;
use App\Libraries\OAuth2\Repositories\AuthCodeRepository;
use App\Libraries\OAuth2\Repositories\ClientRepository;
use App\Libraries\OAuth2\Repositories\IdentityRepository;
use App\Libraries\OAuth2\Repositories\RefreshTokenRepository;
use App\Libraries\OAuth2\Repositories\ScopeRepository;
use Heimdall\Server\HeimdallResourceServer;

/**
 * Class OAuth2Server
 * @package App\Libraries\OAuth2
 */
abstract class HeimdallAuthorizationCode
{
    /**
     * @return HeimdallAuthorizationServer
     */
    static function createAuthorizationServer()
    {
        return Heimdall::initializeAuthorizationServer(
            Heimdall::withAuthorizationConfig(
                new ClientRepository(),
                new AccessTokenRepository(),
                new ScopeRepository(),
                __DIR__ . '/private.key'
            ),
            Heimdall::withAuthorizationCodeGrant(
                new AuthCodeRepository(),
                new RefreshTokenRepository()
            ),
            Heimdall::withOIDC(
                new IdentityRepository()
            )
        );
    }

    /**
     * @return HeimdallResourceServer
     */
    static function createResourceServer()
    {
        return Heimdall::initializeResourceServer(
            Heimdall::withResourceConfig(
                new AccessTokenRepository(),
                __DIR__ . '/public.key'
            )
        );
    }
}