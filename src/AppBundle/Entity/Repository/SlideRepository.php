<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 13.12.15
 * Time: 17:50
 */

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class SlideRepository extends EntityRepository
{
    public function getVisibleItems($count = null)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.visible = 1')
            ->orderBy('s.order', 'asc')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
}