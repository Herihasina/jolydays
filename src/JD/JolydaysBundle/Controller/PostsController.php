<?php

namespace JD\JolydaysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;
use JD\JolydaysBundle\Entity\Posts;
use JD\JolydaysBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

use JD\JolydaysBundle\Form\PostsType;
use JD\JolydaysBundle\Form\PostsEditType;
use JD\JolydaysBundle\Repository\CategoryRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class PostsController extends Controller
{
  // path /
  public function indexAction($page)
  {
    // get all posts
    $repository = $this->getDoctrine()->getManager()->getRepository('JDJolydaysBundle:Posts');
    $posts = $repository->findAll();    

    return $this->render('JDJolydaysBundle:Posts:index.html.twig', array(
      'page_title' => 'All posts',
      'posts' => $posts
    ));
  }

  // path /post/$post_id, $post_id is from url
  public function viewAction($post_id)
  {
    $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('JDJolydaysBundle:Posts');

    $post = $repository->find($post_id); 
    $categs = $post->getCategories();
    if ( null !== $post){
      // Call view.html.twig and send post parameter
      return $this->render('JDJolydaysBundle:Posts:view.html.twig', array(
        'post' => $post,
        'categories' => $categs
      ));
    }else{
      throw new NotFoundHttpException('This post does not exist');
    }
    
  }

  // path /add
  public function addAction(Request $request)
  {
    $post = new Posts();

    // create the form
    $form = $this->createForm(PostsType::class, $post);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $request->getSession()->getFlashBag()->add('popup', 'Post added');
        return $this->redirectToRoute('jd_jolydays_view', array(
          'post_id' => $post->getId()
          ));
      }

    }

    return $this->render('JDJolydaysBundle:Posts:add.html.twig', array(
      'form' => $form->createView()
    ));
  }

  // path /edit/$post_id_to_edit
  public function editAction($post_id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository('JDJolydaysBundle:Posts')->find($post_id);

    if (null === $post) {
      throw new NotFoundHttpException("This post does not exist");
    }

    $form = $this->createForm(PostsEditType::class, $post);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->flush();

      $request->getSession()->getFlashBag()->add('popup', 'Post edited');

      return $this->redirectToRoute('jd_jolydays_view', array(
          'post_id' => $post_id
          ));
    }
    
    // show the edit form
    return $this->render('JDJolydaysBundle:Posts:edit.html.twig',array(
      'post_id'=> $post->getId(),
      'form' => $form->createView()
      ));
      
  }

  // path /delete/$post_id_to_delete
  public function deleteAction($post_id, Request $request)
  {
    // TODO: delete post with post_id id to db
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository('JDJolydaysBundle:Posts')->find($post_id);

    if (null === $post) {
      throw new NotFoundHttpException("This post does not exist");
    }

    if ($request->isMethod('POST')) {
      $em->remove($post);
      $em->flush();

      $request->getSession()->getFlashBag()->add('popup', "Post successfully deleted.");

      return $this->redirectToRoute('jd_jolydays_homepage');
    }

    //show the page confirming deletion
    return $this->render('JDJolydaysBundle:Posts:delete.html.twig', array('post_id' => $post_id ));
  }

  public function menuAction()
  {
    // Get all categories
    $repository = $this->getDoctrine()->getManager()->getRepository('JDJolydaysBundle:Category');
    $categories = $repository->findAll();
    // Pass parameters to the view
    return $this->render('JDJolydaysBundle:Posts:categories.html.twig', array(
        'categories' => $categories
      ));
  }

  public function postsInCatAction($cat_name, Request $request) {
    $cat_name = $request->attributes->get('cat_name');
    
    $categories = $this
                  ->getDoctrine()
                  ->getManager()
                  ->getRepository('JDJolydaysBundle:Category');
    $category = $categories->findOneBy(
        array('name' => $cat_name)
      );

    $posts = $category->getPosts(); 
    
    return $this->render('JDJolydaysBundle:Posts:index.html.twig', array(
      'page_title' => 'All posts in '.$cat_name.' category',
        'posts' => $posts
      ));
  }

  public function searchAction(Request $request)
  {
    $search = NULL; 
    $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('jd_jolydays_result', array('search' => $search) ))
        ->add('search', SearchType::class)
        ->add('Find', SubmitType::class)
        ->getForm();
    
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
    { 
      $search = $request->request->get('form')['search'];

      $repository = $this
                  ->getDoctrine()
                  ->getManager()
                  ->getRepository('JDJolydaysBundle:Posts');
                  
      $query = $repository->createQueryBuilder('p')
        ->where('p.title LIKE :result_title')
        ->setParameter('result_title', '%'.$search.'%')

        ->orWhere('p.author LIKE :result_author')
        ->setParameter('result_author', '%'.$search.'%')

        ->orWhere('p.content LIKE :result_content')
        ->setParameter('result_content', '%'.$search.'%')

        ->getQuery(); 

      $query = $query->getResult();

      return $this->render('JDJolydaysBundle:Posts:index.html.twig', array(
        'page_title' => 'Search result for "'.$search.'" keyword',
        'posts' => $query
        ));
    }   

    return $this->render('JDJolydaysBundle:Posts:resultForm.html.twig', array(
      'form' => $form->createView()
    ));


  }


}