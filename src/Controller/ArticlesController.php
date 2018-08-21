<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController extends FOSRestController
{
    private $articleRepository;
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em)
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }
    public function getArticlesAction()
    {
        $articles= $this->articleRepository->findAll();
        return $this->view($articles);
    }
    public function getUserAction(Article $article)
    {
        return $this->view($article);
    }
    public function postArticlesAction(Article $article)
    {
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }
}
