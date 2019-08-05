<?php

namespace Xusifob\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Xusifob\Controller\Controller;
use Xusifob\Router;
use Xusifob\Services\TinderApiService;
use Xusifob\Services\TinderSecurity;

/**
 *
 * Interface for every controller.
 *
 * Interface Controller
 * @package Xusifob\Controller
 */
class TinderController extends Controller
{


    /**
     * @var array
     */
    protected $data = array();


    /**
     * @var TinderApiService
     */
    protected $tinderService;


    /**
     * Controller constructor.
     * @param array     $data   An array of all the data you want to get
     */
    public function __construct($data)
    {
        parent::__construct($data);


        $this->tinderService = $this->getData('tinder_api');

    }


    /**
     * @param array $matches
     *
     * @return Response
     */
    public function loginAction($matches = array())
    {

        /** @var TinderSecurity $security */
        $security = $this->getData('security');

        /** @var Router $router */
        $router = $this->getData('router');

        if(isset($_POST['X-Auth-Token'])) {
            setcookie('token',$_POST['X-Auth-Token']);
            return new RedirectResponse($router->generateUrl('home'));
        }

        if($security->isLoggedIn()) {
            return new RedirectResponse($router->generateUrl('dashboard'));
        }

        return $this->loadView('login',array(
            'token' => $security->getCurrentUser()
        ));
    }

    /**
     * @param array $matches
     *
     * @return Response
     */
    public function logoutAction($matches = array())
    {

        /** @var TinderSecurity $security */
        $security = $this->getData('security');

        /** @var Router $router */
        $router = $this->getData('router');


        setcookie('token',"",time()-1000);
        return new RedirectResponse($router->generateUrl('home'));

    }


    /**
     * @param array $matches
     * @return Response
     */
    public function dashboardAction($matches = array())
    {

        return $this->loadView('dashboard',array(
            'google_api_key' => $this->getData('google_api_key'),
        ));
    }


    /**
     * @return JsonResponse
     */
    public function myProfileAction()
    {
        $informations = $this->tinderService->getMyProfile();

        return new JsonResponse($informations);

    }

    /**
     * @param array $matches
     * @return JsonResponse
     */
    public function matchesAction($matches = array())
    {

        $matches = $this->tinderService->getMatchs();

        return new JsonResponse($matches);
    }

    /**
     * @param array $matches
     * @return JsonResponse
     */
    public function goldsAction($matches = array())
    {
        $matches = $this->tinderService->getMyTinderGold();

        return new JsonResponse($matches);
    }


    /**
     *
     * @param $matches
     *
     * @return JsonResponse
     */
    public function tinderActionAction($matches)
    {

        if(!isset($_POST['action'])) {
            return new JsonResponse("parameter action is missing",Response::HTTP_BAD_REQUEST);
        }


        $user = $matches['id'];

        $s_number = $_POST['s_number'];
        $action = $_POST['action'];

        $response = null;

        switch ($action) {
            case "unlike" :
                $response = $this->tinderService->pass($user,$s_number);
                break;
            case "like" :
                $response = $this->tinderService->like($user,$s_number);
                break;
            case "superlike" :
                $response = $this->tinderService->superLike($user,$s_number);
                break;
        }

        return new JsonResponse($response);

    }


    /**
     * @param array $matches
     * @return JsonResponse
     */
    public function updateProfile($matches = array())
    {

        $lat = $_POST['pos']['lat'];
        $lon = $_POST['pos']['lon'];

        $result = $this->tinderService->updateLocation($lat,$lon);

        return new JsonResponse($result);

    }

    /**
     * @param $template
     * @param array $data
     * @return Response
     */
    protected function loadView($template,$data = array())
    {
        $base = (__DIR__ . "/../Ressources/views/base.php");
        $javascript = (__DIR__ . "/../Ressources/js/$template.js");
        $template = (__DIR__ . "/../Ressources/views/$template.php");

        extract($data);

        if(!file_exists($template)) {
            throw  new FileNotFoundException($template);
        }

        ob_start();
        include $base;
        $base = ob_get_clean();

        ob_start();
        include $template;
        $template = ob_get_clean();

        if(file_exists($javascript)) {

            ob_start();
            include $javascript;
            $javascript = ob_get_clean();
        } else {
            $javascript = "";
        }



        $base = str_replace('{{body}}',$template,$base);
        $base = str_replace('{{javascript}}',$javascript,$base);

        return new Response($base);

    }


}