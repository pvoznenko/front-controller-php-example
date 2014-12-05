<?php
/**
 * Created by PhpStorm.
 * User: pavlo
 * Date: 05/12/14
 * Time: 18:29
 */

namespace MusicPlayer\controllers;

use app\BaseController;


class TestController extends BaseController
{
    public function index($id)
    {
        var_dump($id);
    }
} 