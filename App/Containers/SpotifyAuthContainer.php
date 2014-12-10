<?php
namespace App\Containers;

use App\BaseContainer;
use App\Interfaces\ContainerInterface;

/**
 * Class SpotifyAuthContainer
 * @package App\Containers
 *
 * Container to handle Spotify API Auth response
 *
 * @method string getAccessToken() - returns access token
 * @method string getTokenType() - returns token type
 * @method int getExpiresIn() - get expires in
 * @method int getRequestedAt() - get time when token was requested
 * @method $this setRequestedAt(int $time) - set time when token was requested
 */
class SpotifyAuthContainer extends BaseContainer implements ContainerInterface
{
    /**
     * Access token
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * Token type
     *
     * @var string
     */
    protected $tokenType = '';

    /**
     * Expires in
     *
     * @var int
     */
    protected $expiresIn = 0;

    /**
     * Time when token was requested
     *
     * @var int
     */
    protected $requestedAt = 0;
} 