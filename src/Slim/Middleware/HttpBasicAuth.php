<?php
namespace Slim\Middleware;

/**
 * HTTP Basic Authentication
 *
 * Use this middleware with your Slim Framework application
 * to require HTTP basic auth for all routes.
 *
 * @package    Slim
 * @author     Julio Napuri <julionc@gmail.com>
 */
class HttpBasicAuth extends \Slim\Middleware
{
    /**
     * @var string
     */
    protected $realm;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $route;

    /**
     * Constructor
     *
     * @param   string $username The HTTP Authentication username
     * @param   string $password The HTTP Authentication password
     * @param   string $realm The HTTP Authentication realm
     */
    public function __construct($username, $password, $realm = 'Protected Area', $route = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->realm = $realm;
        $this->route = $route;
    }

    /**
     * Call
     *
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     */
    public function call()
    {
        $request = $this->app->request;

        if (false !== strpos($request->getPath(), $this->route)) {

            $req = $this->app->request();
            $res = $this->app->response();
            $authUser = $req->headers('PHP_AUTH_USER');
            $authPass = $req->headers('PHP_AUTH_PW');

            if ($authUser && $authPass && $authUser === $this->username && $authPass === $this->password) {
                $this->next->call();
            } else {
                $res->status(401);
                $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
            }

            return;
        }
        $this->next->call();


    }
}