<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/page/{slug}", name="page")
     * @Template()
     */
    public function pageAction($slug)
    {
        $page = $this->getDoctrine()
            ->getRepository('AxSPageBundle:Page')
            ->findOneBy(['visible' => 1, 'slug' => $slug]);

        if (!$page) throw new NotFoundHttpException();

        return [
            'page' => $page
        ];
    }

    /**
     * @Route("/delivery", name="page-delivery")
     * @Template()
     */
    public function pageDeliveryAction()
    {
        return [];
    }

    /**
     * @Route("/articles", name="articles")
     * @Template()
     */
    public function articlesAction()
    {
        $articles = $this->getDoctrine()
            ->getRepository('AxSArticleBundle:Article')
            ->findBy(['visible' => 1], ['createdAt' => 'DESC']);

        return [
            'articles' => $articles,
        ];
    }

    /**
     * @Route("/articles/{slug}", name="article")
     * @Template()
     */
    public function articleAction($slug)
    {
        $article = $this->getDoctrine()
            ->getRepository('AxSArticleBundle:Article')
            ->findOneBy(['visible' => 1, 'slug' => $slug]);

        if (!$article) throw new NotFoundHttpException();

        return [
            'article' => $article,
        ];
    }
}
