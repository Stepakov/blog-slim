<?php

use Blog\PostMapper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

$config = require "config/database.php";
$dns = $config[ 'dns' ];
$username = $config[ 'username' ];
$password = $config[ 'password' ];

try {
    $connection = new PDO( $dns, $username, $password, [
        PDO::ATTR_PERSISTENT => true
    ] );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $connection->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
}
catch( \Exception $e )
{
    echo 'Database error: ' . $e->getMessage();
    exit;
}

$postMapper = new PostMapper( $connection );

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ( $twig ) {
    $result = $twig->render( 'index.twig' );
//    var_dump( $result ); exit;
    $response->getBody()->write( $result );
    return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) use ( $twig ) {
    $result = $twig->render( 'about.twig', [
        'name' => 'Sasha'
    ] );

    $response->getBody()->write( $result );

    return $response;
});

$app->get('/post/{url_key}', function (Request $request, Response $response, $args) use ( $twig, $postMapper ) {

    $post = $postMapper->getByUrlKey( $args[ 'url_key' ] );

    if( empty( $post ) )
    {
        $result = $twig->render( 'not-found.twig' );
    }
    else
    {
        $result = $twig->render( 'post.twig', [
            'post' => $post
        ] );
    }

    $response->getBody()->write( $result );

    return $response;
});

$app->run();