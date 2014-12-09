<?php
namespace App\Interfaces;

/**
 * Interface SpotifySearchEntityInterface
 * @package App\Interfaces
 *
 * Interface for Spotify API Search result entities
 */
interface SpotifySearchEntityInterface
{
    /**
     * Method cast object to array
     *
     * @return array
     */
    public function toArray();
} 