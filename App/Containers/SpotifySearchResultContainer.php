<?php
namespace App\Containers;

use App\BaseContainer;
use App\Exceptions\BadRequestException;
use App\Interfaces\ContainerInterface;

/**
 * Class SpotifyAuthContainer
 * @package App\Containers
 *
 * Container to handle Spotify search result object
 *
 * @method $this setType(string $type) - set type of search
 */
class SpotifySearchResultContainer extends BaseContainer implements ContainerInterface
{
    /**
     * Albums object
     *
     * @var \stdClass|null
     */
    protected $albums = null;

    /**
     * Artists object
     *
     * @var \stdClass|null
     */
    protected $artists = null;

    /**
     * Tracks object
     *
     * @var \stdClass|null
     */
    protected $tracks = null;

    /**
     * Search type
     *
     * @var string
     */
    protected $type = '';

    /**
     * Return search result data or false if no data is found
     *
     * @throws BadRequestException - if type of search is not specified
     * @return SpotifySearchResponseContainer
     */
    public function getData()
    {
        if ($this->type != '') {
            $variable = $this->type . 's';
            return (new SpotifySearchResponseContainer($this->$variable))->setType($this->type);
        } else {
            throw new BadRequestException('Type of search should be specified!');
        }
    }
}