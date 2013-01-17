<?php
namespace Skel\Model;

use Core\Test\ModelTestCase;
use Skel\Model\Post;
use Skel\Model\Comment;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Model
 */
class PermissaoTest extends ModelTestCase
{
    public function testGetInputFilter()
    {
        $comment = new Comment();
        $if = $comment->getInputFilter();
 
        $this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
        return $if;
    }
 
    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(4, $if->count());
 
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('post_id'));
        $this->assertTrue($if->has('body'));
        $this->assertTrue($if->has('email'));
    }
    
    /**
     * @expectedException Core\Model\EntityException
     */
    public function testInputFilterInvalido()
    {
        $comment = new Comment();
        //email deve ser um e-mail válido
        $comment->email = 'email_invalido';
    }

    /**
     * Teste de inserção de um comment válido
     */
    public function testInsert()
    {
        $comment = $this->addComment();

        $saved = $this->getTable('Skel\Model\Comment')->save($comment);

        $this->assertEquals(
            'Comentário importante alert("ok");', $saved->body
        );
        $this->assertEquals(1, $saved->id);
        $this->assertNotNull($saved->created);
    }

    /**
     * @expectedException Zend\Db\Adapter\Exception\InvalidQueryException
     */
    public function testInsertInvalido()
    {
        $comment = new Comment();
        $comment->body = 'teste';

        $saved = $this->getTable('Skel\Model\Comment')->save($comment);
    }    

    public function testUpdate()
    {
        $tableGateway = $this->getTable('Skel\Model\Comment');
        $comment = $this->addComment();

        $saved = $tableGateway->save($comment);
        $id = $saved->id;

        $this->assertEquals(1, $id);

        $comment = $tableGateway->get($id);
        $this->assertEquals('eminetto@coderockr.com', $comment->email);

        $comment->email = 'eminetto@gmail.com';
        $updated = $tableGateway->save($comment);

        $comment = $tableGateway->get($id);
        $this->assertEquals('eminetto@gmail.com', $comment->email);

    }

    /**
     * @expectedException Zend\Db\Adapter\Exception\InvalidQueryException
     * @expectedExceptionMessage Statement could not be executed
     */
    public function testUpdateInvalido()
    {
        $tableGateway = $this->getTable('Skel\Model\Comment');
        $comment = $this->addComment();

        $saved = $tableGateway->save($comment);
        $id = $saved->id;

        $comment = $tableGateway->get($id);
        $comment->post_id = 10;
        $updated = $tableGateway->save($comment);
    }

    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Could not find row 1
     */
    public function testDelete()
    {
        $tableGateway = $this->getTable('Skel\Model\Comment');
        $comment = $this->addComment();

        $saved = $tableGateway->save($comment);
        $id = $saved->id;

        $deleted = $tableGateway->delete($id);
        $this->assertEquals(1, $deleted); //numero de linhas excluidas

        $comment = $tableGateway->get($id);
    }

    /**
     * Adiciona um novo post para o teste
     */
    private function addPost()
    {
        $post = new Post();
        $post->title = 'Apple compra a Coderockr';
        $post->body = 'A Apple compra a <b>Coderockr</b><br> ';
        $saved = $this->getTable('Skel\Model\Post')->save($post);

        return $saved;
    }

    /**
     * Adiciona um novo comentário para o teste
     */
    private function addComment() 
    {
        $post = $this->addPost();
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->body = 'Comentário importante <script>alert("ok");</script> <br> ';
        $comment->email = 'eminetto@coderockr.com';

        return $comment;

    }

}