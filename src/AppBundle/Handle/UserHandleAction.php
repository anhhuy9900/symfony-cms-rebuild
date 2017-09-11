<?php
namespace AppBundle\Handle;

use AppBundle\Controller\Front\UserController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\HttpFoundation\Response;

class UserHandleAction extends UserController
{

    /*
     * This function handle action for feature user register
     */
    public function userRegister($data)
    {
        global $kernel;

        $status = 1;
        $data = (object)$data;
        $validation = new \AppBundle\Validation\Front\Users\UserRegisterValidation;
        $validation->fullname = $data->fullname;
        $validation->email = $data->email;
        $validation->password = $data->password;
        $validation->confirm_password = $data->confirm_password;
        $validation->phone = (int)$data->phone;
        $validation->gender = (int)$data->gender;

        $errors = $kernel->getContainer()->get('validator')->validate($validation);
        $error_message = $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($errors);

        if($error_message) {
            $status = 0;
        } else {
            //User Register Valid
            $check_success = $kernel->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:UsersEntity')->user_data_insert($data);
            if(!$check_success){
                $status = 0;
                $error_message = 'Register fail. Please try again';
            } else {
                $error_message = 'Register successfully';
            }
        }

        $response_data = array(
            'status' => $status,
            'msg' => $error_message
        );

        return $response_data;
    }

    /*
     * This function handle action for feature user login
     */
    public function userLogin($data)
    {
        global $kernel;
        $helper = $kernel->getContainer()->get('app.global_helper_service');

        $data = (object)$data;
        $validation = new \AppBundle\Validation\Front\Users\UserLoginValidation;
        $validation->account = $helper->cleanDataInput($data->account);
        $validation->password = $data->password ? $helper->encodePassword('UserPass', $data->password) : '';

        $errors = $kernel->getContainer()->get('validator')->validate($validation);
        $error_message = $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($errors);

        /*
         * if status = 1 => Valid
         * if status = 0 => Invalid
         */
        $status = 0;
        if(!$error_message){
            $status = 1;
            $error_message = 'Success';

            //save session for user
            $this->saveSessionUser($data);

        }

        $response_data = array(
            'status' => $status,
            'msg' => $error_message
        );

        return $response_data;
    }

    /*
     * This function use to save session at user login
     */
    function saveSessionUser($data){
        global $kernel;
        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();

        $user_data = $kernel->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:UsersEntity')->get_current_user($data);

        $user = new \stdClass();
        $user->uid = $user_data->getID();
        $user->fullname = $user_data->getFullname();

        $session->set('user', serialize($user));
        $session->save();

        //set cookie for user remmeber
        if($data->user_remember){
            $response = new Response();
            $cookie = new Cookie('user_remember', 1, time()+86400);
            $response->headers->setCookie($cookie);
            $response->sendHeaders();
        }
    }

    /*
     * This function handle action for feature forgot password
     */
    function userForgotPassword($data){
        global $kernel;
        $helper = $kernel->getContainer()->get('app.global_helper_service');

        $data = (object)$data;
        $validation = new \AppBundle\Validation\Front\Users\UserForgotPasswordValidation;
        $validation->email = $helper->cleanDataInput($data->email);

        $errors = $kernel->getContainer()->get('validator')->validate($validation);
        $error_message = $kernel->getContainer()->get('app.global_helper_service')->getErrorMessages($errors);

        /*
         * if status = 1 => Valid
         * if status = 0 => Invalid
         */
        $status = 0;
        if(!$error_message){
            $status = 1;
            $error_message = 'Success';

            $user_data = $kernel->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:UsersEntity')->get_current_user_by_email($data->email);

            $mail_helper = $kernel->getContainer()->get('app.mail_helper');

            $body = $kernel->getContainer()->get('templating')->render(
                '@frontend/edm/forgot_password.html.twig',
                array('name' => $user_data->getFullname())
            );
            $mail_helper->send_mail('Hello Email', 'nhahuy1990@gmail.com', $body);

        }

        $response_data = array(
            'status' => $status,
            'msg' => $error_message
        );

        return $response_data;
    }


}