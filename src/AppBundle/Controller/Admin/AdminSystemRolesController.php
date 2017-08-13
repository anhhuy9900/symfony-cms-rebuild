<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/* import Bundle Custom */
use AppBundle\Controller\AdminController;
use AppBundle\Validation\Admin\SystemRolesValidation;
use AppBundle\Entity\SystemRolesEntity;

class AdminSystemRolesController extends AdminController
{
    /**
     * Used as constructor
     * @param ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->data['title'] = 'Manage System Roles';
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $key = $request->query->get('key') ? $this->global_helper_service->cleanStringInput($request->query->get('key')) : '';
        $arr_order = $request->query->get('order') ? $this->global_helper_service->handleParamrOderInUrl($request->query->get('order')) : array('field'=>'id', 'by'=>'DESC');
        $date_range = $request->query->get('date_range') ? $this->global_helper_service->handleParamDateRangeInUrl($request->query->get('date_range')) : array();

        $limit = $request->query->get('lm') ? (int)$request->query->get('lm') : 10;
        $page_offset = $request->query->get('p') ? (int)$request->query->get('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

        $repository = $this->getDoctrine()->getRepository('AppBundle:SystemRolesEntity');
        $total = $repository->getTotalRecords($key);
        $results = $repository->getRecords($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);

        if($request->query->get('report')){
            $this->reportData($results);
        }

        $pagination = $this->global_helper_service->pagination($total, $page_offset, $limit, 3, $this->generateUrl('admincp_system_roles_page'));

        $this->data['filterOptions'] = $this->filterOptions();
        $this->data['results'] = $results;
        $this->data['pagination'] = $pagination;

        return $this->render('@admin/system-roles/list.html.twig', $this->data);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $handleData = self::handleFormData($request, 0);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Created record success!');
            $url = $this->generateUrl('admincp_system_roles_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        $this->data['listModules'] = $handleData['listModules'];

        return $this->render('@admin/system-roles/edit.html.twig', $this->data);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $handleData = self::handleFormData($request, $id);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Updated record success!');
            $url = $this->generateUrl('admincp_system_roles_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        $this->data['listModules'] = $handleData['listModules'];

        return $this->render('@admin/system-roles/edit.html.twig', $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:SystemRolesEntity')->find($id);
        if($entity) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('message_data', 'Deleted record success!');

            $url = $this->generateUrl('admincp_system_roles_page');
            return $this->redirect($url, 301);
        }

        return $this->render();
    }

    /**
     * This function Handle create vs update data including handle and handle record in database
     * @param $request
     * @param $id
     * @return array
     */
    private function handleFormData($request, $id){
        $em = $this->getDoctrine()->getEntityManager();
        if($id > 0) {
            $entity = $em->getRepository('AppBundle:SystemRolesEntity')->find($id);
        }
        else {
            $entity = new SystemRolesEntity;
        }

        $form = $this->createForm(\AppBundle\Form\Admin\SystemRolesFrom::class, $entity, []);
        $form->handleRequest($request);

        $form_errors = '';
        $success = FALSE;
        if ($form->isSubmitted() && $form->isValid()) {
            $validation = new SystemRolesValidation;
            $form_errors = $validation->validates($entity);
            if(!$form_errors){
                $roleType = self::filterPermissionRoleType($request->request->get('roleType'));
                $entity->setRoleType($roleType);
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

        $listModules = array();
        $getListModules = $em->getRepository('AppBundle:SystemRolesEntity')->getModules();
        if(!empty($getListModules)){
            foreach ($getListModules as $key => $module) {
                $var = new \stdClass();
                $var->id = $module['id'];
                $var->moduleName = $module['moduleName'];
                $var->view = self::checkRoleSystem($id, $module['id'], 'view');
                $var->add = self::checkRoleSystem($id, $module['id'], 'add');
                $var->edit = self::checkRoleSystem($id, $module['id'], 'edit');
                $var->delete = self::checkRoleSystem($id, $module['id'], 'delete');
                $module = $var;
                $listModules[] = $module;
            }
        }

        $handleData = array(
            'listModules' => $listModules,
            'form' => $form,
            'form_errors' => $form_errors,
            'success' => $success
        );

        return $handleData;
    }

    /**
     * @param array $arrData
     * @return Response
     */
    private function reportData($arrData = array())
    {
        return new Response();
    }

    /**
     * The function use to filter permission for each modules
     * @param  array
     * @return array
     */
    private function filterPermissionRoleType($roleType = []){
        if(!empty($roleType)){
            foreach ($roleType as $key => $value) {
                $arr_val= [];
                if(!empty($value['view'])){
                    $arr_val['view'] = 1;
                }else{
                    $arr_val['view'] = 0;
                }

                if(!empty($value['add'])){
                    $arr_val['add'] = 1;
                }else{
                    $arr_val['add'] = 0;
                }

                if(!empty($value['edit'])){
                    $arr_val['edit'] = 1;
                }else{
                    $arr_val['edit'] = 0;
                }

                if(!empty($value['delete'])){
                    $arr_val['delete'] = 1;
                }else{
                    $arr_val['delete'] = 0;
                }
                $roleType[$key] = $arr_val;
            }
        }
        return serialize($roleType);
    }

    /**
     * This function use to check exists record of the role in database
     * @param  int
     * @param  int
     * @param  string
     * @return int
     */
    protected function checkRoleSystem($roleId, $module_id, $action = ''){
        $em = $this->getDoctrine()->getEntityManager();
        $result_role_active = $em->getRepository('AppBundle:SystemRolesEntity')->find($roleId);
        if(!empty($result_role_active)){
            if(!empty($result_role_active->getRoleType())){
                $roleType = unserialize($result_role_active->getRoleType());
                if(!empty($roleType[$module_id][$action])) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }
    }

    /**
     * This function used to render form html filter for data
     * @return array
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
