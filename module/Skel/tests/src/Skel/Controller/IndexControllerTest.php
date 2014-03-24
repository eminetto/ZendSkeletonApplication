<?php

use Core\Test\ControllerTestCase;
use Skel\Controller\IndexController;
use Skel\Model\Post;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;


/**
 * @group Controller
 */
class IndexControllerTest extends ControllerTestCase
{
    /**
     * Namespace completa do Controller
     * @var string
     */
    protected $controllerFQDN = 'Skel\Controller\IndexController';

    /**
     * Nome da rota. Geralmente o nome do módulo
     * @var string
     */
    protected $controllerRoute = 'skel';

    
    public function test404()
    {
        $this->routeMatch->setParam('action', 'action_nao_existente');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testIndexAction()
    {
        // Cria posts para testar
        $postA = $this->addPost();
        $postB = $this->addPost();

        // Invoca a rota index
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request, $this->response);

        // Verifica o response
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // Testa se um ViewModel foi retornado
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        // Testa os dados da view
        $variables = $result->getVariables();

        $this->assertArrayHasKey('posts', $variables);

        // Faz a comparação dos dados
        $controllerData = $variables["posts"]->toArray();
        $this->assertEquals($postA->title, $controllerData[0]['title']);
        $this->assertEquals($postB->title, $controllerData[1]['title']);
    }  

    public function testSaveActionGetRequest()
    {
        $postA = $this->addPost();

        // Dispara a ação
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $postA->id);
        $result = $this->controller->dispatch($this->request, $this->response);

        // Verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // Testa se recebeu um ViewModel
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        // Verifica se foi encontrado um post
        $variables = $result->getVariables();
        $this->assertArrayHasKey('post', $variables);
        
        $controllerData = $variables["post"]->toArray();
        $this->assertEquals($postA->title, $controllerData['title']);
    }

    public function testSaveActionPostRequest()
    {
        // Dispara a ação
        $this->routeMatch->setParam('action', 'save');
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('title', 'Apple compra a Coderockr');
        $this->request->getPost()->set(
            'body', 'A Apple compra a <b>Coderockr</b><br> '
        );
        
        $result = $this->controller->dispatch($this->request, $this->response);
        // Verifica a resposta
        $response = $this->controller->getResponse();
        //a página redireciona, então o status = 302
        $this->assertEquals(302, $response->getStatusCode());

        //verifica se salvou
        $posts = $this->getTable('Skel\Model\Post')->fetchAll()->toArray();
        $this->assertEquals(1, count($posts));
        $this->assertEquals('Apple compra a Coderockr', $posts[0]['title']);
    }

    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Input inválido: title = 
     */
    public function testSaveActionInvalidPostRequest()
    {
        // Dispara a ação
        $this->routeMatch->setParam('action', 'save');
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', 'Apple compra a Coderockr');
        $this->request->getPost()->set('title', '');
        
        $result = $this->controller->dispatch($this->request, $this->response);
    }

    /**
     * @expectedException Exception
     */
    public function testSaveActionMissingFieldPostRequest()
    {
        // Dispara a ação
        $this->routeMatch->setParam('action', 'save');
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', 'Apple compra a Coderockr');
       
        $result = $this->controller->dispatch($this->request, $this->response);
    }     

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Código obrigatório
     */
    public function testInvalidDeleteAction()
    {
        $post = $this->addPost();
        // Dispara a ação
        $this->routeMatch->setParam('action', 'delete');

        $result = $this->controller->dispatch($this->request, $this->response);
        // Verifica a resposta
        $response = $this->controller->getResponse();
        //a página redireciona, então o status = 302
        $this->assertEquals(302, $response->getStatusCode());

        //verifica se salvou
        $posts = $this->getTable('Skel\Model\Post')->fetchAll()->toArray();
        $this->assertEquals(0, count($posts));
    }

    public function testDeleteAction()
    {
        $post = $this->addPost();
        // Dispara a ação
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $post->id);

        $result = $this->controller->dispatch($this->request, $this->response);
        // Verifica a resposta
        $response = $this->controller->getResponse();
        //a página redireciona, então o status = 302
        $this->assertEquals(302, $response->getStatusCode());

        //verifica se salvou
        $posts = $this->getTable('Skel\Model\Post')->fetchAll()->toArray();
        $this->assertEquals(0, count($posts));
    }

    private function addPost()
    {
        $post = new Post();
        $post->title = 'Apple compra a Coderockr';
        $post->body = 'A Apple compra a <b>Coderockr</b><br> ';

        $saved = $this->getTable('Skel\Model\Post')->save($post);
        return $saved;
    }
}
