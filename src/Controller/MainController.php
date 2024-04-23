<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    class MainController extends AbstractController
    {
        #[Route("/", name: 'home')]
        public function home(PostRepository $PostRepository, UserRepository $UserRepository, EntityManagerInterface $entMng):Response
        {

        // Récupérer la liste des utilisateurs depuis la base de données
        $posts = $PostRepository->findAll();
        

        // $user = $UserRepository->findAll();
        // dd($post);
        // dd($user);
        // $view ='main/home.html.twig';

            return $this->render('main/home.html.twig',[
                'posts'=>$posts,
                // 'user'=>$user,
            ]);
        }



        #[Route("/{id}", name: 'show', methods : ['GET'])]
        public function show(Post $post,PostRepository $PostRepository,UserRepository $UserRepository):Response
        {

        // Récupérer la liste des utilisateurs depuis la base de données
        $posts = $PostRepository->find($post->getId());
        $user = $UserRepository->find($post->getAuthor());
        // $post = $Postrepository->find($id);
        // dd($posts);
        // dd($user);

            return $this->render('./main/post.html.twig',[
                'postId'=>$posts,
                'user'=>$user,
            ]);
        }
    }