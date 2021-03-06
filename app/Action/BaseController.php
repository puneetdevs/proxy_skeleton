<?php

namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;
use Slim\Exception\NotFoundException;
use Slim\Router;

abstract class BaseController
{
    /**
     * Slim application container
     *
     * @var ContainerInterface
     */
    protected $container;
    protected $data = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

 



    /**
     * Stop the script and print info about a variable
     *
     * @param mixed $data
     */
    public function debug($data)
    {
        die('<pre>' . print_r($data, true) . '</pre>');
    }

    /**
     * Get request params
     *
     * @param Request $request
     * @param string[] $params
     * @return array
     */
    public function params(Request $request, array $params)
    {
        $data = [];
        foreach ($params as $param) {
            $data[$param] = $request->getParam($param);
        }

        return $data;
    }

    /**
     * Redirect to route
     *
     * @param Response $response
     * @param string $route
     * @param array $params
     * @return Response
     */
    public function redirect(Response $response, $route, array $params = [])
    {
        return $response->withRedirect($this->router->pathFor($route, $params));
    }

    /**
     * Redirect to url
     *
     * @param Response $response
     * @param string $url
     *
     * @return Response
     */
    public function redirectTo(Response $response, $url)
    {
        return $response->withRedirect($url);
    }

    /**
     * Return "200 Ok" response with JSON data
     *
     * @param Response $response
     * @param mixed $data
     * @return int
     */
    public function ok(Response $response, $data, $status)
    {
        return $this->json($response, $data, $status);
    }

    /**
     * Return "201 Created" response with location header
     *
     * @param Response $response
     * @param string $route
     * @param array $params
     * @return Response
     */
    public function created(Response $response, $route, array $params = [])
    {
        return $this->redirect($response, $route, $params)->withStatus(201);
    }

   

    /**
     * Return "204 No Content" response
     *
     * @param Response $response
     * @return Response
     */
    public function noContent(Response $response)
    {
        return $response->withStatus(204);
    }

    /**
     * Return validation errors as a JSON array
     *
     * @param Response $response
     * @return int
     */
    public function validationErrors(Response $response)
    {
        return $this->json($response, $this->validator->getErrors(), 400);
    }

   
    /**
     * Write JSON in the response body
     *
     * @param Response $response
     * @param mixed $data
     * @param int $status
     * @return int
     */
    public function json(Response $response, $data, $status = 200)
    {
        return $response->withJson($data, $status);
    }

    /**
     * Write text in the response body
     *
     * @param Response $response
     * @param string $data
     * @param int $status
     * @return int
     */
    public function write(Response $response, $data, $status = 200)
    {
        return $response->withStatus($status)->getBody()->write($data);
    }


    public function __get($property)
    {
        return $this->container->get($property);
    }
}
