<?php
namespace AppBundle\Controller\API;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api/test")
 */
class ApiTestController extends FOSRestController
{
    /**
     * @Rest\Get("/{id}", requirements={"id":"\d+"})
     * @Rest\View(
     *  serializerGroups={"test"}
     * )
     */
    public function getAction($id)
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:NewsEntity')->find($id);
        $data['result'] = $result;
      
        // JSON response can be return in another way as well
        $view = $this->view(['data' => $data])->setStatusCode(201);
        return $this->handleView($view);
    }
  
    /**
     * @Rest\Get
     * @Rest\View(
     *  serializerGroups={"test"}
     * )
     */
    public function listAction()
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:NewsEntity')->list_news_get_all();
        $data['result'] = $result;
        // JSON response can be return in another way as well
        $view = $this->view(['data' => $data])->setStatusCode(201);
        return $this->handleView($view);
    }
    
    /**
     * @Rest\Post
     * @Rest\View(
     *  serializerGroups={"test"},
     *  statusCode=400
     * )
     */
    public function createAction(Request $request)
    {
        $response = ['action' => 'CREATE'];
        $content = $request->getContent();
        if (!empty($content))
        {
          $response['data'] = json_decode($content, true); // 2nd param to get as array
        }
        $view = $this->view(['data' => $response])->setStatusCode(201);
        return $this->handleView($view);
    }
  
    /**
     * @Rest\Put("/{id}", requirements={"id":"\d+"})
     * @Rest\View(
     *  serializerGroups={"test"},
     *  statusCode=400
     * )
     */
    public function updateAction($id, Request $request)
    {
      $response = ['action' => 'UPDATE', 'id' => $id];
      $content = $request->getContent();
      if (!empty($content))
      {
        $response['data'] = json_decode($content, true); // 2nd param to get as array
      }
      $view = $this->view(['data' => $response])->setStatusCode(201);
      return $this->handleView($view);
    }
    
    /**
     * @Rest\Delete("/{id}", requirements={"id":"\d+"})
     * @Rest\View(
     *  serializerGroups={"test"},
     *  statusCode=400
     * )
     */
    public function deleteAction($id)
    {
        $response = ['action' => 'DELETE', 'id' => $id];
        $view = $this->view(['data' => $response])->setStatusCode(201);
        return $this->handleView($view);
    }
}