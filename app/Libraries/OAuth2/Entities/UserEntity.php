<?php namespace App\Libraries\OAuth2\Entities;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;
use OpenIDConnectServer\Entities\ClaimSetInterface;

/**
 * Class UserEntity
 * @package App\Libraries\OAuth2\Entities
 */
class UserEntity implements UserEntityInterface, ClaimSetInterface
{
    use EntityTrait;

    /**
     * UserEntity constructor.
     * @param int $identifier
     */
    public function __construct($identifier = 1)
    {
        $this->setIdentifier($identifier);
    }

    /**
     * @return mixed|string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getClaims(): array
    {
        return [
            // profile
            'name' => 'John Smith',
            'family_name' => 'Smith',
            'given_name' => 'John',
            'middle_name' => 'Doe',
            'nickname' => 'JDog',
            'preferred_username' => 'jdogsmith77',
            'profile' => '',
            'picture' => 'avatar.png',
            'website' => 'http://www.google.com',
            'gender' => 'M',
            'birthdate' => '01/01/1990',
            'zoneinfo' => '',
            'locale' => 'US',
            'updated_at' => '01/01/2018',
            // email
            'email' => 'john.doe@example.com',
            'email_verified' => true,
            // phone
            'phone_number' => '(866) 555-5555',
            'phone_number_verified' => true,
            // address
            'address' => '50 any street, any state, 55555',
        ];
    }
}