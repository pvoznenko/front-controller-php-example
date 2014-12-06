<?php
namespace MusicPlayer\controllers;

use app\BaseController;
use MusicPlayer\models\Users;

/**
 * Class UsersController
 * @package MusicPlayer\controllers
 */
class UsersController extends BaseController
{
    /**
     * Current method fake user authentication and response with auth token that needed for further manipulations
     */
    public function authentication()
    {
        /**
         * Lets generate random token, this is only in scope of our test application
         */
        $token = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));

        (new Users())->addUser($token);

        $this->response->addHeader('201 Created')->send(['token' => $token]);
    }
} 