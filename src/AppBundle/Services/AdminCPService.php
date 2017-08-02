<?php
namespace AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AdminCPService extends Controller {

    private $global_helper_service;

//    public function setContainer(ContainerInterface $container)
//    {
//        $this->container = $container;
//
//    }

    public function __construct(EntityManager $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
        $this->global_helper_service = $this->container->get('app.global_helper_service');
    }

    public function adminCheckValidUser($username, $password)
    {
        $password = $this->encodePassword('MyPass', $password);
        $repository = $this->em->getRepository('AppBundle:SystemUsersEntity');
        $result = $repository->findOneBy(
            array('username' => $username, 'password' => $password, 'status' => 1 )
        );
        if($result) {
            return $result;
        }

        return NULL;
    }

    public function adminGetUserByToken($userToken)
    {
        $repository = $this->em->getRepository('AppBundle:SystemUsersEntity');
        $result = $repository->findOneBy(
            [
                'userToken' => $userToken,
                'status' => 1
            ]
        );
        if($result) {
            return $result;
        }

        return NULL;
    }

    public function adminGetCurrentUserLogin(){
        $session = new Session(new PhpBridgeSessionStorage());
        if(!empty($session->get('userad_authentication'))) {
            $session_user = $session->get('userad_authentication');

            //get current user
            $get_user = $this->adminGetUserByToken($session_user['token']);

            return $get_user;
        }
        return NULL;
    }

    public function adminSetAuthentication($user_data, $remember) {
        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();

        $firewall = 'secured_userad';
        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));

        $user = array(
            'username' => $user_data->getUsername(),
            'token' => $user_data->getUserToken(),
            'ad_token' => $token,
        );

        $session->set('security_'.$firewall, serialize($token));
        $session->set('userad_authentication', $user);
        $session->save();

        //set cookie for remmeber me
        if($remember) {
            $response = new Response();
            $cookie = new Cookie('remember_me', 1, time()+86400);
            $response->headers->setCookie($cookie);
            $response->sendHeaders();
        }
    }

    public function adminUserSessionLogin(){
        $session = new Session(new PhpBridgeSessionStorage());
        $is_login = FALSE;

        $request = new Request;
        $cookies = $request->cookies->get('cookie');
        if($cookies) {
          if ($cookies->has('remember_me') && $cookies->get('remember_me')) {
            $is_login = TRUE;
          }
        }
        else {
            if(!empty($session->get('security_secured_userad'))) {
                $is_login = TRUE;
            }
        }
        return $is_login;
    }

    public function adminCheckValidLogin()
    {
        if($this->adminUserSessionLogin()) {
            return TRUE;
        }
        return FALSE;

    }

    public function adminUserAdminInfo()
    {
        $session = new Session();
        $user = $session->get('userad_authentication');
        return $user;
    }

    public function encodePassword($raw, $salt)
    {
        return hash('sha256', $salt . $raw); // Custom function for encrypt
    }

    public function adminListModulesLeft($parentId)
    {
        //get current url
        //dump($this->container->get("router")->getContext()->getPathInfo());die;
        //$route = $this->container->get("router")->match($this->container->getRequest()->getPathInfo());
        $route = $this->container->get("router")->getContext()->getPathInfo();

        $current_user = $this->adminGetCurrentUserLogin();
        $repository = $this->em->getRepository('AppBundle:SystemModulesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk.id, pk.moduleName, pk.moduleAlias");
        $query->where('pk.moduleStatus = 1');
        $query->andWhere('pk.parentId = :parentId')->setParameter('parentId', $parentId);
        //Just only super admin enough permission access for all modules
        if($current_user) {
          if($current_user->getPermissionLimit() == 0) {
            $query->andWhere('pk.modulePermission = 0');
          }
        }

        $query->orderBy("pk.moduleOrder", 'ASC');
        $results = $query->getQuery()->getResult();

        $html = '';
        if(!empty($results)) {
            $html .= '<ul class="submenu">';
            foreach($results as $value) {
                $html_menu = $this->adminListModulesLeft($value['id']);
                $url_redirect = $value['moduleAlias'] ? $this->generateUrl($value['moduleAlias']) : '#';
                $class_active = $route == $value['moduleAlias'] ? ' class="active leftmenu-child-active"' : '';
                $html .='<li'.$class_active.'>';
                    $html .='<a href="' .$url_redirect. '"' .($html_menu ? 'class="dropdown-toggle"' : ''). '>';
                        $html .='<i class="menu-icon fa fa-caret-right"></i>';
                        $html .= $value['moduleName'];
                    $html .='</a>';
                    $html .='<b class="arrow ' .($html_menu ? 'fa fa-angle-down' : ''). '"></b>';

                    $html .= $html_menu;

                $html .='</li>';

            }
            $html .= '</ul>';
        }

        return $html;
    }

    public function adminCheckRolesUser($module_id, $roleType)
    {
        $valid = FALSE;
        $get_user = $this->adminGetCurrentUserLogin();
        if($get_user) {
            $repository = $this->em->getRepository('AppBundle:SystemUsersEntity');
            $query = $repository->createQueryBuilder('pk');
            $query->select("fk.roleType");
            $query->leftJoin("AppBundle:SystemRolesEntity", "fk", "WITH", "pk.roleId=fk.id");
            $query->where('pk.id = :id')->setParameter('id', $get_user->getID());
            $query->andwhere('pk.status = 1');
            $result = $query->getQuery()->getArrayResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);

            $result_roleType = unserialize($result[0]['roleType']);
            if(!empty($result_roleType[$module_id])) {
                switch($roleType) {
                    case 'view':
                        if($result_roleType[$module_id][$roleType]){
                            $valid = TRUE;
                        }
                        break;
                    case 'add':
                        if($result_roleType[$module_id][$roleType]){
                            $valid = TRUE;
                        }
                        break;
                    case 'edit':
                        if($result_roleType[$module_id][$roleType]){
                            $valid = TRUE;
                        }
                        break;
                    case 'delete':
                        if($result_roleType[$module_id][$roleType]){
                            $valid = TRUE;
                        }
                        break;
                    default:
                        break;
                }
            }

            if($get_user->getPermissionLimit() == 1) {
                $valid = TRUE;
            }
        }

        return $valid;

    }

    public static function adminRandomToken($length = 16)
    {
        if (function_exists('openssl_random_pseudo_bytes'))
        {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            if ($bytes === false)
            {
                // throw exception that unable to create random token
            }

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        return NULL;
    }

    public function adminGetCurrentModule($moduleAlias)
    {
        $repository = $this->em->getRepository('AppBundle:SystemModulesEntity');
        $result = $repository->findOneBy(['moduleAlias' => $moduleAlias]);
        if($result) {
            return $result;
        }
        return NULL;
    }

    public static function handleElementFormFilter($array_filters = array())
    {
        $html = '';
        if(!empty($array_filters)){
            //$html .= '<form name="filterOptions" id="filterOptions">';
            foreach($array_filters as $key => $value) {
                $html .= '<div class="row">';
                $html .= '<div class="col-xs-6 hr4">';
                $html .= '<label for="' .$key. '" class="col-xs-3">' .$value['title']. ' : </label>';
                switch($value['type']) {
                    case 'input':
                        $html .= '<div class="input-group dataTables_filter col-xs-8">';
                        $html .= '<input type="input" id="' .$key. '" name="' .$key. '" value="' .$value['default_value']. '" class="form-control input-sm col-xs-5" placeholder="" aria-controls="dynamic-table">';
                        $html .= '</div>';
                        break;
                    case 'select':
                        $html .= '<div class="input-group col-xs-8">';
                        $html .= '<select name="' .$key. '" class="col-xs-8">';
                        foreach($value['options'] as $option_key => $option_value){
                            $selected = $value['default_value'] == $option_key ? 'selectetd="selected"' : '';
                            $html .= '<option value="' .$option_key. '" '.$selected.'>' .$option_value. '</option>';
                        }
                        $html .= '</select>';
                        $html .= '</div>';
                        break;
                    case 'date_picker':
                        $html .= '<div class="input-group col-xs-8">';
                        $html .= '<span class="input-group-addon">';
                        $html .= '<i class="fa fa-calendar bigger-110"></i>';
                        $html .= '</span>';
                        $html .= '<input class="form-control date-picker" type="text" name="' .$key. '" value="' .$value['default_value']. '" id="' .$key. '" data-type="date-picker">';
                        $html .= '</div>';
                        break;
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            //$html .= '</form>';
        }

        return  $html;
    }

}
