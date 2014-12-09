<?php
namespace MusicPlayer\models;

use app\dataLayer\BaseModel;
use app\interfaces\SpotifyAPIInterface;
use app\ServiceContainer;
use app\services\SpotifyAPI;
use app\dataLayer\BaseEntity;
use app\containers\SpotifySearchResponseContainer;
use app\interfaces\SpotifySearchEntityInterface;

/**
 * Class SearchModel
 * @package MusicPlayer\models
 *
 * Model perform search through Spotify API
 */
class SearchModel extends BaseModel
{
    /**
     * @var SpotifyAPIInterface
     */
    protected $spotifyApi;

    public function __construct()
    {
        $this->spotifyApi = ServiceContainer::getInstance()->get('SpotifyAPI');
    }

    /**
     * Perform search
     *
     * @param string $query
     * @param string $type - search type, one of SpotifyAPI::SPOTIFY_SEARCH_TYPE_*
     * @param int $page - page number
     *
     * @return \app\containers\SpotifySearchResponseContainer
     */
    public function search($query, $type, $page)
    {
        $offset = BaseEntity::calculateOffset($page, SpotifyAPI::SPOTIFY_DEFAULT_ITEMS_LIMIT);

        return $this->spotifyApi->search($query, $type, $offset)->getData();
    }

    /**
     * Parse data and cast it to array
     *
     * @param SpotifySearchResponseContainer $response
     *
     * @return array
     *
     * @throws \app\exceptions\BadRequestException
     */
    public function createResponse(SpotifySearchResponseContainer $response)
    {
        $data = $response->getData();

        return $this->parseAndMapToArray($data);
    }

    /**
     * Parse object and map it to array
     *
     * @param SpotifySearchEntityInterface[] $items
     * @return array
     */
    protected function parseAndMapToArray(array $items)
    {
        $data = [];

        foreach($items as $item) {
            $data[] = $item->toArray();
        }

        return $data;
    }
} 