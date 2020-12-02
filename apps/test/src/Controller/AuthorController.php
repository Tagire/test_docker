<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author/create", name="author_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        if (empty($request->get('name'))) {
            throw new HttpException(404, 'Name is required');
        }

        $author = new Author();
        $author->setName($request->get('name'));
        $entityManager->persist($author);

        $entityManager->flush();

        return new JsonResponse($author, 200, []);
    }
}
