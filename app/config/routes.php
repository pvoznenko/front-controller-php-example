<?php

/**
 * Bellow you can see all possible routes existing for following test application
 */
return [
    new app\Route('POST', '/users/authentication', "MusicPlayer\\controllers\\UsersController", 'authentication')
];