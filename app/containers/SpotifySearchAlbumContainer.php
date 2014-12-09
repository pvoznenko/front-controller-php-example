<?php
namespace App\Containers;

use App\BaseContainer;
use App\Interfaces\ContainerInterface;
use App\Interfaces\SpotifySearchEntityInterface;

/**
 * Class SpotifySearchAlbumContainer
 * @package App\Containers
 *
 * Container to handle Spotify album object
 *
 * @method string getName() - returns name of album
 */
class SpotifySearchAlbumContainer extends BaseContainer implements ContainerInterface, SpotifySearchEntityInterface
{
    /**
     * Album name
     *
     * @var string
     */
    protected $name;

    /**
     * Method cast object to array
     *
     * @return array
     */
    public function toArray()
    {
        return ['name' => $this->getName()];
    }
}