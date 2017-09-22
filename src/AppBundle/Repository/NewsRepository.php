<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Repository\Admin\AdminNewsRepository;

class NewsRepository extends EntityRepository
{
    use AdminNewsRepository;
  
  /**
   * Get news items
   * @param $limit
   * @param $offset
   *
   * @return array|null
   */
    public function listNewsGet($limit, $offset)
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:NewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.status = 1');
        $query->orderBy("pk.updatedDate", "DESC");
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result = $query->getQuery()->getResult();

        if (!empty($result)) {
            return $result;
        }

        return NULL;
    }
  
  /**
   * Get total news item
   * @param array $where
   *
   * @return mixed
   */
    public function totalListNewsGet($where = array())
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:NewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('COUNT(pk.id)');
        $query->where('pk.status = 1');
        $total = $query->getQuery()->getSingleScalarResult();

        return $total;
    }
  
  /**
   * Get items news on home page
   * @return array|null
   */
    public function getNewsOnHomePage()
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:NewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.status = 1');
        $query->orderBy("pk.updatedDate", "DESC");
        $query->setMaxResults(9);
        $result = $query->getQuery()->getResult();

        if (!empty($result)) {
            return $result;
        }

        return NULL;
    }
  
  /**
   * Get News Detail Info
   * @param $slug
   *
   * @return mixed|null
   */
    public function getNewsDetail($slug)
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:NewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.status = 1');
        $query->andWhere('pk.slug = :slug')->setParameter('slug', $slug);

        $result = $query->getQuery()->getSingleResult();

        if (!empty($result)) {
            return $result;
        }

        return NULL;
    }
}