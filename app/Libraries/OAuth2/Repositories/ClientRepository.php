<?php namespace App\Libraries\OAuth2\Repositories;

use App\Libraries\OAuth2\Entities\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class ClientRepository
 * @package App\Libraries\OAuth2\Repositories
 */
class ClientRepository implements ClientRepositoryInterface
{
    const REDIRECT_URI = 'https://oauth.pstmn.io/v1/callback';

    /**
     * @param string $clientIdentifier
     * @return ClientEntity|ClientEntityInterface|null
     */
    public function getClientEntity($clientIdentifier)
    {
        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName(getenv('app.name'));
        $client->setRedirectUri(ClientRepository::REDIRECT_URI);
        $client->setConfidential();
        return $client;
    }

    /**
     * @param string $clientIdentifier
     * @param string|null $clientSecret
     * @param string|null $grantType
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $clients = [
            'test' => [
                'secret'          => password_hash('test123', PASSWORD_BCRYPT),
                'name'            => getenv('app.name'),
                'redirect_uri'    => ClientRepository::REDIRECT_URI,
                'is_confidential' => true,
            ],
        ];
        if(array_key_exists($clientIdentifier, $clients) === false) return false;
        if (
            $clients[$clientIdentifier]['is_confidential'] === true
            && password_verify($clientSecret, $clients[$clientIdentifier]['secret']) === false
        ) return false;
        return true;
    }
}