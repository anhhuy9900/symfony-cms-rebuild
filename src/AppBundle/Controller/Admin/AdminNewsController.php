<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;


/* import Bundle Custom */
use AppBundle\Controller\AdminController;
use AppBundle\Validation\Admin\AdminNewsValidation;
use AppBundle\Entity\NewsEntity;

class AdminNewsController extends AdminController
{

    /**
     * Used as constructor
     * @param ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->data['title'] = 'Admin Manage News';
        $this->data['admin_module_id'] = $this->admincp_service->adminGetCurrentModule('admincp_news_page')->getID();
    }

    /**
     * @Route("/system/news", name="admincp_news_page")
     */
    public function indexAction(Request $request)
    {

        $key = $request->query->get('key') ? $this->global_helper_service->cleanStringInput($request->query->get('key')) : '';
        $arr_order = $request->query->get('order') ? $this->global_helper_service->handleParamrOderInUrl($request->query->get('order')) : array('field'=>'id', 'by'=>'DESC');
        $date_range = $request->query->get('date_range') ? $this->global_helper_service->handleParamDateRangeInUrl($request->query->get('date_range')) : '';
        $status = $request->query->get('status') != '' ? (int)$this->global_helper_service->cleanStringInput($request->query->get('status')) : '';
        $limit = !empty($request->query->get('lm')) ? (int)$request->query->get('lm') : 10;
        $page_offset = !empty($request->query->get('p')) ? (int)$request->query->get('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

        $repository = $this->getDoctrine()->getRepository('AppBundle:NewsEntity');
        $total = $repository->getTotalRecords(array('key' => $key, 'date_range' => $date_range, 'status' => $status));
        $results = $repository->getRecords($limit, $offset, array('key' => $key, 'date_range' => $date_range, 'status' => $status), $arr_order);

        if($request->query->get('report')){
            $this->reportData($results);
        }

        $pagination = $this->global_helper_service->pagination($total, $page_offset, $limit, 3, $this->generateUrl('admincp_news_page'));
        //dump($results);die();

        $this->data['filterOptions'] = $this->filterOptions();
        $this->data['results'] = $results;
        $this->data['pagination'] = $pagination;

        return $this->render('@admin/news/list.html.twig', $this->data);
    }

    /**
     * @Route("/system/news/create", name="admincp_news_create_page")
     */
    public function createAction(Request $request)
    {
        $handleData = self::handleFormData($request, 0);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Created record success!');
            $url = $this->generateUrl('admincp_news_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        $this->data['galleries'] = $handleData['galleries'];
        $this->data['tags'] = $handleData['tags'];
        return $this->render('@admin/news/edit.html.twig', $this->data);

    }

    /**
     * @Route("/system/news/edit/{id}", name="admincp_news_edit_page")
     */
    public function editAction(Request $request, $id)
    {
        $handleData = self::handleFormData($request, $id);
        if($handleData['success']){
            $request->getSession()->getFlashBag()->add('message_data', 'Updated record success!');
            $url = $this->generateUrl('admincp_news_page');
            return $this->redirect($url, 301);
        }

        $this->data['form'] = $handleData['form']->createView();
        $this->data['form_errors'] = $handleData['form_errors'];
        $this->data['galleries'] = $handleData['galleries'];
        $this->data['tags'] = $handleData['tags'];
        return $this->render('@admin/news/edit.html.twig', $this->data);
    }

    /**
     * @Route("/system/news/delete/{id}", name="admincp_news_delete_page")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:NewsEntity')->find($id);
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
            $entity = $em->getRepository('AppBundle:NewsEntity')->find($id);
        }
        else {
            $entity = new NewsEntity;
        }

        //Get list galleries
        //$galleries = $this->global_service->getListGalleries($id, 'news');
        $galleries = [];

        //Get list tags
        $tags = $em->getRepository('AppBundle:NewsEntity')->getTagsNews($id, 'news');

        $form = $this->createForm(\AppBundle\Form\Admin\News::class, $entity,
            [
                'galleries' => $galleries ? json_encode($galleries) : [],
                'tags'      => $tags
            ]
        );

        $form->handleRequest($request);

        $form_errors = '';
        $success = FALSE;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

//            $validation = new AdminNewsValidation();
//            $validation->title = $data['title'];
//            $validation->description = $data['description'];
//            $validation->content = $data['content'];

            $errors = $this->get('validator')->validate($entity);
            $form_errors = $this->global_helper_service->getErrorMessages($errors);
            if(!$form_errors){

                //Create Slug
                $entity->setSlug($this->global_helper_service->createSlug($entity->getName()));

                //Upload image
//                $service = $this->container->get('app.upload_files_service');
//                $data['image'] = $service->uploadFileRequest($data['image'],'news');

                $file = $form['file']->getData();
                $uploadFile = $this->container->get('app.upload_files_service');
                $uploadFile->upload($file, 'news');
                $fileEntity = new \AppBundle\Entity\FilesEntity;
                $fileEntity->setType('news');
                $fileEntity->setFile($uploadFile->fileName);
                $fileEntity->setPath($uploadFile->path);
                $fileEntity->setStatus(1);
                $fileEntity->setCreatedDate();
                $entity->setFile($fileEntity);

                if($entity->getID() > 0){
                    /* Update record */
                    $em->flush();
                }
                else {
                    /* Create new record */
                    $em->persist($entity);
                    $em->flush();
                }

                /* handle gallery images */
                //create new files
                /*if(!empty($data['lists_thumb'])){
                    $files_gallery = json_decode($data['lists_thumb']);
                    foreach ($files_gallery as $key => $value) {
                        $service->saveFilesData($id, 'news', $value->file);
                    }
                }

                //delete new files
                if(!empty($data['lists_del_file'])){
                    $lists_del_file = json_decode($data['lists_del_file']);
                    foreach ($lists_del_file as $key_del_file => $del_file) {
                        $service->deleteFilesData($id, 'news', $del_file->id);
                    }
                }*/
                /* End handle gallery images */

                /* handle tags */
                //$em->getRepository('AppBundle:NewsEntity')->handleTagsNews($id, 'news', $data['tags']);
                /* End handle tags */

                $success = TRUE;
            }
        }

        $handleData = array(
            'form' => $form,
            'form_errors' => $form_errors,
            'success' => $success,
            'galleries' => $galleries,
            'tags' => (!empty($tags)) ? json_encode($tags): ''
        );

        return $handleData;
    }

    /**
     * Report data into file excel
     */
    private function reportData($arrData = array())
    {

        $file_name = 'List-News-Data-' . date('Ymd') . '.xlsx';

        // Create excel file
        $header = array();
        $header[] = 'ID';
        $header[] = 'Title';
        $header[] = 'Description';
        $header[] = 'Content';
        $header[] = 'Status';
        $header[] = 'Created Date';

        $data['headers'] = $header;

        $rows = array();
        if(!empty($arrData)){
            foreach($arrData as $key => $value) {
                $tmp = array();
                $tmp[] = $value->getID();
                $tmp[] = $value->getTitle();
                $tmp[] = $value->getDescription();
                $tmp[] = $value->getContent();
                $tmp[] = $value->getStatus() == 1 ? 'Active' : 'UnActive';
                $tmp[] = date('Y-m-d H:i:s',$value->getCreatedDate());

                $rows[] = $tmp;
            }
            $data['rows'] = $rows;
            $this->global_helper_service->exportToExcel($data,$file_name);
        }
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
