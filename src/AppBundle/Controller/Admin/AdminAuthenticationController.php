<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

/* import Bundle Custom */
use AppBundle\Validation\Admin\AuthenticationValidation;

class AdminAuthenticationController extends Controller
{
    private $admincp_service;
    private $global_helper_service;

    /**
     * Used as constructor
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->admincp_service = $this->container->get('app.admincp_service');
        $this->global_helper_service = $this->container->get('app.global_helper_service');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function loginAction(Request $request)
    {
        if($this->admincp_service->adminUserSessionLogin()){
            $url = $this->generateUrl('admincp_page');
            return $this->redirect($url, 301);
        }

        $defaultData = array('message' => 'Type your message here');
        $form = $this->createForm(\AppBundle\Form\Admin\AuthenticateLoginForm::class);
        $form->handleRequest($request);

        $form_errors = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $validation = new AuthenticationValidation();
            $validation->username = $data['username'];
            $validation->password = $data['password'];

            $errors = $this->get('validator')->validate($validation);
            $form_errors = $this->global_helper_service->getErrorMessages($errors);
            if(!$form_errors){
                $user = $this->admincp_service->adminCheckValidUser($data['username'], $data['password']);
                $this->admincp_service->adminSetAuthentication($user, $data['remember']);

                $url = $this->generateUrl('admincp_page');
                return $this->redirect($url, 301);
            }
        }

        $data = array(
            'form' => $form->createView(),
            'form_errors' => $form_errors
        );
        return $this->render('@admin/login.html.twig', $data);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        if(!empty($session->get('_security_secured_userad'))){
            $session->remove('_security_secured_userad');
        }
        $url = $this->generateUrl('admincp_login_page');
        return $this->redirect($url, 301);
    }

}
