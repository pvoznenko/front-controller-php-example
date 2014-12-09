<?php
namespace app\services;

use app\interfaces\ServiceInterface;
use app\interfaces\CurlInterface;
use app\interfaces\CacheInterface;
use app\interfaces\SpotifyAPIInterface;
use app\ServiceContainer;
use app\exceptions\BadRequestException;
use app\containers\SpotifyAuthContainer;
use app\containers\SpotifySearchResultContainer;

/**
 * Class SpotifyAPI
 * @package app\services
 *
 * Service responsible for communication with Spotify API
 */
class SpotifyAPI implements ServiceInterface, SpotifyAPIInterface
{
    /**
     * URL for authorization
     */
    const SPOTIFY_AUTH_URL = 'https://accounts.spotify.com/api/token';

    /**
     * URL for search
     */
    const SPOTIFY_SEARCH_URL = 'https://api.spotify.com/v1/search';

    /**
     * Available search type: track
     */
    const SPOTIFY_SEARCH_TYPE_TRACK = 'track';

    /**
     * Available search type: artist
     */
    const SPOTIFY_SEARCH_TYPE_ARTIST = 'artist';

    /**
     * Available search type: album
     */
    const SPOTIFY_SEARCH_TYPE_ALBUM = 'album';

    /**
     * Limit of items from the search
     */
    const SPOTIFY_DEFAULT_ITEMS_LIMIT = 20;

    /**
     * @var CurlInterface
     */
    private $curl;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Spotify Auth object
     *
     * @var SpotifyAuthContainer|null
     */
    private $authTokenObject = null;

    /**
     * Should return unique name of the service
     *
     * @return string
     */
    public static function getServiceName()
    {
        return 'SpotifyAPI';
    }

    /**
     * Add service initializer into DI container
     *
     * @param ServiceContainer $container
     * @param mixed $injection - injectable object, default null
     */
    public static function initializeService(ServiceContainer $container, $injection = null)
    {
        $className = __CLASS__;
        $container->set(static::getServiceName(), function() use($className) { return new $className; });
    }

    protected function __construct()
    {
        $serviceContainer = ServiceContainer::getInstance();

        $this->curl = $serviceContainer->get('Curl');
        $this->cache = $serviceContainer->get('Cache');
    }

    /**
     * Method do authorization of our application in Spotify API
     *
     * @return SpotifyAuthContainer - container with auth data
     *
     * @throws BadRequestException - if response from Spotify API not 200
     */
    public function authorize()
    {
        $this->doAuthorizationIfNeeded();

        return $this->authTokenObject;
    }

    /**
     * Method do authorization of our application in Spotify API
     *
     * @return $this
     * @throws BadRequestException - if response from Spotify API not 200
     */
    private function doAuthorizationIfNeeded()
    {
        $now = time();

        /**
         * If we already have auth object and it not expired then we do not need double authorization
         */
        if ($this->authTokenObject instanceof SpotifyAuthContainer) {
            $requestedAt = $this->authTokenObject->getRequestedAt();
            $expirationTokenTime = $this->authTokenObject->getExpiresIn();

            if (($now - $requestedAt) <= $expirationTokenTime) {
                return $this;
            }
        }

        $type = 'Basic';
        $token =  base64_encode(SPOTIFY_CLIENT_ID . ':' . SPOTIFY_CLIENT_SECRETE);

        $this->curl->clearHeaders()
            ->addHeader($this->getAuthorizationHeaderFormatted($type, $token))
            ->post(self::SPOTIFY_AUTH_URL, ['grant_type' => 'client_credentials']);

        $responseCode = $this->curl->getResponseCode();

        if ($responseCode != 200) {
            throw new BadRequestException('Spotify authorization failed. Got response code: ' . $responseCode);
        }

        $response = $this->curl->getParsedResponse();

        $this->authTokenObject = new SpotifyAuthContainer($response);
        $this->authTokenObject->setRequestedAt($now);

        return $this;
    }

    /**
     * Returns formatted header for authorization on Spotify
     *
     * @return string
     */
    private function getAuthHeader()
    {
        return $this->getAuthorizationHeaderFormatted($this->authTokenObject->getTokenType(), $this->authTokenObject->getAccessToken());
    }

    /**
     * Method returns formatted authorization header
     *
     * @param string $type
     * @param string $token
     * @return string
     */
    private function getAuthorizationHeaderFormatted($type, $token)
    {
        return sprintf('Authorization: %s %s', $type, $token);
    }

    /**
     * Spotify search for music
     *
     * @param string $query - search query
     * @param string $type - type of search, allowed only SpotifyAPI::SPOTIFY_SEARCH_TYPE_*
     * @param int $offset - offset for pagination through items, default 0
     *
     * @return SpotifySearchResultContainer
     *
     * @throws BadRequestException - if wrong type provided or search failed
     */
    public function search($query, $type = '', $offset = 0)
    {
        if ($type == '') {
            $type = self::SPOTIFY_SEARCH_TYPE_TRACK;
        } else {
            $this->validateSearchType($type);
        }

        $authHeader = $this->doAuthorizationIfNeeded()->getAuthHeader();

        $params = http_build_query(['q' => $query, 'type' => $type, 'offset' => (int)$offset]);
        $searchUrl = self::SPOTIFY_SEARCH_URL . '?' . $params;

        $responseCode = $this->curl->clearHeaders()
            ->addHeader($authHeader)
            ->get($searchUrl)
            ->getResponseCode();

        if ($responseCode != 200) {
            throw new BadRequestException('Spotify search failed. Got response code: ' . $responseCode);
        }

        $response = $this->curl->getParsedResponse();

        return (new SpotifySearchResultContainer($response))->setType($type);
    }

    /**
     * Validates if provided type is allowed for search
     *
     * @param string $type
     * @throws BadRequestException
     */
    private function validateSearchType($type)
    {
        $allowedSearchTypes = [self::SPOTIFY_SEARCH_TYPE_ALBUM, self::SPOTIFY_SEARCH_TYPE_ARTIST, self::SPOTIFY_SEARCH_TYPE_TRACK];
        if (!in_array($type, $allowedSearchTypes)) {
            throw new BadRequestException(sprintf('Specified type "%s" is not allowed! Allowed types: %s', $type, implode(', ', $allowedSearchTypes)));
        }
    }
}