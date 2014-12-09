<?php
namespace app\containers;

use app\BaseContainer;
use app\interfaces\ContainerInterface;
use app\interfaces\SpotifySearchEntityInterface;

/**
 * Class SpotifySearchArtistContainer
 * @package app\containers
 *
 * Container to handle Spotify track object
 *
 * @method string getName() - returns name of track
 * @method $this setArtists(array $artists) - set artists list
 * @method $this setAlbum(SpotifySearchAlbumContainer $album) - set album
 */
class SpotifySearchTrackContainer extends BaseContainer implements ContainerInterface, SpotifySearchEntityInterface
{
    /**
     * Track name
     *
     * @var string
     */
    protected $name;

    /**
     * Album
     *
     * @var \stdClass
     */
    protected $album;

    /**
     * Artists
     *
     * @var \stdClass
     */
    protected $artists;

    /**
     * Returns album
     *
     * @return SpotifySearchAlbumContainer
     */
    public function getAlbum()
    {
        if (!($this->album instanceof SpotifySearchAlbumContainer)) {
            $this->album = new SpotifySearchAlbumContainer($this->album);
        }

        return $this->album;
    }

    /**
     * Returns artists
     *
     * @return array|SpotifySearchArtistContainer[]
     */
    public function getArtists()
    {
        if (empty($this->artists)) {
            return $this->artists;
        }

        if (!($this->artists[0] instanceof SpotifySearchArtistContainer)) {
            $artists = [];

            /** @var \stdClass $artist */
            foreach($this->artists as $artist) {
                $artists[] = new SpotifySearchArtistContainer($artist);
            }

            $this->artists = $artists;
        }

        return $this->artists;
    }

    /**
     * Method cast SpotifySearchArtistContainer to list of list
     *
     * @return array
     */
    private function getArtistsToArray()
    {
        $artists = $this->getArtists();
        $items = [];

        foreach($artists as $artist) {
            $items[] = $artist->toArray();
        }

        return $items;
    }

    /**
     * Method cast object to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'album' => $this->getAlbum()->toArray(),
            'artists' => $this->getArtistsToArray()
        ];
    }
}