<?php
namespace App\Containers;

use App\BaseContainer;
use App\Exceptions\BadRequestException;
use App\Interfaces\ContainerInterface;
use App\Services\SpotifyAPI;

/**
 * Class Response
 * @package App\Containers
 *
 * Container to handle Spotify search response object
 *
 * @method $this setType(string $type) - set type of search
 * @method int getTotal() - returns total of found rows
 */
class SpotifySearchResponseContainer extends BaseContainer implements ContainerInterface
{
    /**
     * Items from response
     *
     * @var array
     */
    protected $items;

    /**
     * Limit of items per request
     *
     * @var int
     */
    protected $limit;

    /**
     * Limit of items per request
     *
     * @var int
     */
    protected $offset;

    /**
     * Limit of items per request
     *
     * @var int
     */
    protected $total;

    /**
     * Search type
     *
     * @var string
     */
    protected $type = '';

    /**
     * Containers to data type mapper
     *
     * @var array
     */
    private $containersMap = [
        SpotifyAPI::SPOTIFY_SEARCH_TYPE_ALBUM => '\App\Containers\SpotifySearchAlbumContainer',
        SpotifyAPI::SPOTIFY_SEARCH_TYPE_ARTIST => '\App\Containers\SpotifySearchArtistContainer',
        SpotifyAPI::SPOTIFY_SEARCH_TYPE_TRACK => '\App\Containers\SpotifySearchTrackContainer',
    ];

    /**
     * Will returns data from items array mapped to specific class
     *
     * @throws BadRequestException
     * @return array|SpotifySearchAlbumContainer[]|SpotifySearchArtistContainer[]|SpotifySearchTrackContainer[]
     */
    public function getData()
    {
        if ($this->type != '') {
            if ($this->total == 0) {
                return [];
            }

            $className = $this->containersMap[$this->type];
            $items = [];

            /** @var \stdClass $item */
            foreach($this->items as $item) {
                $class = new $className($item);

                if ($class instanceof SpotifySearchTrackContainer) {
                    $class->setAlbum($class->getAlbum())
                        ->setArtists($class->getArtists());
                }

                $items[] = $class;
            }

            return $items;
        } else {
            throw new BadRequestException('Type of search should be specified!');
        }
    }
}