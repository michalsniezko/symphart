<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends AbstractController
{
    /**
     * @Route(path="/", methods={"GET"}, name="article_list")
     * @return Response
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/new", name="new_article", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Create', 'attr' => ['class' => 'btn btn-primary mt-3']]
            )->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/article/edit/{id}", name="edit_article", methods={"GET", "POST"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Update', 'attr' => ['class' => 'btn btn-primary mt-3']]
            )->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render(
            'articles/edit.html.twig',
            [
                'article' => $article,
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/article/{id}", methods={"GET"}, name="article_show")
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/article/delete/{id}", methods={"DELETE"}, name="delete_article")
     * @param Request $request
     * @param int $id
     */
    public function delete(Request $request, int $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    private function handleArticleForm(Article $article, Request $request)
    {
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Create', 'attr' => ['class' => 'btn btn-primary mt-3']]
            )->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }
    }

//    /**
//     * @Route("/article/save")
//     */
//    public function save()
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $article = new Article();
//        $article->setTitle('Article One');
//        $article->setBody('Test body');
//
//        $entityManager->persist($article);
//        $entityManager->flush();
//
//        return new Response('Saves an article with the id of ' . $article->getId());
//    }
}