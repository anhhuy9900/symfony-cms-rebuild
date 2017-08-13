<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminController extends Controller
{
    public $admincp_service;
    public $global_service;
    public $global_helper_service;
    public $data;

    /**
     * Used as constructor
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->admincp_service = $this->container->get('app.admincp_service');
        $this->global_service = $this->container->get('app.global_service');
        $this->global_helper_service = $this->container->get('app.global_helper_service');
        $this->data = array(
            'title' => 'Admin DasnhBoard',
            'user_admin' => $this->admincp_service->adminUserAdminInfo(),
            'left_menu' => $this->admincp_service->adminListModulesLeft(0)
        );
    }


    public function indexAction(Request $request)
    {
        $this->data['title'] = 'Admin DasnhBoard';
        return $this->render('@admin/admin.html.twig', $this->data);
    }

    /**
     * @param Request $request
     */
    public function uploadFilesAction(Request $request)
    {
        $service = $this->container->get('app.upload_files_service');
        $file = $request->files->get('file');
        $status = FALSE;
        $image = '';
        if ($file) {
            $status = TRUE;
            $image = $service->uploadFileRequest($file, 'files_tmp');
        }
        $data = array(
            'status' => $status,
            'file' => $image,
            'file_path' => $service->getPathFolderUpload() . $image
        );

        print json_encode($data);
        die();
    }

}
