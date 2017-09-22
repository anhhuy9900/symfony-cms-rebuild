<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\FrontController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends FrontController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $limit = 9;
        $page_offset = !empty($request->query->get('p')) ? (int)$request->query->get('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

        $repository = $this->getDoctrine()->getRepository('AppBundle:NewsEntity');
        $results = $repository->listNewsGet($limit, $offset);
        $total = $repository->totalListNewsGet();
        $pagination = $this->global_helper_service->pagination($total, $page_offset, $limit, 3, $this->generateUrl('news_page'));

        $this->data['title'] = 'News Page';
        $this->data['results'] = $results;
        $this->data['pagination'] = $pagination;

        return $this->render('@frontend/news/index.html.twig', $this->data);
    }

    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction($slug)
    {
        $slug = $this->global_helper_service->cleanDataInput($slug);
        $repository = $this->getDoctrine()->getRepository('AppBundle:NewsEntity');
        $item = $repository->getNewsDetail($slug);

        $this->data['title'] = 'News Page';
        $this->data['item'] = $item;

        return $this->render('@frontend/news/detail.html.twig', $this->data);
    }
}
