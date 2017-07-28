<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\Front\BaseController;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends BaseController
{
    /**
     * @Route("/news", name="news_page")
     */
    public function indexAction(Request $request)
    {
        $limit = 9;
        $page_offset = !empty($request->query->get('p')) ? (int)$request->query->get('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

        $repository = $this->getDoctrine()->getRepository('AppBundle:NewsEntity');
        $results = $repository->list_data_news_get($limit, $offset);
        $total = $repository->total_list_data_news_get();
        $pagination = $this->global_helper_service->pagination($total, $page_offset, $limit, 3, $this->generateUrl('news_page'));

        $this->data['title'] = 'News Page';
        $this->data['results'] = $results;
        $this->data['pagination'] = $pagination;

        return $this->render('@frontend/news/index.html.twig', $this->data);
    }

    /**
     * @Route("/news/{slug}", name="news_detail_page")
     */
    public function detailAction($slug)
    {
        $slug = $this->global_helper_service->cleanStringInput($slug);
        $repository = $this->getDoctrine()->getRepository('AppBundle:NewsEntity');
        $result = $repository->get_news_detail($slug);

        $this->data['title'] = 'News Page';
        $this->data['result'] = $result;

        return $this->render('@frontend/news/detail.html.twig', $this->data);
    }
}
