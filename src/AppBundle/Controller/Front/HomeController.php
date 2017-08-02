<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home_page")
     */
    public function indexAction(Request $request)
    {

        $this->image_helper = $this->container->get('app.image_helper');
        $results = $this->getDoctrine()->getRepository('AppBundle:NewsEntity')->list_news_get_all();
        if(!empty($results)){
            foreach($results as $value){
                $image_path = $this->getParameter('upload_dir').'/'. $value->getImage();
                $resize_path = $this->getParameter('upload_dir').'/resize/';
                $resize_data = $this->image_helper->resizeImage($image_path, $resize_path, 360, 206);
                //$value->image_url = $this->get('request')->getBasePath() . '/web/uploads/resize/' . $resize_data['image_name'];
                $value->image_url = '';

            }
        }

        $this->data['title'] = 'Home Page';
        $this->data['results'] = $results;
        return $this->render('@frontend/home/index.html.twig', $this->data);

    }

    /**
     * @Route("test-upload", name="home_page")
     */
    public function testUploadAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('file',\Symfony\Component\Form\Extension\Core\Type\FileType::class, array('label' => 'Upload file'))
            ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ->getForm();

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $file = $form['file']->getData();
                dump($file);die;
                $file = new \AppBundle\Entity\FilesEntity;
                $file->setType('file_test');
                $file->setFile('file_test');

                $em = $this->getDoctrine()->getEntityManager();

                $em->persist();
                $em->flush();

                return $this->redirect($this->generateUrl('home_page'));
            }
        }
        $this->data['form'] = $form->createView();
        return $this->render('@frontend/home/test_upload.html.twig', $this->data);
    }
}