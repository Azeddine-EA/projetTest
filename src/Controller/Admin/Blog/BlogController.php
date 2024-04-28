<?php 
namespace App\Controller\Admin\Blog;

// use App\Controller\Admin;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Controller\MainController;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin/blog', name:'admin_blog_')]
final class BlogController extends AbstractController
{
    // admin_blog_index
    #[Route("", name: 'index')]
    public function index(PostRepository $PostRepository, UserRepository $UserRepository):Response
    {

    // Récupérer la liste des utilisateurs depuis la base de données
    $posts = $PostRepository->findAll();
    // $user = $UserRepository->findAll();
    // dd($post);
    // dd($user);
    // $view ='main/home.html.twig';

        return $this->render('admin/blog/index.html.twig',[
            'posts'=>$posts,
            // 'user'=>$user,
        ]);
    }

    #[Route("/new", name: 'new', methods:['GET','POST'])]
    public function create(Request $request, UserRepository $userRepository ,EntityManagerInterface $entM):Response
    {
        
        $post = new Post();
        $author = new User();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $author = $userRepository->find(5);
            $post->setAuthor($author);
            $post->setPublishedAt(new \DateTimeImmutable);
            $entM->persist($post);
            $entM->flush();
    // status ici pour redirection 303 (en cas d utilotsation de turbo)
            return $this->redirectToRoute('admin_blog_index', status: Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/blog/new.html.twig', [
        'form' => $form,
        'post' => $post
        ]);
        // $author = new User();
        // dd($author);

         // la méthode persist() pour indiquer à Doctrine que vous souhaitez enregistrer une nouvelle entité, puis utilisez flush() pour effectivement enregistrer les modifications dans la base de données.
        
        //on instancie une variable
        // $author = new User();
        //On donne a la variable author le setEmail etc .. 
        // $author ->setEmail("Tessa2@gmail.com")
        //         ->setFullName("Tessa")
        //         ->setUsername("Priya")
        //         ->setPassword("484785");

        //Persite permet de faire que entM prend/prepare $author
        // $entM->persist($author);
        //flush permet d'envoyer à la bdd
        // $entM->flush();

        
        // $authorId = $UserRepository->find(6);

        // $post= new Post();
        // $post -> setAuthor($authorId)
        //         ->setTitle("Arise")
        //         ->setSlug("Solo-Leveling")
        //         ->setSummary("Igris")
        //         ->setContent("Sung jin woo is a monarch of shadow")
        //         ->setPublishedAt(new \DateTimeImmutable)
        //         ->setUpdatedAt(NULL);
        // $entMng->persist($post);
        // $entMng->flush();

        return $this->render('admin/blog/new.html.twig');
    }



    #[Route("/{id<\d+>}", name: 'show', methods : ['GET'])]
    public function show(Post $post,PostRepository $PostRepository,UserRepository $UserRepository):Response
    {

    // Récupérer la liste des utilisateurs depuis la base de données
    $post = $PostRepository->find($post->getId());
    $user = $UserRepository->find($post->getAuthor());
    // $post = $Postrepository->find($id);
    // dd($posts);
    // dd($user);

        return $this->render('admin/blog/post.html.twig',[
            'post'=>$post,
            'user'=>$user,
        ]);
    }

    //<\d+> permet de dire que en parametre on attend  1 chiffre ou + 
    // Get permet de recupérer les info dans la barre url et post envoyer
    #[Route("/edit/{id<\d+>}", name: 'edit', methods : ['GET','Post'])]
    public function edit(Post $post, PostRepository $postRepository, EntityManagerInterface $emPost, Request $request)
    {
        $post = $postRepository->find($post->getId());
        // dd($post);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $post->setPublishedAt(new \DateTimeImmutable);
            $emPost->persist($post);
            $emPost->flush();
        // status ici pour redirection 303 (en cas d utilotsation de turbo)
            return $this->redirectToRoute('admin_blog_index', status: Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/blog/edit.html.twig', [
            'form' => $form,
        ]);
    }




    #[Route("/suppr/{id<\d+>}", name: 'suppr', methods : ['GET','Post'])]
    public function suppr(Post $post, PostRepository $postRepository, EntityManagerInterface $emPost, Request $request)
    {
        $posts = $postRepository->findAll();
        $post = $postRepository->find($post->getId());
        // dd($post);
        $emPost->remove($post);
        $emPost->flush();

        return $this->redirectToRoute('admin_blog_index', [
            'posts' => $posts,
        ]);
    }
}
