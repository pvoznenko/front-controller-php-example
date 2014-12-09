<?php
namespace app\containers;

use app\BaseContainer;
use app\interfaces\ContainerInterface;
use app\interfaces\SpotifySearchEntityInterface;

/**
 * Class SpotifySearchAlbumContainer
 * @package app\containers
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