<?php
namespace MusicPlayer\controllers;

use app\BaseController;
use MusicPlayer\models\UsersModel;

/**
 * Class UsersController
 * @package MusicPlayer\controllers
 *
 * Controller responsible for actions on users
 */
class UsersController extends BaseController
{
    /**
     * Current method fake user authentication and response with auth token that needed for further manipulations
     *
     * This method brakes rule of idempotent since on each request it will generate new outcome, but as I wrote this
     * method made only for test purposes. On real application I will implement normal user authentication.
     *
     * Will send response with status 201 and json: {token: "auth_token"}
     */
    public function authentication()
    {
        /**
         * Lets generate random token, this is only in scope of our test application
         */
        $token = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));

        (new UsersModel)->addUser($token);

        $this->response->addHeader('201 Created')->send(['token' => $token]);
    }
} 