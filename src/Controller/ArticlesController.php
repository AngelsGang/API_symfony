<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController extends FOSRestController
{
    private  $articleRepository;
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em)
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }
    public function getArticlesAction()
    {
        $articles = $this->articleRepository->findAll();
        return $this->view($articles);
    }
    public function getUserAction(Article $article)
    {
        return $this->view($article);
    }
    /**
     * @Rest\Post("/articles")
     * @paramConverter("article", converter="fos_rest.request_body")
     */
    public function postArticlesAction(Article $article)
    {
        $user = $this->getUser();
        $article->setUser($user);

        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }
    public function putArticlesAction(Request $request, int $id)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $createdAt = $request->get('created_at');

        $article = $this->articleRepository->find($id);
        $article->setName($name);
        $article->setDescription($description);
        $article->setCreatedAt($createdAt);
        $this->em->persist($article);
        $this->em->flush();

        return $this->view($article);
    }
    public function deleteArticlesAction(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
    }
}
