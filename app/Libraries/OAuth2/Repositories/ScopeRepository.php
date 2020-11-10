<?php namespace App\Libraries\OAuth2\Repositories;

use App\Libraries\OAuth2\Entities\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Class ScopeRepository
 * @package App\Libraries\OAuth2\Repositories
 */
class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @param string $scopeIdentifier
     * @return ScopeEntity|ScopeEntityInterface|null
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        $scopes = [
            'openid' => [
                'description' => 'Enable OpenID Connect support'
            ],
            'email' => [
                'description' => 'Your email address',
            ],
            'test' => [
                'description' => 'Your email address',
            ],
        ];
        if (array_key_exists($scopeIdentifier, $scopes) === false) return null;
        $scope = new ScopeEntity();
        $scope->setIdentifier($scopeIdentifier);
        return $scope;
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null $userIdentifier
     * @return array|ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        if ((int) $userIdentifier === 1) {
            $scope = new ScopeEntity();
            $scope->setIdentifier('email');
            $scopes[] = $scope;
        }
        return $scopes;
    }
}