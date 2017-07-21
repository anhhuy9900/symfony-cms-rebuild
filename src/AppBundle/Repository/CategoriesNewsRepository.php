<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Repository\Admin\AdminCategoriesNewsRepository;


/**
 * @ORM\Table(name="categories_news")
 * @ORM\Entity(repositoryClass="AdminCategoriesNewsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CategoriesNewsRepository extends EntityRepository
{
    use AdminCategoriesNewsRepository;

}