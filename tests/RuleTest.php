<?php

namespace Slim\HttpBasicAuth\Tests;

use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\HttpBasicAuth\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Request object
     *
     * @var \Slim\Http\Request
     */
    protected $request;

    /**
     * Response object
     *
     * @var \Slim\Http\Response
     */
    protected $response;

    /**
     * Run before each test
     */
    public function setUp()
    {
        $uri            = Uri::createFromString( 'https://www.example.com/admin' );
        $headers        = new Headers();
        $cookies        = [ ];
        $env            = Environment::mock();
        $serverParams   = $env->all();
        $body           = new Body( fopen( 'php://temp', 'r+' ) );
        $this->request  = new Request( 'GET', $uri, $headers, $cookies, $serverParams, $body );
        $this->response = new Response;
    }

    public function testValidAuth()
    {
        $request = $this->request
            ->withMethod( 'GET' )
            ->withHeader( 'PHP_AUTH_USER', 'admin' )
            ->withHeader( 'PHP_AUTH_PW', 'admin' );

        $response    = $this->response;
        $next        = function ( $req, $res ) {
            return $res;
        };
        $mw          = new Rule( 'admin', 'admin', '/admin' );
        $newResponse = $mw( $request, $response, $next );

        $this->assertEquals( 200, $newResponse->getStatusCode() );
    }

    public function testInValidAuth()
    {
        $request = $this->request
            ->withMethod( 'GET' )
            ->withHeader( 'PHP_AUTH_USER', 'admin' )
            ->withHeader( 'PHP_AUTH_PW', 'invalid' );

        $response    = $this->response;
        $next        = function ( $req, $res ) {
            return $res;
        };
        $mw          = new Rule( 'admin', 'admin', '/admin' );
        $newResponse = $mw( $request, $response, $next );

        $this->assertEquals( 401, $newResponse->getStatusCode() );
    }

    public function testMissingPassword()
    {
        $request = $this->request
            ->withMethod( 'GET' )
            ->withHeader( 'PHP_AUTH_USER', 'admin' )
            ->withHeader( 'PHP_AUTH_PW', 'admin' );

        try {
            $response    = $this->response;
            $next        = function ( $req, $res ) {
                return $res;
            };
            $mw          = new Rule( 'admin', '', '/secured-page' );
            $newResponse = $mw( $request, $response, $next );

        } catch ( \Exception $e ) {
        }

        $this->assertInstanceOf( 'RuntimeException', $e );
    }

}