<?php
namespace AppBundle\Controller\Front;

use AppBundle\AppBundle;
use AppBundle\Controller\FrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Handle\UserHandleAction;

class UserController extends FrontController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        /*
         * Check session user exists yet
         */
        if($this->global_service->sessionCurrentUser()){
            $url = $this->generateUrl('home_page');
            return $this->redirect($url, 301);
            exit();
        }

        $form = $this->createForm(\AppBundle\Form\Front\UserLogin::class);
        $form->handleRequest($request);
        $this->data['form'] = $form->createView();

        $add_scripts = $this->global_helper_service->systemAddJs(array(
            array(
                //'path' => $this->container->get('templating.helper.assets')->getUrl('@AppBundle/Resources/public/frontend/js/user/user_page.js'),
                'path' => '',
                'version' => '',
                'footer' => true,
            )
        ));
        $this->data['add_scripts'] = $add_scripts;
        $this->data['title'] = 'Login Page';

        return $this->render('@frontend/users/login.html.twig', $this->data);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(\AppBundle\Form\Front\UserRegister::class);
        $form->handleRequest($request);

        $this->data['form'] = $form->createView();
        $add_scripts = $this->global_helper_service->systemAddJs(array(
          array(
            'path' => $this->container->get('templating.helper.assets')->getUrl('themes/frontend/assets/js/user/user_page.js'),
            'version' => '',
            'footer' => true,
          )
        ));
        $this->data['add_scripts'] = $add_scripts;
        $this->data['title'] = 'Register Page';
        return $this->render('@frontend/users/register.html.twig', $this->data);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm(\AppBundle\Form\Front\UserForgotPassword::class);
        $form->handleRequest($request);

        $this->data['form'] = $form->createView();
        $add_scripts = $this->global_helper_service->systemAddJs(array(
            array(
                'path' => $this->container->get('templating.helper.assets')->getUrl('themes/frontend/assets/js/user/user_page.js'),
                'version' => '',
                'footer' => true,
            )
        ));
        $this->data['add_scripts'] = $add_scripts;
        $this->data['title'] = 'Forgot Password Page';

        return $this->render('@frontend/users/forgot_password.html.twig', $this->data);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction(Request $request){

        //remove session of user
        $session = $request->getSession();
        if($this->global_service->sessionCurrentUser()){
            $session->remove('user');
        }

        $url = $this->generateUrl('home_page');
        return $this->redirect($url, 301);
    }

    /**
     * @Route("/user/api", name="user_api_page")
     * @Method({"POST","HEAD"}))
     */
    public function user_apiAction(Request $request)
    {

        $json_data = $request->get('json_data');
        if(!$json_data){
            $response_json = array(
                'status' => 0,
                'msg' => 'API not found'
            );
            return new JsonResponse($response_json);
        }

        $json_data = json_decode($json_data);
        $response_data = array();
        $action = $json_data->action;

//        if(!$this->global_service->checkValidCsrfToken($action, $json_data->data->csrf_token)){
//            $response_json = array(
//                'status' => 0,
//                'msg' => 'Invalid Token'
//            );
//            return new JsonResponse($response_json);
//        }

        $handle_action = new UserHandleAction;
        switch($action){
            case 'user-register':
                $response_data = $handle_action->userRegister($json_data->data);
                break;
            case 'user-login':
                $response_data = $handle_action->userLogin($json_data->data);
                break;
            case 'user-forgot-password':
                $response_data = $handle_action->userForgotPassword($json_data->data);
                break;
            default:
                break;
        }

        return new JsonResponse($response_data);

    }
}