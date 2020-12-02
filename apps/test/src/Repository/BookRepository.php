<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public const DEFAULT_SEARCH_LIMIT = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Fulltext search
     * @return Book[] Returns an array of Book objects
     */
    public function search($query, $limit = self::DEFAULT_SEARCH_LIMIT)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $sql = "SELECT b.id, bt.name FROM book b LEFT JOIN book_translation bt ON b.id = bt.translatable_id WHERE MATCH(bt.name) AGAINST (? IN BOOLEAN MODE) LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $query);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
