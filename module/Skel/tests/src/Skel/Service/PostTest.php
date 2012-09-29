<?php
namespace Skel\Service;

use Core\Test\ServiceTestCase;
use Skel\Model\Post;
use Skel\Model\Comment;
use Zend\Cache\Storage\Adapter\Apc;

/**
 * @group Service
 */
class PostTest extends ServiceTestCase
{
    public function testValid()
    {
        $post = $this->addPost();
        $commentA = $this->addComment($post);
        $commentB = $this->addComment($post);

        $postService = $this->getService('Skel\Service\Post');

        $this->assertEquals('Skel\Service\Post', get_class($postService));

        $posts = $postService->getComments($post->id);

        $this->assertEquals(1, $posts['id']);
        $this->assertEquals(2, count($posts['comments']));

        $this->assertEquals($post->title, $posts['title']);
        $this->assertEquals($commentA->email, $posts['comments'][0]['email']);
    }

    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Could not find row 1
     */
    public function testInvalidPost()
    {
        $postService = $this->getService('Skel\Service\Post');

        $posts = $postService->getComments(1);

        $this->assertEquals(0, count($posts));
    }

    public function testPostNoComments()
    {
        $post = $this->addPost();

        $postService = $this->getService('Skel\Service\Post');

        $posts = $postService->getComments($post->id);

        $this->assertEquals(1, $posts['id']);
        $this->assertEquals(0, count($posts['comments']));
    }    

    private function addPost()
    {
        $post = new Post();
        $post->title = 'Apple compra a Coderockr';
        $post->body = 'A Apple compra a <b>Coderockr</b><br> ';

        $saved = $this->getTable('Skel\Model\Post')->save($post);
        
        return $saved;
    }

    private function addComment($post) 
    {
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->body = 'Comentário importante <script>alert("ok");</script> <br> ';
        $comment->email = 'eminetto@coderockr.com';

        $saved = $this->getTable('Skel\Model\Comment')->save($comment);
        
        return $saved;

    }    

}