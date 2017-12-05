<?php

namespace JD\JolydaysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostsController extends Controller
{
  // path /
  public function indexAction($page)
  {
    // if valid page number, show next lists of posts
    // else show error
    if ($page >= 1) {
      return new Response('All posts are here');
    }else{
      throw new NotFoundHttpException('Page '.$page.' does not exist');
    } 
  }

  // path /post/$post_id_to_view
  public function viewAction($post_id)
  {
    return new Response('Single page for the post with id ' . $post_id);
  }

  // path /add
  public function addAction(Request $request)
  {
    // Assuming that datas were collected from forms
    if ($request->isMethod('POST')) {
      
      // TODO: save post to db (...) and get its id

      return new Response('Post has been successfully added');
    }

    // if /add url was manually inserted, show the add form
    return new Response('Form for adding posts');
  }

  // path /edit/$post_id_to_edit
  public function editAction($post_id, Request $request)
  {
    if ($request->isMethod('POST')) {

      // TODO: alter post to db (...) and get its id to view it

      return new Response('The post with id '.$post_id.' has been successfully edited');
    }

      // if /edit/$post_id url was manually inserted, show the edit form
      return new Response('Form for editing posts');
      
  }

  // path /delete/$post_id_to_delete
  public function deleteAction($post_id)
  {
    // TODO: delete post with post_id id to db
    //show the page with a delete button
    return new Response('Form with a delete button');
  }
}