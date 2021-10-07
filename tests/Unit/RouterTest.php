<?php
namespace Terrazza\Tests\Routing\Unit;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Terrazza\Http\Request\HttpRequest;
use Terrazza\Http\Response\HttpResponse;
use Terrazza\Injector\Injector;
use Terrazza\Log\ILogger;
use Terrazza\Log\Logger;
use Terrazza\Component\Routing\IRouteClassMethodBuilder;
use Terrazza\Component\Routing\Route;
use Terrazza\Component\Routing\RouteBuilder\RouteClassBuilder;
use Terrazza\Component\Routing\RouteBuilder\RouteClassMethodAnnotationBuilder;
use Terrazza\Component\Routing\RouteCollection;
use Terrazza\Component\Routing\Router;

class RouterTest extends TestCase {

    private function get_routes_config() : array {
        return [
            new Route("/ad/{id}", RouterTestClassController::class),
            new Route("/target", RouterTestClassController2::class),
        ];
    }

    function testRouterDirectly() {
        $request                                    = new HttpRequest("GET", "https://www.google.com/ad/131212/targets");
        $request->withQueryParams(["question" => "yes"]);
        //
        $router                                     = new Router(
            new RouteClassBuilder(
                new RouteCollection(...array_values($this->get_routes_config())),
                new RouteClassMethodAnnotationBuilder()
            ),
            new Injector("", new Logger()),
            new Logger()
        );
        $router->process($request);
        $this->assertTrue(true);
    }

    private function get_di_config() : array {
        return [
            RouteCollection::class                  => $this->get_routes_config(),
            IRouteClassMethodBuilder::class         => RouteClassMethodAnnotationBuilder::class,
            ILogger::class                          => Logger::class
        ];
    }

    /*function testRouterWithInjector() {
        $request                                    = new HttpRequest("GET", "https://www.google.com/ad/131212/targets");
        $request->withQueryParams(["question" => "yes"]);

        $router                                     = (new Injector(
            $this->get_di_config()
        ))->get(Router::class);
        $router->process($request);
        $this->assertTrue(true);
    }*/
}
class RouterTestResponseObject implements JsonSerializable {
    public int $publicInt = 12;
    protected int $protectedInt = 14;
    public function jsonSerialize() {
        //return get_object_vars($this);
        return $this;
    }
}
class RouterTestClassController {
    /**
     * @Route:uri /targets
     * @Route:method get
     */
    public function getAd(int $id, string $question) : ResponseInterface {
        return new HttpResponse(200);
    }

    /**
     * @Route:uri /
     * @Route:method get
     */
    public function getEmpty() : ResponseInterface {
        return (new HttpResponse)::createEmptyResponse(200);
        //return (new HttpResponse)::createJsonResponse(200, ["publicInt" => 12]);
        //return (new HttpResponse)::createJsonResponse(200, new RouterTestResponseObject());
        //return (new HttpResponse)::createJsonResponse(200, null);
    }

    /**
     * @Route:uri /
     * @Route:method post
     */
    public function postAd() : ResponseInterface {
        return new HttpResponse(200);
    }

    /**
     * @Route:uri /
     * @Route:method put
     */
    public function putAd() : ResponseInterface {
        return new HttpResponse(201);
    }
}
class RouterTestClassController2 {}