<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\SecurityController;

class NewsController extends AbstractController
{

    private $em;

    private $security;

    public function __construct(EntityManagerInterface $em, SecurityController $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    #[Route('/news', name: 'news')]
    public function index(): Response
    {
        $date = new \DateTime('yesterday');

        $postRepository = $this->em->getRepository(Post::class);
        $userRepository = $this->em->getRepository(User::class);

        //query build for posts
        $posts = $postRepository->findByDateTimeField($date);
        $users = $userRepository->findAll();
        //$posts2 = $repository->findAll();
        //dd($posts);
        return $this->render('news/news.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);

    }

    #[Route('/search', methods:['GET','HEAD'], name: 'search_news')]
    public function searchNews(Request $request): Response
    {
        $date = new \DateTime('yesterday');

        $postRepository = $this->em->getRepository(Post::class);
        $userRepository = $this->em->getRepository(User::class);

        //get search parametar
        $posts_search = $request->get('search');
        //query build for posts
        
        $posts = $postRepository->findByDateAndSearch($date, $posts_search);
        $users = $userRepository->findAll();
        //$posts2 = $repository->findAll();
        //dd($posts);
        return $this->render('news/search.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);


    }


    #[Route('/news/{id}',methods:['GET','HEAD'], name: 'app_profile')]
    public function search(Request $request, $id): Response
    {
        $date = new \DateTime('yesterday');

        $posts_search = $request->get('search');

        $postRepository = $this->em->getRepository(Post::class);
        $userRepository = $this->em->getRepository(User::class);
        //query build for posts
        
        $allPostsByUser = $postRepository->findBy(['owner'=>$id]);
        $posts = $postRepository->findByOwnerDateSearch($date, $id, $posts_search);
        $user = $userRepository->find($id);
        

        //$posts2 = $repository->findAll();
        //dd($id);
        return $this->render('news/profile.html.twig', [
            'numberofposts' => $allPostsByUser,
            'posts' => $posts,
            'user' => $user, 
        ]);
    }


    #[Route('/create', name: 'create_form')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $user = $this->security->getUser('getId');
            $newPost = $form->getData();
            $newPost->setOwner($user);
            $newPost->setCreatedOn();
            $imagePath = $form->get('imagePath')->getData();
            if($imagePath){
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try{
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e){
                    return new Response($e->getMessage());
                }

                $newPost->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newPost);
            $this->em->flush();
            return $this->redirectToRoute('news');
        }

        return $this->render('news/create.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}
