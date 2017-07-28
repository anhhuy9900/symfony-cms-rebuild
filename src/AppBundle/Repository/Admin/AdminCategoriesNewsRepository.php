<?php
namespace AppBundle\Repository\Admin;

Trait AdminCategoriesNewsRepository
{
    public function createRecordDb($data)
    {
        $entity = new CategoriesNewsEntity();
        $entity->setTitle($data['title']);
        $entity->setStatus($data['status']);
        $entity->setUpdated_Date(time());
        $entity->setCreated_Date(time());

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return $entity->getID();
    }

    public function updateRecordDb($data)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppBundle:CategoriesNewsEntity')->find($data['id']);

        $entity->setTitle($data['title']);
        $entity->setStatus($data['status']);
        $entity->setUpdated_Date(time());

        $em->flush();

        return $entity->getID();
    }

    public function deleteRecordDb($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppBundle:CategoriesNewsEntity')->findOneBy(array('id'=>$id));
        $em->remove($entity);
        $em->flush();
    }

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
                $query->andWhere('pk.updated_date >= :date_from')->setParameter('date_from', $where['date_range']['from']);
                $query->andWhere('pk.updated_date <= :date_to')->setParameter('date_to', $where['date_range']['to']);
            }
        }
        $query->orderBy("pk.".$order['field'], $order['by']);
        $query->setMaxResults($offset);
        $query->setFirstResult($limit);
        $result = $query->getQuery()->getResult();

        return $result;
    }

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