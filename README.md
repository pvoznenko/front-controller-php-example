# Music-player

[![Build Status](https://magnum.travis-ci.com/fosco-maestro/music-player.svg?token=GBn7ue6jJozTxZP71pzj&branch=master)](https://magnum.travis-ci.com/fosco-maestro/music-player)

For this test application I chose to focus on PHP part, because long time since I wrote something from scratch on PHP
without using existing solutions. Also since I believe main goal of this test task to see how I think and code, in my
GitHub I have enough javascript projects but only one php.

I decided to implement Front Controller pattern to encapsulate the typical request/route/dispatch/response cycles.

I believe I can call it simple CRUD micro framework for creating small services on PHP.

Music player application divided on two part:
* Frontend application written with [AngularJS](https://angularjs.org/) (nothing special there, just to have it in test purpose);
* Backend API application with my custom micro service written on PHP.

In this way we can talk about handling serious load, by providing multiple frontend and backend servers with load balance
(HTTP cache servers and another middleware) in between. Otherwise I would propose instead of PHP use Scala applications
(easily to scale even monolith application and maintain).

Also in name of scalability and maintainability I would divide search and playlist functionality into two separate
micro services, but since in my micro framework I did not implement any API for monitoring, it will stay in one application.

Since data storage in this case was not really important I chose SQLite, for the production application of course it
should be changed to something more serious.

# Dear reviewer,

Please after review could you provide me with a short feedback, so I have chance to improve and do not do the same mistakes
in the future? Leave them as an Issue in GitHub repository. Thank you!

# System Requirements

To run current web server you should have at least `PHP v5.4` or higher (tested on [Travis](https://travis-ci.org/)
for PHP versions 5.4, 5.5 and 5.6) with `SQLite`.

# Run Web Server

In examples I will assume that web server runs on `http://localhost:7070/`.

## nginx

In nginx config important that `public` folder of current project specified as `root` and `location` set on `index.php`,
something like this:

```
root /var/www/public;

location / {
    try_files $uri $uri/ /index.php;
}
```

## PHP Web Server

You can use standard php web server for development / test purposes.

To start web server run following command:

```$ php -S localhost:7070 -t /var/www/public```

Your server should be accessible from `http://localhost:7070/`.

# Test

To run test, first you need to download all dependencies by using [Composer](https://getcomposer.org/):

```$ composer install```

After all dependencies you can run test with following command:

```$ ./vendor/bin/phpunit```

It will start server and run API tests.

# Application API

Application contain public API and private that requires authentication first.

You can use [cURL](http://curl.haxx.se/) to test application.

API response in JSON format.

Supported request types:

* `application/json`

If you will asked for another type, server will response with code `406`.

## Version

For current state there are no API version implemented.

## Public API

### Users

#### POST /api/users/authentication

You can get authentication token for your communication with server's private API by querying following URL:

```
$ curl -i -H Accept:application/json -X POST http://localhost:7070/api/users/authentication
```

You will get response status `201` - because this method will fake user creation and provide you with auth token for private API.

Be aware that this method brakes rule of idempotent since on each request it will generate new outcome. This
method made only for test purposes. On real application I will implement normal user authentication.

## Private API

For this part of API you need to have authentication toking provided by `/api/users/authentication`.
You should pass token in header with key `token`.

### Playlist

#### GET /api/playlist

You can get list of all playlist available for current authorized user:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/playlist -H token:{token}
```

* If everything is OK you should get response code `200` with list of available playlist.

#### GET /api/playlist/{playlistId}

You can get information about specific playlist for current authorized user:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/playlist/{playlistId} -H token:{token}
```

* If everything is OK you should get response code `200` with playlist.

#### POST /api/playlist

You can create new playlist for current authorized user:

```
$ curl -i -H Accept:application/json http://localhost:7070/api/playlist -H token:{token} -d "name={newPlaylistName}"
```

* If everything is OK you should get response code `201` with playlist;
* If you missed to post `name` variable server will response with code `400`;
* If you missed playlist with such `name` already exists server will response with code `400`.

#### PUT /api/playlist/{playlistId}

You can update playlist name for current authorized user:

```
$ curl -i -H Accept:application/json -X PUT http://localhost:7070/api/playlist/{playlistId} -H token:{token} -d "newName={newPlaylistName}"
```

* If everything is OK you should get response code `204`;
* If you missed to put `newName` variable server will response with code `400`;
* If you missed playlist with such `newName` already exists server will response with code `400`;
* If you tried to update not yours playlist (not existing for authorized user) server will response with code `404`.

#### DELETE /api/playlist/{playlistId}

You can delete playlist for current authorized user:

```
$ curl -i -H Accept:application/json -X DELETE -G http://localhost:7070/api/playlist/{playlistId} -H token:{token}
```

* If everything is OK you should get response code `204`;
* If you tried to delete not yours playlist (not existing for authorized user) server will response with code `404`.

#### PUT /api/playlist/{playlistId}/song

You can add song to the existing playlist of current authorized user:

```
$ curl -i -H Accept:application/json -X PUT http://localhost:7070/api/playlist/{playlistId}/song -H token:{token} -d "track={track}&artist={artist}&album={album}"
```

* If everything is OK you should get response code `204`;
* If you tried to add song to not yours playlist (not existing for authorized user) server will response with code `404`.

#### DELETE /api/playlist/{playlistId}/song/{songId}

You can remove song from the existing playlist of current authorized user:

```
$ curl -i -H Accept:application/json -X DELETE -G http://localhost:7070/api/playlist/{playlistId}/song/{songId} -H token:{token}
```

* If everything is OK you should get response code `204`;
* If you tried to delete song from not yours playlist (not existing for authorized user) server will response with code `404`.

### Search

## Exceptions

* For `Private API` if parameter `token` is missing you will get response with code `401`;
* For `Private API` if parameter `token` is wrong, there are no authorized user in the system with provided token, you
will get response with code `401`;
* For methods that are not in API, you will get response with code `404;
* For all uncovered exceptions you will get response with code `500`.

# Copyright

Copyright (C) 2014 Pavlo Voznenko.
