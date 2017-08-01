<?php
namespace AppBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AppBundle\Entity\SystemUsersEntity;


/**
 * @ORM\Table(name="system_users")
 * @ORM\Entity(repositoryClass="AdminSystemUsersRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdminSystemUsersRepository extends EntityRepository
{

    public function createRecordDb($data)
    {
        $entity = new SystemUsersEntity();
        $entity->setRoleID($data['roleId']);
        $entity->setUsername($data['username']);
        $entity->setEmail($data['email']);
        $entity->setPassword($data['password']);
        $entity->setStatus($data['status']);
        $entity->setUpdated_Date(time());
        $entity->setCreatedDate(time());

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return $entity->getID();
    }

    public function updateRecordDb($data)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppBundle:SystemUsersEntity')->find($data['id']);

        $entity->setRoleID($data['roleId']);
        $entity->setUsername($data['username']);
        $entity->setEmail($data['email']);
        $entity->setPassword($data['password']);
        $entity->setStatus((int)$data['status']);
        $entity->setUpdated_Date(time());

        $em->flush();

        return $entity->getID();
    }

    public function deleteRecordDb($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppBundle:SystemUsersEntity')->findOneBy(array('id'=>$id));
        $em->remove($entity);
        $em->flush();
    }

    public function getRecords($offset, $limit, $where = array(), $order = array('field'=>'id', 'by'=>'DESC'))
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemUsersEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->addSelect("fk.roleName");
        $query->leftJoin("AppBundle:SystemRolesEntity", "fk", "WITH", "pk.roleId=fk.id");
        $query->where('pk.id > 0');
        if(!empty($where)){
            if(isset($where['key']) && $where['key']) {
                $query->andWhere('pk.username LIKE :key')->setParameter('key', '%'.$where['key'].'%');
            }
            if(isset($where['date_range']) && $where['date_range']) {
                $query->andWhere('pk.updatedDate >= :date_from')->setParameter('date_from', $where['date_range']['from']);
                $query->andWhere('pk.updatedDate <= :date_to')->setParameter('date_to', $where['date_range']['to']);
            }
        }
        $query->orderBy("pk.".$order['field'], $order['by']);
        $query->setMaxResults($offset);
        $query->setFirstResult($limit);
        $result = $query->getQuery()->getArrayResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);

        return $result;
    }

    public function getTotalRecords($key = '')
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemUsersEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('COUNT(pk.id)');
        if($key){
            $query->where('pk.username LIKE :key')->setParameter('key', '%'.$key.'%');
        }
        $total = $query->getQuery()->getSingleScalarResult();

        return $total;
    }

    public function getRolesUser()
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:SystemRolesEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('pk.id, pk.roleName');
        $query->where('pk.roleStatus = 1');
        $result = $query->getQuery()->getResult();

        return $result;
    }

}
