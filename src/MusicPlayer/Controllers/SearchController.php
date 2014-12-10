<?php
namespace MusicPlayer\Controllers;

use MusicPlayer\MusicPlayerAuthController;
use \MusicPlayer\Models\SearchModel;
use App\Services\SpotifyAPI;

/**
 * Class SearchController
 * @package MusicPlayer\Controllers
 *
 * Controller responsible for search in Spotify API
 */
class SearchController extends MusicPlayerAuthController
{
    /**
     * Method perform search in Spotify Search API
     *
     * @param string $type - search type, one of SpotifyAPI::SPOTIFY_SEARCH_TYPE_*
     */
    public function search($type)
    {
        $page = $this->getPageNumber();
        $query = $this->request->get('q', true);

        $searchModel = new SearchModel;

        $data = $searchModel->search($query, $type, $page);
        $numberOfResults = $data->getTotal();

        $responseData['info'] = $this->getPaginationBlock($page, $numberOfResults, SpotifyAPI::SPOTIFY_DEFAULT_ITEMS_LIMIT);
        $responseData['result'] = $numberOfResults == 0 ? [] : $searchModel->createResponse($data, $type);

        $this->response->addHeader('200 OK')->send($responseData);
    }
} 