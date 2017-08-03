<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/* import Bundle Custom */
use AppBundle\Controller\AdminController;
use AppBundle\Validation\Admin\AdminSystemUsersValidation;
use AppBundle\Entity\SystemUsersEntity;

class AdminSystemUsersController extends AdminController
{

    /**
     * Used as constructor
     * @param ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->data['title'] = 'Manage System Users';
    }

    /**
     * @Route("/system/system-users", name="admincp_system_users_page")
     */
    public function indexAction(Request $request)
    {
        $key = $request->query->get('key') ? $this->global_helper_service->cleanStringInput($request->query->get('key')) : '';
        $arr_order = $request->query->get('order') ? $this->global_helper_service->handleParamrOderInUrl($request->query->get('order')) : array('field'=>'id', 'by'=>'DESC');
        $date_range = $request->query->get('date_range') ? $this->global_helper_service->handleParamDateRangeInUrl($request->query->get('date_range')) : array();

        $limit = $request->query->get('lm') ? (int)$request->query->get('lm') : 10;
        $page_offset = $request->query->get('p') ? (int)$request->query->get('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

    	$repository = $this->getDoctrine()->getRepository('AppBundle:SystemUsersEntity');
        $total = $repository->getTotalRecords($key);
        $results = $repository->getRecords($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);
        dump($results);die;
        if($request->query->get('report')){
            $this->_report_data($results);
        }

        $pagination = $this->global_helper_service->pagination($total, $page_offset, $limit, 3, $this->generateUrl('admincp_system_users_page'));

        $this->data['filterOptions'] = $this->filterOptions();
        $this->data['results'] = $results;
        $this->data['pagination'] = $pagination;

        return $this->render('@admin/system-users/list.html.twig', $this->data);
    }

    /**
     * @Route("/system/system-users/create", name="admincp_system_users_create_page")
     */
    public function createAction(Request $request)
    {
        $handleData = self::handleFormData($request, 0);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Created record success!');
            $url = $this->generateUrl('admincp_system_users_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        return $this->render('@admin/system-users/edit.html.twig', $this->data);

    }

    /**
     * @Route("/system/system-users/edit/{id}", name="admincp_system_users_edit_page")
     */
    public function editAction($id, Request $request)
    {
        $handleData = self::handleFormData($request, $id);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Updated record success!');
            $url = $this->generateUrl('admincp_system_users_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        return $this->render('@admin/system-users/edit.html.twig', $this->data);
    }

    /**
     * @Route("/system/system-users/delete/{id}", name="admincp_system_users_delete_page")
     */
    public function deleteAction($id , Request $request)
    {
        if($id > 0){
            $em = $this->getDoctrine()->getEntityManager();
            $check_exist_record = $em->getRepository('AppBundle:SystemUsersEntity')->find($id);
            if($check_exist_record){
                $em->getRepository('AppBundle:SystemUsersEntity')->deleteRecordDb($id);

                $request->getSession()->getFlashBag()->add('message_data', 'Deleted record success!');
            }

            $url = $this->generateUrl('admincp_system_users_page');
            return $this->redirect($url, 301);
            exit();
        }

        return $this->render();
    }

    /**
     * This function Handle create vs update data including handle and handle record in database
     */
    private function handleFormData($request, $id){
        $em = $this->getDoctrine()->getEntityManager();
        if($id > 0) {
            $entity = $em->getRepository('AppBundle:SystemUsersEntity')->find($id);
        }
        else {
            $entity = new SystemUsersEntity;
        }
        $getRolesUser = $em->getRepository('AppBundle:SystemUsersEntity')->getRolesUser();
        $roles = $this->global_helper_service->convertArrayResultSelectbox($getRolesUser, array('key'=>'id', 'value'=>'roleName'));

        $form = $this->createForm(\AppBundle\Form\Admin\SystemUsers::class, $entity, ['roles' => $roles]);
        $form->handleRequest($request);

        $form_errors = '';
        $success = FALSE;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // $validation = new AdminSystemUsersValidation();
            // $validation->username = $data['username'];
            // $validation->email = $data['email'];
            // $validation->password = $data['password'];

            $errors = $this->get('validator')->validate($entity);
            $form_errors = $this->global_helper_service->getErrorMessages($errors);
            if(!$form_errors){
                //$password = $this->admincp_service->encodePassword('MyPass', $data['password']);
                $password = 'asdasd';
                $entity->setPassword($password);
                if($entity->getID() > 0){
                    /* Update record */
                    $em->flush();
                }
                else {
                    /* Create new record */
                    $em->persist($entity);
                    $em->flush();
                }
                $success = TRUE;
            }
        }

        $handleData = array(
            'form' => $form,
            'form_errors' => $form_errors,
            'success' => $success
        );

        return $handleData;
    }

    /**
     * Report data into file excel
     */
    private function _report_data($arrData = array())
    {

        return new Response();
    }

    /*
     * This function used to render form html filter for data
     */
    private function filterOptions(){
        $request = new Request();

        $key = $request->query->get('key') ? $this->global_helper_service->cleanStringInput($request->query->get('key')) : '';
        $date_range = $request->query->get('date_range') ? $request->query->get('date_range') : '';
        $status = $request->query->get('status') != '' ? (int)$this->global_helper_service->cleanStringInput($request->query->get('status')) : '';

        $array_filters = array();

        $array_filters['key'] =  array(
            'type' => 'input',
            'title' => 'Search',
            'default_value' => $key
        );

        $array_filters['status'] =  array(
            'type' => 'select',
            'title' => 'Status',
            'options' => array(
                '' => 'Choose status',
                0 => 'UnPublish',
                1 => 'Publish',
            ),
            'default_value' => $status
        );

        $array_filters['date_range'] =  array(
            'type' => 'date_picker',
            'title' => 'Date Range',
            'options' => '',
            'default_value' => $date_range
        );

        return $this->admincp_service->handleElementFormFilter($array_filters);
    }
}
