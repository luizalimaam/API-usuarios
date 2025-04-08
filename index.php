<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
 
require __DIR__ . '/vendor/autoload.php';
 
$app = AppFactory::create();
// middleware é um evento que ocorre antes da requisição chegar na rota.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (Request $request, Throwable $excepition, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails, ) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write('{"error": "Recurso não foi encontrado"}');
    return $response->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
});
 
 
$app->get('/usuario', function (Request $request, Response $response, array $args) {
    $tarefas = [
        ["id" => 1, "nome" => "rafael", "sobrenome" => "da costa", "login" => "rafael@gmail.com", "senha" => "rafael2208"],
        ["id" => 2, "nome" => "sthella", "sobrenome" => "lima","login" => "sthella@gmail.com",  "senha" => "sthella28"],
        ["id" => 3, "nome" => "sophia", "sobrenome" => "silva", "login" => "sophia@gmail.com",  "senha" => "soso3030"],
        ["id" => 4, "nome" => "malu", "sobrenome" => "rosa", "login" => "malu@gmail.com",  "senha" => "maluuu3"],
        ["id" => 5, "nome" => "andré", "sobrenome" => "pereira", "login" => "andré@gmail.com",  "senha" => "dede508"],
 
    ];
    $response->getBody()->write(json_encode($tarefas));
    return $response->withHeader('Content-Type', 'application/json');
});
 
$app->post('/usuario', function (Request $request, Response $response, array $args) {
    $parametros = (array) $request->getParsedBody();
 
    if (!array_key_exists('nome', $parametros) || empty($parametros['nome'])) {
        $response->getBody()->write(json_encode([
            "mensagem" => "nome é obrigatório"
        ]));
   
        return $response->withHeader('Content-type', 'application/json')->withStatus(400);
    }
    if (!array_key_exists('senha', $parametros) || empty($parametros['senha'])) {
        $response->getBody()->write(json_encode([
            "mensagem" => "senha é obrigatório"
        ]));
   
        return $response->withHeader('Content-type', 'application/json')->withStatus(400);
    }
 
    return $response->withStatus(201);
});
 
$app->delete('/usuario', function (Request $request, Response $response, array $args) {
    return $response->withStatus(204);
});
 
$app->put('/usuario', function (Request $request, Response $response, array $args) {
    return $response->withStatus(201);
});
 
$app->run();
