<?php
namespace AppBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AppBundle\Entity\SystemModulesEntity;

/**
 * @ORM\Table(name="system_modules")
 * @ORM\Entity(repositoryClass="AdminSystemModulesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdminSystemModulesRepository extends EntityRepository
{
    /**
     * @param  [int]
     * @param  [int]
     * @param  array
     * @param  array
     * @return array
     */
    public function getRecords($offset, $limit, $where = array(), $order = array('field'=>'id', 'by'=>'DESC'))
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemModulesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.id > 0');
        if(!empty($where)){
            if(isset($where['key']) && $where['key']) {
                $query->andWhere('pk.moduleName LIKE :key')->setParameter('key', '%'.$where['key'].'%');
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

    /**
     * @param  string
     * @return total
     */
    public function getTotalRecords($key = '')
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemModulesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('COUNT(pk.id)');
        if($key){
            $query->where('pk.moduleName LIKE :key')->setParameter('key', '%'.$key.'%');
        }
        $total = $query->getQuery()->getSingleScalarResult();

        return $total;
    }

    /**
     * @param  [int]
     * @param  array
     * @return array
     */
    public function getRecursiveModules($parentId, &$arr_menu = array())
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemModulesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk.id, pk.moduleName");
        $query->where('pk.moduleStatus = 1');
        $query->andWhere('pk.parentId = :parentId')->setParameter('parentId', $parentId);
        $results = $query->getQuery()->getResult();
        if(!empty($results)){
            foreach($results as $value){
                $str = '';
                if($parentId > 0){
                    $str .= '--';
                }
                $value['moduleName'] = $str.$value['moduleName'];
                $arr_menu[] = $value;
                $this->getRecursiveModules($value['id'], $arr_menu);
            }
        }

        return $arr_menu;
    }

}
