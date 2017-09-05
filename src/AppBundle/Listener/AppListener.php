<?php
namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AppListener
{
    protected $container;

    public function __construct(ContainerInterface $container) // this is @service_container
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        //$controller = $event->getController();
        //dump($controller);die;

    }
  
  /**
   * Hook request in app
   * @param GetResponseEvent $event
   */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $kernel    = $event->getKernel();
        $request   = $event->getRequest();

    }
  
  /**
   * Hook response in app
   * @param FilterResponseEvent $event
   */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response  = $event->getResponse();
        $request   = $event->getRequest();
        $kernel    = $event->getKernel();

        $adminService = $this->container->get('app.admincp_service');
        $redirectUrl = $this->container->get("router")->generate('admincp_login_page');
        $currentUrl = $this->container->get("router")->getContext()->getPathInfo();
        $route = $this->container->get("router")->match($currentUrl);
        if (!empty($route['_route'])) {
          if ($adminService->getModulesByAlias($route['_route']) && !$adminService->adminGetCurrentUserLogin()) {
            $redirectResponse = new RedirectResponse($redirectUrl);
            $event->setResponse($redirectResponse);
          }
        }
    }
  
}