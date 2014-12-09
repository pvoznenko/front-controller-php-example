<?php
namespace app\containers;

use app\BaseContainer;
use app\interfaces\ContainerInterface;
use app\interfaces\SpotifySearchEntityInterface;

/**
 * Class SpotifySearchArtistContainer
 * @package app\containers
 *
 * Container to handle Spotify artist object
 *
 * @method string getName() - returns name of artist
 */
class SpotifySearchArtistContainer extends BaseContainer implements ContainerInterface, SpotifySearchEntityInterface
{
    /**
     * Artist name
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