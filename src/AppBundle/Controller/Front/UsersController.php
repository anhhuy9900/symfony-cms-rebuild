<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Handle\UserHandleAction;

class UsersController extends BaseController
{
    /**
     * @Route("/user/login", name="user_login_page")
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

        $defaultData = array();
        $form = $this->createFormBuilder($defaultData, array('csrf_protection' => true))
          //->setAction($this->generateUrl('login_form_submit'))
          ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
              'data' => $this->get('security.csrf.token_manager')->refreshToken('user-login')
          ))
          ->add('account', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
          ->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
          ->add('user_remember', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class , array(
            'label'    => '',
            'required' => false,
          ))
          //->add('send', SubmitType::class)
          ->getForm();

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
        $this->data['title'] = 'Login Page';

        return $this->render('@frontend/users/login.html.twig', $this->data);

    }

    /**
     * @Route("/user/register", name="user_register_page")
     */
    public function registerAction(Request $request)
    {
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
          //->setAction($this->generateUrl('login_form_submit'))
          ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
              'data' => $this->get('security.csrf.token_manager')->refreshToken('user-register')
          ))
          ->add('fullname', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
          ->add('email', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
          ->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
          ->add('confirm_password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
          ->add('phone', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
          ->add('address', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class)
          ->add('gender', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
            'choices'  => array(
              1 => 'Male',
              2 => 'Female',
            ),
          ))
          //->add('send', SubmitType::class)
          ->getForm();

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
     * @Route("/user/forgot_password", name="user_forgot_password_page")
     */
    public function forgot_passwordAction(Request $request)
    {
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            //->setAction($this->generateUrl('forgot_password_form_submit'))
            ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
                'data' => $this->get('security.csrf.token_manager')->refreshToken('user-forgot-password')
            ))
            ->add('email', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            //->add('send', SubmitType::class)
            ->getForm();

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
     * @Route("/user/logout", name="user_logout_page")
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

        if(!$this->global_service->checkValidCsrfToken($action, $json_data->data->csrf_token)){
            $response_json = array(
                'status' => 0,
                'msg' => 'Invalid Token'
            );
            return new JsonResponse($response_json);
        }

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