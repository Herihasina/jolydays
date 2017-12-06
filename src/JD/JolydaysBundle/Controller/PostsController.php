<?php

namespace JD\JolydaysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JD\JolydaysBundle\Entity\Posts;

class PostsController extends Controller
{
  // path /
  public function indexAction($page)
  {
    // sample datas for populating homepage
    $all_posts = array(
      array(
        'title'   => 'First post',
        'id'      => 1),
      array(
        'title'   => 'Another post',
        'id'      => 2)
    );    

    // Call index.html.twig and send posts parameter
    return $this->render('JDJolydaysBundle:Posts:index.html.twig', array(
      'posts' => $all_posts
    ));
  }

  // path /post/$post_id, $post_id is from url
  public function viewAction($post_id)
  {
    // sample datas for populating the single page
    $a_post = array(
      'title'   => 'Random post',
      'id'      => $post_id,
      'author'  => 'John Doe',
      'content' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
      'date'    => new \Datetime()
    );

    // Call view.html.twig and send post parameter
    return $this->render('JDJolydaysBundle:Posts:view.html.twig', array(
      'post' => $a_post
    ));
  }

  // path /add
  public function addAction()
  {
    $p = new Posts();
    // show the add form
    return $this->render('JDJolydaysBundle:Posts:add.html.twig');
  }

  // path /edit/$post_id_to_edit
  public function editAction($post_id)
  {

    // show the edit form
    return $this->render('JDJolydaysBundle:Posts:edit.html.twig', array('post_id' => $post_id ));
      
  }

  // path /delete/$post_id_to_delete
  public function deleteAction($post_id)
  {
    // TODO: delete post with post_id id to db
    //show the page confirming deletion
    return $this->render('JDJolydaysBundle:Posts:delete.html.twig', array('post_id' => $post_id ));
  }
}