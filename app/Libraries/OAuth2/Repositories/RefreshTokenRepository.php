<?php namespace App\Libraries\OAuth2\Repositories;

use App\Libraries\OAuth2\Entities\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * Class RefreshTokenRepository
 * @package App\Libraries\OAuth2\Repositories
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // Some logic to persist the refresh token in a database
    }

    /**
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId)
    {
        // Some logic to revoke the refresh token in a database
    }

    /**
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        return false; // The refresh token has not been revoked
    }

    /**
     * @return RefreshTokenEntity|RefreshTokenEntityInterface|null
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
    }
}