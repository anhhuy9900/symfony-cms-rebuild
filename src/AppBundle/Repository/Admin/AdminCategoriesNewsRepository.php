<?php
namespace AppBundle\Repository\Admin;

Trait AdminCategoriesNewsRepository
{
    /**
     * @param $offset
     * @param $limit
     * @param array $where
     * @param array $order
     * @return array
     */
    public function getRecords($offset, $limit, $where = array(), $order = array('field'=>'id', 'by'=>'DESC'))
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:CategoriesNewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.id > 0');
        if(!empty($where)){
            if(isset($where['key']) && $where['key']) {
                $query->andWhere('pk.title LIKE :key')->setParameter('key', '%'.$where['key'].'%');
            }
            if(isset($where['key']) && $where['date_range']) {
                $query->andWhere('pk.updatedDate >= :date_from')->setParameter('date_from', $where['date_range']['from']);
                $query->andWhere('pk.updatedDate <= :date_to')->setParameter('date_to', $where['date_range']['to']);
            }
        }
        $query->orderBy("pk.".$order['field'], $order['by']);
        $query->setMaxResults($offset);
        $query->setFirstResult($limit);
        $result = $query->getQuery()->getResult();

        return $result;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getTotalRecords($key = '')
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:CategoriesNewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('COUNT(pk.id)');
        if($key){
            $query->where('pk.title LIKE :key')->setParameter('key', '%'.$key.'%');
        }
        $total = $query->getQuery()->getSingleScalarResult();

        return $total;
    }

}
