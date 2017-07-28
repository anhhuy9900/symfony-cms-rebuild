<?php
namespace AppBundle\Controller\Front;

use AppBundle\Controller\Front\BaseController;
use Symfony\Component\HttpFoundation\Request;

class CategoriesNewsController extends BaseController
{
    /**
     * @Route("/categories", name="categories_news_page")
     */
    public function indexAction(Request $request)
    {
        dump(1111);die;
        return $this->render();
    }
}
