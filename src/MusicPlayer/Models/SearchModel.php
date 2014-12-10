<?php
namespace MusicPlayer\Models;

use App\Containers\CacheDataContainer;
use App\DataLayer\BaseModel;
use App\Interfaces\CacheInterface;
use App\Interfaces\SpotifyAPIInterface;
use App\ServiceContainer;
use App\Services\SpotifyAPI;
use App\DataLayer\BaseEntity;
use App\Containers\SpotifySearchResponseContainer;
use App\Interfaces\SpotifySearchEntityInterface;

/**
 * Class SearchModel
 * @package MusicPlayer\Models
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
        parent::__construct();
    }

    /**
     * Perform search
     *
     * @param string $query
     * @param string $type - search type, one of SpotifyAPI::SPOTIFY_SEARCH_TYPE_*
     * @param int $page - page number
     *
     * @return SpotifySearchResponseContainer
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
     * @throws \App\Exceptions\BadRequestException
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