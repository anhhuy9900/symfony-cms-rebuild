<?php
namespace AppBundle\Repository\Admin;

Trait AdminNewsRepository
{
    /**
     * @param $limit
     * @param $offset
     * @param array $where
     * @param array $order
     * @return array
     */
    public function getRecords($limit, $offset, $where = array(), $order = array('field'=>'id', 'by'=>'DESC'))
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:NewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk");
        $query->where('pk.id > 0');

        if(!empty($where)) {
            if(isset($where['key']) && $where['key']) {
                $query->andWhere('pk.title LIKE :key')->setParameter('key', '%'.$where['key'].'%');
            }
            if(isset($where['date_range']) && $where['date_range']) {
                $query->andWhere('pk.updatedDate >= :date_from')->setParameter('date_from', $where['date_range']['from']);
                $query->andWhere('pk.updatedDate <= :date_to')->setParameter('date_to', $where['date_range']['to']);
            }

            if(isset($where['status']) && $where['status']) {
                $query->andWhere('pk.status = :status')->setParameter('status', $where['status']);
            }
        }
        $query->orderBy("pk.".$order['field'], $order['by']);
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        return $query->getQuery()->getResult();
    }

    /**
     * @param array $where
     * @return mixed
     */
    public function getTotalRecords($where = array())
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:NewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select('COUNT(pk.id)');
        if(!empty($where)) {
            if(isset($where['key']) && $where['key']) {
                $query->andWhere('pk.title LIKE :key')->setParameter('key', '%'.$where['key'].'%');
            }
            if(isset($where['date_range']) && $where['date_range']) {
                $query->andWhere('pk.updatedDate >= :date_from')->setParameter('date_from', $where['date_range']['from']);
                $query->andWhere('pk.updatedDate <= :date_to')->setParameter('date_to', $where['date_range']['to']);
            }

            if(isset($where['status']) && $where['status']) {
                $query->andWhere('pk.status = :status')->setParameter('status', $where['status']);
            }
        }
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @return array
     */
    public function getCategoriesNews()
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:CategoriesNewsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk.id, pk.title");
        $results = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $results;
    }

    /**
     * @param $typeId
     * @param string $type
     * @return array
     */
    public function getTagsNews($typeId, $type = 'default')
    {
        $repository = $this->getEntityManager()->getRepository('AppBundle:TagsEntity');
        $query = $repository->createQueryBuilder('pk');
        $query->select("pk.id, pk.tagName");
        $query->where('pk.type = :type');
        $query->andWhere('pk.typeId = :typeId');
        $query->setParameter('type', $type);
        $query->setParameter('typeId', $typeId);
        $results = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $results;
    }

    /**
     * This function use create and update tags for each news
     * @param $typeId
     * @param string $type
     * @param string $tags
     * @return bool
     */
    public function handleTagsNews($typeId, $type = 'default', $tags = '')
    {
        if($tags){
            $entity = $this->getEntityManager()->getRepository('AppBundle:TagsEntity');
            $list_tags = explode(',', $tags);
            if(!empty($list_tags)){
                foreach($list_tags as $tag){
                    $query = $entity->createQueryBuilder('pk');
                    $query->select("pk");
                    $query->where('pk.type = :type');
                    $query->andWhere('pk.typeId = :typeId');
                    $query->andWhere('pk.tagName = :tagName');
                    $query->setParameter('type', $type);
                    $query->setParameter('typeId', $typeId);
                    $query->setParameter('tagName', $tag);
                    $get_tag_exists = $query->getQuery()->getResult();
                    if(empty($get_tag_exists)) {
                        //Create tag in database
                        $create = new TagsEntity();
                        $create->setTypeID($typeId);
                        $create->setType($type);
                        $create->setTagName($tag);
                        $create->setStatus(1);
                        $create->setCreatedDate(time());
                        $em = $this->getEntityManager();
                        $em->persist($create);
                        $em->flush();

                    }
                }
            }

            //delete tag if it isn't exists in list atgs
            $query = $entity->createQueryBuilder('pk');
            $query->select("pk");
            $query->where('pk.type = :type');
            $query->andWhere('pk.typeId = :typeId');
            $query->andWhere($query->expr()->notIn('pk.tagName', ':list_tags'));
            $query->setParameter('type', $type);
            $query->setParameter('typeId', $typeId);
            $query->setParameter('list_tags', $list_tags);
            $list_tags_delete = $query->getQuery()->getResult();
            if(!empty($list_tags_delete)) {
                foreach ($list_tags_delete as $tag) {
                    $entity_delete = $entity->findOneBy(array('id' => $tag->getID()));
                    $em = $this->getEntityManager();
                    $em->remove($entity_delete);
                    $em->flush();
                }
            }
            return TRUE;
        }
        return FALSE;
    }
}
