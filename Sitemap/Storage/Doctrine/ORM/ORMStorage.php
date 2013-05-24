<?php

namespace OpenSky\Bundle\SitemapBundle\Sitemap\Storage\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use OpenSky\Bundle\SitemapBundle\Sitemap\Storage\Storage;
use OpenSky\Bundle\SitemapBundle\Sitemap\Url;

class ORMStorage implements Storage
{

    /**
     * @var Doctrine\ORM\EntityManager 
     */
    protected $em;

    /**
     * @var Doctrine\ORM\EntityRepository
     */
    protected $repository;

    function __construct(EntityManager $em, EntityRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function find($page)
    {
        return $this->repository->createQueryBuilder('u')
                ->setMaxResults(self::PAGE_LIMIT)
                ->setFirstResult($this->getFirstResult($page))
                ->getQuery()
                ->getArrayResult();
    }

    public function findOne($loc)
    {
        return $this->repository->findOneBy(array(
                'loc' => $loc,
        ));
    }

    public function getTotalPages()
    {
        $count = $this->repository->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($count / self::PAGE_LIMIT);
    }

    public function save(Url $url)
    {
        $this->em->persist($url);
        $this->em->flush();
    }

    private function getFirstResult($page)
    {
        return ((int) $page - 1) * self::PAGE_LIMIT;
    }

}