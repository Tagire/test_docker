<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class BookController extends AbstractController
{
    /**
     * @Route("/book/create", name="book_new", methods={"POST"})
     */
    public function new(Request $request, AuthorRepository $authorRepository): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $nameTranslations = $request->get('name');
        if (empty($nameTranslations)) {
            throw new HttpException(404, 'Name is required');
        }

        $book = new Book();
        foreach ($nameTranslations as $locale => $name) {
            $book->translate($locale)->setName($name);
        }

        $authorIds = $request->get('authors', []);
        if (!empty($authorIds)) {
            $authors = $authorRepository->findBy(['id' => $authorIds]);
            foreach ($authors as $author) {
                $book->addAuthor($author);
            }
        }

        $entityManager->persist($book);
        $book->mergeNewTranslations();
        $entityManager->flush();

        return new JsonResponse($book, 200, []);
    }

    /**
     * @return JsonResponse
     * @Route("/{_locale}/book/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): JsonResponse
    {
        return new JsonResponse($book, 200, []);
    }

    /**
     * @return JsonResponse
     * @Route("/book/search", name="book_search", methods={"GET"})
     */
    public function search(Request $request, BookRepository $bookRepository): JsonResponse
    {
        if (empty($request->get('query'))) {
            throw new HttpException(404, 'No search query.'); 
        }

        $bookIndexes = $bookRepository->search($request->get('query'));
        $books = [];

        foreach ($bookIndexes as $bookIndex) {
            $books[] = [
                'name' => $bookIndex['name'],
                'url' => $this->generateUrl('book_show', ['id' => $bookIndex['id'], 'locale' => $request->getLocale()], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }

        return new JsonResponse(['books' => $books], 200, []);
    }
}
