<?php
namespace App\Interfaces;

use App\Exceptions\BadRequestException;
use App\Containers\SpotifyAuthContainer;
use App\Containers\SpotifySearchResultContainer;

/**
 * Interface SpotifyAPIInterface
 * @package App\Interfaces
 *
 * Interface for the Spotify API Service
 */
interface SpotifyAPIInterface
{

    /**
     * Method do authorization of our application in Spotify API
     *
     * @return SpotifyAuthContainer
     * @throws BadRequestException - if response from Spotify API not 200
     */
    public function authorize();

    /**
     * Spotify search for music
     *
     * @param string $query - search query
     * @param string $type - type of search, allowed only SpotifyAPI::SPOTIFY_SEARCH_TYPE_*
     * @param int $offset - data offset in search for pagination
     *
     * @return SpotifySearchResultContainer
     *
     * @throws BadRequestException - if wrong type provided or search failed
     */
    public function search($query, $type = '', $offset = 0);
} 