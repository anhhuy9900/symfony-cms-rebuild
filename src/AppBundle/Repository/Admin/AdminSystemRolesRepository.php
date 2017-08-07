<?php
namespace AppBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AppBundle\Entity\SystemRolesEntity;


/**
 * @ORM\Table(name="system_roles")
 * @ORM\Entity(repositoryClass="AdminSystemRolesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdminSystemRolesRepository extends EntityRepository
{

    public function getRecords($offset, $limit, $where = array(), $order = array('field'=>'id', 'by'=>'DESC'))
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemRolesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.id > 0');
        if(!empty($where)){
            if(isset($where['key']) && $where['key']) {
                $query->andWhere('pk.roleName LIKE :key')->setParameter('key', '%'.$where['key'].'%');
            }
            if(isset($where['date_range']) && $where['date_range']) {
                $query->andWhere('pk.updatedDate >= :date_from')->setParameter('date_from', $where['date_range']['from']);
                $query->andWhere('pk.updatedDate <= :date_to')->setParameter('date_to', $where['date_range']['to']);
            }
        }
        $query->orderBy("pk.".$order['field'], $order['by']);
        $query->setMaxResults($offset);
        $query->setFirstResult($limit);
        $result = $query->getQuery();

        return $result->getResult();
    }

    public function getTotalRecords($key = '')
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemRolesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('COUNT(pk.id)');
        if($key){
            $query->where('pk.roleName LIKE :key')->setParameter('key', '%'.$key.'%');
        }
        $total = $query->getQuery()->getSingleScalarResult();

        return $total;
    }

    public function getModules()
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemModulesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('pk.id, pk.moduleName');
        $query->where('pk.moduleStatus = 1');
        $query->andwhere('pk.parentId > 0');
        $result = $query->getQuery()->getResult();

        return $result;
    }

}
