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
        ["id" => 1, "nome" => "leandro", "sobrenome" => "da silva", "login" => "leandro@gmail.com", "senha" => "lele02"],
        ["id" => 2, "nome" => "rodrigo", "sobrenome" => "silveira","login" => "rodrigo@gmail.com",  "senha" => "rodrigo13"],
        ["id" => 3, "nome" => "sophia", "sobrenome" => "moreira", "login" => "sophia@gmail.com",  "senha" => "sosolinda"],
        ["id" => 4, "nome" => "amabili", "sobrenome" => "dos santos", "login" => "amabili@gmail.com",  "senha" => "luz04"],
        ["id" => 5, "nome" => "luan", "sobrenome" => "pereira", "login" => "luan@gmail.com",  "senha" => "binho123"],
 
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
 