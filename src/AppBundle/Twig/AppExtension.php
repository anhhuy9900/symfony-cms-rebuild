<?php
namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class AppExtension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;

    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('count', array($this, 'count')),
            new \Twig_SimpleFilter('strpos', array($this, 'strposFilter')),
            new \Twig_SimpleFilter('getLinkOrder', array($this, 'getLinkOrderFilter')),
            new \Twig_SimpleFilter('imageStyle', array($this, 'imageStyleFilter')),
        );
    }

    public function count($value)
    {
        return count($value);
    }
  
  /**
   * strpos
   * @param $value
   * @param string $type
   *
   * @return bool|int
   */
    public function strposFilter($value, $type='')
    {
        return strpos($value, $type);
    }
  
  /**
   * Get link order filter pages in admin
   * @param $url
   * @param string $field_type
   *
   * @return string
   */
    public function getLinkOrderFilter($url, $field_type = 'id|DESC')
    {

        $filter_order = $field_type;
        if($this->strposFilter($url, $field_type)){
            if($this->strposFilter($url, 'order=')){
                $replace = $this->strposFilter($url, '?order=') ? '?order='.$field_type : '&order='.$field_type;
                $url = str_replace($replace, '', $url);
            }
            $ex_field = explode('|', $field_type);

            if(!empty($filter_order)){
                if($ex_field[1] == 'DESC'){
                    $filter_order = $ex_field[0] . '|' .'ASC';
                }else{
                    $filter_order = $ex_field[0] . '|' .'DESC';
                }
            }

        }
        $filter_url = $this->strposFilter($url, '?') ? $url.'&order=' : $url.'?order=';
        $handle_url = $filter_url . $filter_order;

        return $handle_url;
    }
  
  /**
   * Get global session on app
   * @return array
   */
    public function getGlobals() {
        $session = new Session();

        return [
            'session' => $session->all(),
            'session_user' => $this->container->get('app.global_service')->sessionCurrentUser()
        ];
    }
  
  /**
   * Use render image style
   * @param $file
   * @param $filterFolder
   * @param $with
   * @param $height
   *
   * @return string
   */
    public function imageStyleFilter($file, $filterFolder, $with, $height) {
      $newPath = '/media/' . $filterFolder . '/';
      $imageHelper = $this->container->get('app.image_helper');
      $imageHelper->sourceFile = $this->container->get('kernel')->getWebPathDir() . $file;
      $imageHelper->newFile = $this->container->get('kernel')->getMediaDir() . $filterFolder . '/';
      $imageHelper->width = $with;
      $imageHelper->height = $height;
      $imageHelper->crop();
      $resize_data = $imageHelper->getData();
      return !empty($resize_data) ? $newPath . $resize_data['image_name'] : '';
    }

    public function getName()
    {
        return 'app_extension';
    }
}