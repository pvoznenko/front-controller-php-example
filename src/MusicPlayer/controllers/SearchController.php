<?php
namespace MusicPlayer\controllers;

use MusicPlayer\MusicPlayerAuthController;
use \MusicPlayer\models\SearchModel;
use app\services\SpotifyAPI;

/**
 * Class SearchController
 * @package MusicPlayer\controllers
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
        $requestData = $this->request->getRawData();
        $method = $this->request->getMethod();
        $page = $this->getPageNumber();
        $query = $requestData[$method]['q'];

        $searchModel = new SearchModel;

        $data = $searchModel->search($query, $type, $page);
        $numberOfResults = $data->getTotal();

        $responseData['info'] = $this->getPaginationBlock($page, $numberOfResults, SpotifyAPI::SPOTIFY_DEFAULT_ITEMS_LIMIT);
        $responseData['result'] = $numberOfResults == 0 ? [] : $searchModel->createResponse($data, $type);

        $this->response->addHeader('200 OK')->send($responseData);
    }
} 