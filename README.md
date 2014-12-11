# Music-player

[![Build Status](https://magnum.travis-ci.com/fosco-maestro/music-player.svg?token=GBn7ue6jJozTxZP71pzj&branch=master)](https://magnum.travis-ci.com/fosco-maestro/music-player)

## Dear Reviewer,

Your feedback is really important for me. Please take Your time and write all comments where You think I should do differently,
so I have chance to improve and do not do the same mistakes in the future. Thank You for Your time!

## Feedback Regarding Code Test

Thank You for such test, it was interesting. Now I wrote my own micro framework on Front Controller pattern :D Hopefully
my code is understandable and readable enough, I did my best.

I believe You should change recommendation for search API usage, since Spotify Metadata API is deprecated. Also please mentioned
that for PHP part there should be simple frontend for manual testing of functionality.

Everything else was great! Now I will wait for feedback from You! Hopefully we can work together.

If there are some questions feel free to contact me:

My Skype: p.voznenko
Email: p.voznenko@gmail.com
Twitter: pvoznenko

## My Workflow

Current project is stored in my GitHub private repository ([https://github.com/fosco-maestro/music-player](https://github.com/fosco-maestro/music-player)),
if you would like to see commit history please approach me. Each commit was tested on PHP versions `5.4`, `5.5` and
`5.6` by commercial version of Travis, so all build is private too.

## Technical Decisions

For this test application I chose to focus on PHP part, because long time since I wrote something from scratch on PHP
without using existing solutions. Also since I believe main goal of this test task to see how I think and code, in my
GitHub I have enough javascript projects but only one PHP.

I decided to implement Front Controller pattern to encapsulate the typical Request -> Route -> Dispatch -> Response cycles.

I believe I can call it simple API CRUD micro framework for creating small services on PHP.

Music player application divided on two part:
* The Frontend (frontend) application (AngularJS and Bootstrap);
* API Server (backend) with my custom micro service written on PHP.

In this way we can talk about handling serious load, by providing multiple frontend and backend servers with load balance
(HTTP cache servers and another middleware) in between.

Also in name of scalability and maintainability I would divide search and playlist functionality into two separate
micro services (in this way applications also fail tolerant - when one fails, another keep working, but I believe you
need to have monitoring tools to maintain micro services better), but because there are no monitoring tools implemented
in my micro framework, it will stay in one application.

Since data storage in this case was not really important I chose SQLite, for the production application of course it
should be changed to something more serious. In name of not using existing libraries, I used standard PHP PDO.

PDO driver with connection to the SQLight injects through constructor to DB service, I believe you can provide different
data source if needed, important to use PDO driver in this case. Also I provided abstraction Entity layer that provides
interface to communicate with PDO driver. I used common SQL syntactics, so it should be possible to inject for example
PDO driver with connection to MySQL database without changing Entity layer. If needed another storage with totally
different interface for example, you exchange DB service and Entity layer.

For the key-value storage for caching I chose Redis, since compare to analogs, in Redis if needed you can configure
data persistence with different options (also you can scale it). For work with Redis from the PHP I chose library
[Predis](https://github.com/nrk/predis) - it is only one external library used for the whole application (if not count tests).

Additionally to the Front Controller I implemented Dependency Injection Container with Services (Service Container). Each
Service is injectable and implement specific interface (so could be exchange with another object that implements the same
interface) and initialized on demand (lazy loading). List of existing Services:
* Cache - responsible for caching mechanics (currently using Redis);
* Curl - responsible for cURL data (currently cURL wrapper);
* DB - data storage (currently PDO);
* SpotifyAPI - service that responsible for authentication with Spotify API server and provides interface for search
functionality (also wraps search results in data object containers (containers)).

## API Server Life Cycle

API Server life cycle represents by following sequence:

```
Request -> Route -> Dispatch -> Controller -> Model -> Entity -> Services (Storage, etc)
```

When Controller gets all needed data from the Model it generates Response to the client.

## File Structure

Below you can find file structure of following project. From the tree folder `vendor` and `public/components` was excluded
since they contain external libraries:

```
.
├── App -- core library
│   ├── BaseContainer.php -- representation of Data container
│   ├── BaseController.php -- representation of Controller
│   ├── Containers -- folder contain Data containers (not DI Containers!). Used for Data representation
│   │   ├── CacheDataContainer.php
│   │   ├── SpotifyAuthContainer.php
│   │   ├── SpotifySearchAlbumContainer.php
│   │   ├── SpotifySearchArtistContainer.php
│   │   ├── SpotifySearchResponseContainer.php
│   │   ├── SpotifySearchResultContainer.php
│   │   ├── SpotifySearchTrackContainer.php
│   │   └── UserDataContainer.php
│   ├── DataLayer -- representation of Data layer
│   │   ├── BaseEntity.php
│   │   ├── BaseModel.php
│   │   └── Param.php
│   ├── Dispatcher.php -- Front Controller dispatcher
│   ├── Exceptions -- Custom exceptions
│   │   ├── BadRequestException.php
│   │   ├── NotAcceptableException.php
│   │   ├── NotFoundException.php
│   │   └── UnauthorizedException.php
│   ├── FrontController.php -- Front Controller
│   ├── GetterSetter.php -- abstract class provides magic getters and setters to the child
│   ├── Interfaces -- folder with interfaces
│   │   ├── CacheContainerInterface.php
│   │   ├── CacheInterface.php
│   │   ├── ContainerInterface.php
│   │   ├── CurlInterface.php
│   │   ├── PDOInterface.php
│   │   ├── RequestInterface.php
│   │   ├── ResponseInterface.php
│   │   ├── RouteInterface.php
│   │   ├── ServiceInterface.php
│   │   ├── SpotifyAPIInterface.php
│   │   └── SpotifySearchEntityInterface.php
│   ├── Request.php -- representation of request
│   ├── Response.php -- representation of response
│   ├── Route.php -- representation of route
│   ├── Router.php -- Router that dispatch Routes
│   ├── ServiceContainer.php -- Service Container
│   ├── Services -- folder with services
│   │   ├── Cache.php
│   │   ├── Curl.php
│   │   ├── DB.php
│   │   └── SpotifyAPI.php
│   └── Singleton.php -- abstract class for singleton
├── README.md
├── bower.json
├── composer.json
├── config
│   ├── config.php -- API Server configuration
│   ├── migration
│   │   └── base.sql -- API Server DB dump
│   └── routes.php -- API Server routes configuration
├── phpunit.xml.dist
├── phpunit.xml.travis.dist
├── public -- The Frontend server
│   ├── app
│   │   ├── app.js
│   │   ├── controllers
│   │   │   ├── index.js
│   │   │   ├── playlist.js
│   │   │   └── search.js
│   │   ├── init.js
│   │   ├── route.js
│   │   ├── services
│   │   │   ├── playlist.js
│   │   │   ├── search.js
│   │   │   ├── user.js
│   │   │   └── userAuth.js
│   │   └── templates
│   │       ├── playlist.html
│   │       └── search.html
│   ├── index.html
│   └── style
│       └── css
│           └── style.css
├── server
│   └── index.php -- entry point to the API Server
├── src -- folder with our API representation
│   └── MusicPlayer
│       ├── Controllers
│       │   ├── PlaylistController.php -- controller responsible for playlist functionality
│       │   ├── SearchController.php -- controller responsible for search functionality
│       │   └── UsersController.php -- controller responsible for user functionality
│       ├── Entities -- contain interfaces to communicate with data source (store data in storage)
│       │   ├── PlaylistEntity.php
│       │   ├── SongsEntity.php
│       │   └── UsersEntity.php
│       ├── Models -- model representation, use caching layer here
│       │   ├── PlaylistModel.php
│       │   ├── SearchModel.php
│       │   ├── SongsModel.php
│       │   └── UsersModel.php
│       └── MusicPlayerAuthController.php -- controller for private API
├── tests -- folder contain test
│   ├── Tests
│   │   ├── Base
│   │   │   └── WebServerTest.php
│   │   ├── BaseWebTestClass.php
│   │   ├── MusicPlayer
│   │   │   ├── PlaylistBehaviourTest.php
│   │   │   ├── SearchBehaviourTest.php
│   │   │   └── UsersBehaviourTest.php
│   │   └── PreTest.php
│   └── bootstrap.php
└── tmp - folder for temporary files, should be writable

26 directories, 82 files
```

## System Requirements

To run current API server you should have [Redis](http://redis.io/) installed and at least `PHP v5.4` with `SQLite`,
`cURL` and `mcrypt` (by default `mcrypt` in Mac OS X is not installed).

## API Server Configuration

You can configure API server through configuration file at: `config\config.php`

## Setup API Server

(Since in test task you asking to have all libraries out of the box (packed in archive), this step could be skipped)

Before running API server you need to install dependencies with [Composer](https://getcomposer.org/) by using following
command:

```
$ composer install
```

It will download all dependencies that needs for tests and create autoload file that API server uses as PSR-4 Autoloader.

Since in scope of the test task it was not recommended to use external libraries, so for API server I used only Redis
client library, all another dependencies only for testing (to test API server).

### Run API Server

In examples I will assume that API server runs on `http://localhost:7070/`. The server enter point (`index.php`) is located
in folder `server`.

#### nginx

In nginx config important that `server` folder of current project specified as `root` and `location` set on `index.php`,
something like this:

```
root /var/www/server;

location / {
    try_files $uri $uri/ /index.php;
}
```

## Test

To run tests, you need that Composer downloaded all dependencies (see section Setup). You can run tests with following
command:

```
$ ./vendor/bin/phpunit
```

It will start server on `http://localhost:7072` (you can configure `host` and `port` in `phpunit.xml.dist` file, to
successfully run tests, port should be available) and run API tests. Unfortunately I did not mock Spotify API, so
config `phpunit.xml.dist` will run tests without really testing Spotify search API.

To test search using real Spotify API, run test with config `phpunit.xml.travis.dist`:

```
$ ./vendor/bin/phpunit -c phpunit.xml.travis.dist
```

For tests I used [PHPUnit](https://phpunit.de/) and [Guzzle](http://guzzle.readthedocs.org/en/latest/) HTTP
client (for testing API).

## The Frontend Configuration

At the file `public/app/app.js` you should see constant `ApiUrl` - please set it to yours correct API Server address.

## Setup The Frontend

(Since in test task you asking to have all libraries out of the box (packed in archive), this step could be skipped)

Before running Frontend you need to install dependencies with [Bower](http://bower.io/) by using following command:

```
$ bower install
```

It will download all dependencies and put them to `public/components`.

### Web Server for The Frontend

Web server should look at `public` folder.

I run The Frontend together with API Server on the same host using nginx and the following configuration:

```
location /web {
    alias /path/to/folder/public;
}
location ~ (app|components|style) {
    root /path/to/folder/public;
}
```

In this way we avoid Cross-origin resource sharing (CORS), so The Frontend will be accessible on the same host as API
Server, for example at `http://localhost:7070/web`.

The Frontend is not configured to work with CORS, so if you want it, do it on your own risk.

So my nginx server config ended up looking like this (for The Frontend and API server):

```
server
{
    listen 7070;

    #some additional configurations like error log and etc

    root /path/to/folder/server;
    index index.php index.html;

    # API Server
    location / {
        index index.php;
        try_files $uri $uri/ /index.php;

        location ~ \.php$ {
            # fast cgi configurations
        }
    }

    # The Frontend
    location /web {
        alias /vagrant/shared/www/public;
    }
    location ~ (app|components|style) {
        root /vagrant/shared/www/public;
    }
}
```

So API server available on `http://localhost:7070/` and The Frontend on `http://localhost:7070/web`.

## The Frontend

The Frontend looks really simple and understandable, I believe. You have two tabs: Playlist - here you can manage your
playlist; Search - where you perform search of tracks.

On page reload The Frontend will authorize you in API server as new user, so all your data from previous 'session' will
be lost. It made in name of less manual work with registration and login, it fakes authorization, since The Frontend
exists for testing purpose.

Please do not judge on how it looks like since it exists only for manual test purposes. You could see not beautiful CSS, etc.
I did not focused on this part of test task, spend all effort on server side. Thank You for Your understanding.

## API Server

API Server covers goal of this test task:

- Perform track/artist/album searches;
- Maintain playlists:
-- CRUD (Create, Remove, Update, Delete);
-- Add/remove tracks.

API Server contain public API and private that requires authentication first.

You can use [cURL](http://curl.haxx.se/) to test API server.

API response in JSON format.

Supported request types:

* `application/json`

If you will asked for another type, server will response with code `406`.

### Version

You can configure version of your API at `config\config.php`.

Make sure that your requests contain correct version of API.

Default version is set to `1`.

### Public API

#### Users

##### POST /api/v1/users/authentication

You can get authentication token for your communication with server's private API by querying following URL:

```
$ curl -i -H Accept:application/json -X POST http://localhost:7070/api/v1/users/authentication
```

You will get response status `201` - because this method will fake user creation and provide you with auth token for private API.

You should receive object with authentication token:

```
{
    "token": "TOKEN"
}
```

Be aware that this method brakes rule of idempotent since on each request it will generate new outcome. This
method made only for test purposes. On real application I will implement normal user authentication.

### Private API

For this part of API you need to have authentication toking provided by `/api/v1/users/authentication`. You should pass
token in header with key `token`.

#### Playlist

##### GET /api/v1/playlist

You can get list of all playlist available for current authorized user:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/playlist -H token:{token} -d offset={offset.?}
```

Variable `offset` is optional for content pagination, by default is set to 0 and each page returns 20 rows of content.
Variable `offset` should be positive integer equal or greater then 0, otherwise you would get `404` error. Response will
contain additional block with key `info` that contains information for pagination. Limitation made for not overflow
response header size.

You should receive object with pagination info and playlist:

```
{
    "playlist": [
        {
            "id": "1",
            "user_id": "1",
            "name": "My First Playlist"
        }
    ],
    "info": {
        "num_results": 1,
        "limit": 20,
        "offset":0
    }
}
```

* If everything is OK you should get response code `200` with list of available playlist;
* Server will response with code `404` if optional variable `offset` specified and it not positive integer.

##### GET /api/v1/playlist/{playlistId}

You can get information about specific playlist for current authorized user:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/playlist/{playlistId} -H token:{token}
```

You should receive object with playlist:

```
{
    "playlist": {
        "id": "1",
        "user_id": "1",
        "name": "My First Playlist"
    }
}
```

* If everything is OK you should get response code `200` with playlist;
* If playlist not existing for authorized user server will response with code `404`.

##### POST /api/v1/playlist

You can create new playlist for current authorized user:

```
$ curl -i -H Accept:application/json http://localhost:7070/api/v1/playlist -H token:{token} -d "name={newPlaylistName}"
```

You should receive object with new playlist:

```
{
    "playlist": {
        "id": "2",
        "user_id": "1",
        "name": "My Second Playlist"
    }
}
```

* If everything is OK you should get response code `201` with playlist;
* If you missed to post `name` variable server will response with code `400`;
* If you missed playlist with such `name` already exists server will response with code `400`.

##### PUT /api/v1/playlist/{playlistId}

You can update playlist name for current authorized user:

```
$ curl -i -H Accept:application/json -X PUT http://localhost:7070/api/v1/playlist/{playlistId} -H token:{token} -d "newName={newPlaylistName}"
```

* If everything is OK you should get response code `204`;
* If you missed to put `newName` variable server will response with code `400`;
* If you missed playlist with such `newName` already exists server will response with code `400`;
* If you tried to update not yours playlist (not existing for authorized user) server will response with code `404`.

##### DELETE /api/v1/playlist/{playlistId}

You can delete playlist for current authorized user:

```
$ curl -i -H Accept:application/json -X DELETE -G http://localhost:7070/api/v1/playlist/{playlistId} -H token:{token}
```

* If everything is OK you should get response code `204`;
* Duplication of response will rise `404`, I know it is Holly War about idempotent in HTTP and DELETE;
* If you tried to delete not yours playlist (not existing for authorized user) server will response with code `404`.

##### GET /api/v1/playlist/{playlistId}/songs

You can get list of all songs assigned to specified playlist for current authorized user:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/playlist/{playlistId}/songs -H token:{token} -d offset={offset.?}
```

Variable `offset` is optional for content pagination, by default is set to 0 and each page returns 20 rows of content.
Variable `offset` should be positive integer equal or greater then 0, otherwise you would get `404` error. Response will
contain additional block with key `info` that contains information for pagination. Limitation made for not overflow
response header size.

You should receive object with pagination info and songs list:

```
{
    "songs": [
        {
            "id": "1",
            "user_id": "1",
            "playlist_id": "1",
            "name": "Gimme Shelter",
            "album": "Let It Bleed",
            "artist": "The Rolling Stones"
        }
    ],
    "info": {
        "num_results": 1,
        "limit": 20,
        "offset":0
    }
}
```

* If everything is OK you should get response code `200` with list of available songs;
* If you tried to get songs from playlist that is not yours (not existing for authorized user) server will response with code `404`;
* Server will response with code `404` if optional variable `offset` specified and it not positive integer.

##### GET /api/v1/playlist/{playlistId}/songs/{songId}

You can get list of all songs assigned to specified playlist for current authorized user:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/playlist/{playlistId}/songs/{songId} -H token:{token}
```

You should receive object with song:

```
{
    "song": {
        "id": "1",
        "user_id": "1",
        "playlist_id": "1",
        "name": "Gimme Shelter",
        "album": "Let It Bleed",
        "artist": "The Rolling Stones"
    }
}
```

* If everything is OK you should get response code `200` with list of available songs;
* If you tried to get song from playlist that is not yours (not existing for authorized user) server will response with code `404`;
* If song is not exist server will response with code `404`.

##### PUT /api/v1/playlist/{playlistId}/songs

You can add song to the existing playlist of current authorized user:

```
$ curl -i -H Accept:application/json -X PUT http://localhost:7070/api/v1/playlist/{playlistId}/songs -H token:{token} -d "track={track}&artist={artist}&album={album}"
```

You should receive object with just added song:

```
{
    "song": {
        "id": "2",
        "user_id": "1",
        "playlist_id": "1",
        "name": "Get Off My Cloud",
        "album": "December's Children (And Everybody's)",
        "artist": "The Rolling Stones"
    }
}
```

* If everything is OK you should get response code `204`;
* If you tried to add song to not yours playlist (not existing for authorized user) server will response with code `404`.

##### DELETE /api/v1/playlist/{playlistId}/songs/{songId}

You can remove song from the existing playlist of current authorized user:

```
$ curl -i -H Accept:application/json -X DELETE -G http://localhost:7070/api/v1/playlist/{playlistId}/songs/{songId} -H token:{token}
```

* If everything is OK you should get response code `204`;
* Duplication of response will rise `404`, I know it is Holly War about idempotent in HTTP and DELETE;
* If you tried to delete song from not yours playlist (not existing for authorized user) server will response with code `404`.

#### Search

You can perform search in Spotify library. You can search by artist, album or track. Current API server provide API to
do search using Spotify's search patterns (field filters). More information about search field filters possibility you
can read at official [Spotify Documentation](https://developer.spotify.com/web-api/search-item/).

For search it [Spotify Web API](https://developer.spotify.com/web-api/search-item/), since mentioned in the task
[Spotify Metadata API](http://developer.spotify.com/en/metadata-api/overview/) is deprecated.

##### GET /api/v1/search/track

You can search by track:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/search/track?q={searchQuery} -H token:{token} -d offset={offset.?}
```

You should receive simplified version of response from Spotify search API, object will have pagination info and results list:

```
{
    "result": [
        {
            "name": "Gimme Shelter",
            "album": {
                "name": "Let It Bleed"
            },
            "artists": [
                {
                    "name": "The Rolling Stones"
                }
            ]
        }
    ],
    "info": {
        "num_results": 1,
        "limit": 20,
        "offset":0
    }
}
```

Variable `offset` is optional for content pagination, by default is set to 0 and each page returns 20 rows of content.
Variable `offset` should be positive integer equal or greater then 0, otherwise you would get `404` error. Response will
contain additional block with key `info` that contains information for pagination. Limitation made for not overflow
response header size.

* If everything is OK you should get response code `200` with list of search results;
* Server will response with code `404` if variable `q` not specified;
* Server will response with code `404` if optional variable `offset` specified and it not positive integer.

##### GET /api/v1/search/album

You can search by album:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/search/album?q={searchQuery} -H token:{token} -d offset={offset.?}
```

Variable `offset` is optional for content pagination, by default is set to 0 and each page returns 20 rows of content.
Variable `offset` should be positive integer equal or greater then 0, otherwise you would get `404` error. Response will
contain additional block with key `info` that contains information for pagination. Limitation made for not overflow
response header size.

You should receive simplified version of response from Spotify search API, object will have pagination info and results list:

```
{
    "result": [
        {
            "name": "Let It Bleed"
        },
        {
            "name": "Kill Rock 'N' Roll (Let It Bleed)"
        },
        {
            "name": "Karaoke Masterclass Presents - Let It Bleed The Rolling Stones Karaoke Tribute"
        }
    ],
    "info": {
        "num_results": 3,
        "limit": 20,
        "offset":0
    }
}
```

* If everything is OK you should get response code `200` with list of search results;
* Server will response with code `404` if variable `q` not specified;
* Server will response with code `404` if optional variable `offset` specified and it not positive integer.

##### GET /api/v1/search/artist

You can search by artist:

```
$ curl -i -H Accept:application/json -X GET -G http://localhost:7070/api/v1/search/artist?q={searchQuery} -H token:{token} -d offset={offset.?}
```

Variable `offset` is optional for content pagination, by default is set to 0 and each page returns 20 rows of content.
Variable `offset` should be positive integer equal or greater then 0, otherwise you would get `404` error. Response will
contain additional block with key `info` that contains information for pagination. Limitation made for not overflow
response header size.

You should receive simplified version of response from Spotify search API, object will have pagination info and results list:

```
{
    "result": [
        {
            "name": "The Rolling Stones"
        },
        {
            "name": "The Rolling Stones Tribute Band"
        },
        {
            "name": "Rhythms Del Mundo feat. The Rolling Stones"
        }
    ],
    "info": {
        "num_results": 3,
        "limit": 20,
        "offset":0
    }
}
```

* If everything is OK you should get response code `200` with list of search results;
* Server will response with code `404` if variable `q` not specified;
* Server will response with code `404` if optional variable `offset` specified and it not positive integer.

### Exceptions

* For `Private API` if parameter `token` is missing you will get response with code `401`;
* For `Private API` if parameter `token` is wrong, there are no authorized user in the system with provided token, you
will get response with code `401`;
* For methods that are not in API, you will get response with code `404`;
* For all uncovered exceptions you will get response with code `500`.

## Copyright

Copyright (C) 2014 Pavlo Voznenko.