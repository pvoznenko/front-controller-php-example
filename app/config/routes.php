<?php

return [
    new app\Route('GET', '/test/(?<id>[0-9]+)', "MusicPlayer\\controllers\\TestController"),
    new app\Route('POST', '/address', "Acme\\Library\\Controller\\TestController")
];