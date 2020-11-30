<?php namespace App\Libraries\OAuth2\Repositories;

use App\Libraries\OAuth2\Entities\UserEntity;
use Heimdall\Interfaces\IdentityRepositoryInterface;

/**
 * Class IdentityRepository
 * @package App\Libraries\OAuth2\Repositories
 */
class IdentityRepository implements IdentityRepositoryInterface
{
    /**
     * @param $identifier
     * @return UserEntity
     */
    public function getUserEntityByIdentifier($identifier)
    {
        return new UserEntity($identifier);
    }
}