<?php
namespace App\Containers;

use App\BaseContainer;
use App\Interfaces\ContainerInterface;
use App\Interfaces\SpotifySearchEntityInterface;

/**
 * Class SpotifySearchArtistContainer
 * @package App\Containers
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