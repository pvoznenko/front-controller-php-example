<?php
namespace app\interfaces;

/**
 * Interface SpotifySearchEntityInterface
 * @package app\interfaces
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