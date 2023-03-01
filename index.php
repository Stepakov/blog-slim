<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

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

$app->get('/post/{url_key}', function (Request $request, Response $response, $args) use ( $twig ) {
    $result = $twig->render( 'post.twig', [
        'urlKey' => $args[ 'url_key' ]
    ] );
    $response->getBody()->write( $result );
    return $response;
});

$app->run();