<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\FrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends FrontController
{
    /**
     * @Route("/", name="home_page")
     */
    public function indexAction(Request $request)
    {
        $this->image_helper = $this->container->get('app.image_helper');
        $results = $this->getDoctrine()->getRepository('AppBundle:NewsEntity')->getNewsOnHomePage();
//        if(!empty($results)){
//            foreach($results as $value){
//                $image_path = $this->getParameter('upload_dir').'/'. $value->getImage();
//                $resize_path = $this->getParameter('upload_dir').'/resize/';
//                $resize_data = $this->image_helper->resizeImage($image_path, $resize_path, 360, 206);
//                //$value->image_url = $this->get('request')->getBasePath() . '/web/uploads/resize/' . $resize_data['image_name'];
//                $value->image_url = '';
//
//            }
//        }

        $this->data['title'] = 'Home Page';
        $this->data['results'] = $results;
        return $this->render('@frontend/home/index.html.twig', $this->data);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testUploadAction(Request $request)
    {
        $getFile = $this->getDoctrine()->getRepository('AppBundle:FilesEntity')->find(1);
        $form = $this->createFormBuilder()
            ->add('file',\Symfony\Component\Form\Extension\Core\Type\FileType::class, array('label' => 'Upload file'))
            ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ->getForm();

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $file = $form['file']->getData();

                $uploadFile = $this->container->get('app.upload_files_service');
                $uploadFile->upload($file, 'test');
                $fileEntity = new \AppBundle\Entity\FilesEntity;
                $fileEntity->setType('file_test');
                $fileEntity->setFile($uploadFile->fileName);
                $fileEntity->setPath($uploadFile->path);
                $fileEntity->setStatus(1);
                $fileEntity->setCreatedDate();

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($fileEntity);
                $em->flush();

                $getFile = $this->getDoctrine()->getRepository('AppBundle:FilesEntity')->find($fileEntity->getID());
            }
        }

        $this->data['file'] = $getFile;
        $this->data['form'] = $form->createView();
        return $this->render('@frontend/home/test_upload.html.twig', $this->data);
    }
}
