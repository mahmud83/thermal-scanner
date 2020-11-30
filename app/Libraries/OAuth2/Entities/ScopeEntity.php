<?php namespace App\Libraries\OAuth2\Entities;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

/**
 * Class ScopeEntity
 * @package App\Libraries\OAuth2\Entities
 */
class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait, ScopeTrait;
}