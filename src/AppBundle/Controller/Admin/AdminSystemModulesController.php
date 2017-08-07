<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/* import Bundle Custom */
use AppBundle\Controller\AdminController;
use AppBundle\Validation\Admin\SystemModulesValidation;
use AppBundle\Entity\SystemModulesEntity;

class AdminSystemModulesController extends AdminController
{

    /**
     * Used as constructor
     * @param ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->data['title'] = 'Manage System Modules';
    }

    /**
     * @Route("/system/system-modules", name="admincp_system_modules_page")
     */
    public function indexAction(Request $request)
    {
        $key = $request->query->get('key') ? $this->global_helper_service->cleanStringInput($request->query->get('key')) : '';
        $arr_order = $request->query->get('order') ? $this->global_helper_service->handleParamrOderInUrl($request->query->get('order')) : array('field'=>'id', 'by'=>'DESC');
        $date_range = $request->query->get('date_range') ? $this->global_helper_service->handleParamDateRangeInUrl($request->query->get('date_range')) : array();

        $limit = $request->query->get('lm') ? (int)$request->query->get('lm') : 10;
        $page_offset = $request->query->get('p') ? (int)$request->query->get('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

    	$repository = $this->getDoctrine()->getRepository('AppBundle:SystemModulesEntity');
        $total = $repository->getTotalRecords($key);
        $results = $repository->getRecords($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);
        if($request->query->get('report')){
            $this->reportData($results);
        }

        $pagination = $this->global_helper_service->pagination($total, $page_offset, $limit, 3, $this->generateUrl('admincp_system_modules_page'));

        $this->data['filterOptions'] = $this->filterOptions();
        $this->data['results'] = $results;
        $this->data['pagination'] = $pagination;
        return $this->render('@admin/system-modules/list.html.twig', $this->data);
    }

    /**
     * @Route("/system/system-modules/create", name="admincp_system_modules_create_page")
     */
    public function createAction(Request $request)
    {
        $handleData = self::handleFormData($request, 0);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Created record success!');
            $url = $this->generateUrl('admincp_system_modules_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        return $this->render('@admin/system-modules/edit.html.twig', $this->data);

    }

    /**
     * @Route("/system/system-modules/edit/{id}", name="admincp_system_modules_edit_page")
     */
    public function editAction(Request $request, $id)
    {
        $handleData = self::handleFormData($request, $id);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Updated record success!');
            $url = $this->generateUrl('admincp_system_modules_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];

        return $this->render('@admin/system-modules/edit.html.twig', $this->data);
    }

    /**
     * @Route("/system/system-modules/delete/{id}", name="admincp_system_modules_delete_page")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:SystemModulesEntity')->find($id);
        if($entity) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('message_data', 'Deleted record success!');

            $url = $this->generateUrl('admincp_system_modules_page');
            return $this->redirect($url, 301);
        }

        return $this->render();
    }

    /**
     * This function handle create vs update data including handle and handle record in database
     * @param  object
     * @param  int
     * @return object
     */
    private function handleFormData($request, $id){
        $em = $this->getDoctrine()->getEntityManager();
        if($id > 0) {
            $entity = $em->getRepository('AppBundle:SystemModulesEntity')->find($id);
        }
        else {
            $entity = new SystemModulesEntity;
        }
        $recursiveModules = $this->global_helper_service->convertArrayResultSelectbox(
            $em->getRepository('AppBundle:SystemModulesEntity')->getRecursiveModules(0),
            array('key'=>'id', 'value'=>'moduleName')
        );

        $form = $this->createForm(\AppBundle\Form\Admin\SystemModules::class, $entity, ['recursiveModules' => $recursiveModules]);
        $form->handleRequest($request);

        $form_errors = '';
        $success = FALSE;
        if ($form->isSubmitted() && $form->isValid()) {
            // $data = $form->getData();
            // dump($data);die;
            $validation = new SystemModulesValidation();
            $form_errors = $validation->validates($entity);
            if(!$form_errors){
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
    private function reportData($arrData = array())
    {
        $file_name = 'List-Modules-' . date('Ymd') . '.xlsx';

        // Create excel file
        $header = array();
        $header[] = 'ID';
        $header[] = 'Nodule Name';
        $header[] = 'Module Alias';
        $header[] = 'Module Order';
        $header[] = 'Module Status';
        $header[] = 'Created Date';

        $data['headers'] = $header;

        $rows = array();
        if(!empty($arrData)){
            foreach($arrData as $key => $value) {
                $tmp = array();
                $tmp[] = $value->getID();
                $tmp[] = $value->getModuleName();
                $tmp[] = $value->getmoduleAlias();
                $tmp[] = $value->getModuleOrder();
                $tmp[] = $value->getModuleStatus() == 1 ? 'Active' : 'UnActive';
                $tmp[] = date('Y-m-d H:i:s',$value->getCreatedDate());

                $rows[] = $tmp;
            }
            $data['rows'] = $rows;
            $this->global_helper_service->exportToExcel($data, $file_name);
        }
    }

    /*
     * This function used to render form html filter for data
     */
    private function filterOptions(){
        $request = new Request();

        $key = $request->query->get('key') ? $this->global_helper_service->cleanStringInput($request->query->get('key')) : '';
        $date_range = $request->query->get('date_range') ? $request->query->get('date_range') : '';

        $array_filters = array();

        $array_filters['key'] =  array(
            'type' => 'input',
            'title' => 'Search',
            'default_value' => $key
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
