<?php
namespace Slim\HttpBasicAuth;

use RuntimeException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * HTTP Basic Authentication
 *
 * Use this middleware with your Slim Framework application
 * to require HTTP basic auth for all routes.
 *
 * @package Slim\HttpBasicAuth
 * @author     Julio Napuri <julionc@gmail.com>
 */
class Rule implements ServiceProviderInterface
{

    /**
     * @param $username
     * @param $password
     * @param string $route
     * @param string $realm
     */
    public function __construct( $username, $password, $route = '', $realm = 'Protected Area' )
    {
        $this->username = $username;
        $this->password = $password;
        $this->route    = $route;
        $this->realm    = $realm;
    }

    /**
     * Register service provider
     *
     * @param \Pimple\Container $container
     */
    public function register( Container $container )
    {
        $container['basic_auth'] = $this;
    }

    /**
     * Invoke middleware
     *
     * @param  RequestInterface $request PSR7 request object
     * @param  ResponseInterface $response PSR7 response object
     * @param  callable $next Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke( RequestInterface $request, ResponseInterface $response, callable $next )
    {
        $uri = $request->getUri();

        if (false !== strpos( $uri->getPath(), $this->route )) {

            $authUser = $request->getHeader( 'PHP_AUTH_USER' ) ?: null;
            $authPass = $request->getHeader( 'PHP_AUTH_PW' ) ?: null;

            if ($authUser && $authPass && $authUser === $this->username && $authPass === $this->password) {
                return $next( $request, $response );
            } else {
                return $response
                    ->withStatus( 401 )
                    ->withHeader( 'WWW-Authenticate', sprintf( 'Basic realm="%s"', $this->realm ) );
            }
        }

        return $next( $request, $response );
    }

}