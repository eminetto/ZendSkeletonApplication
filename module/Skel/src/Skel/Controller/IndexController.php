<?php

namespace Skel\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Skel\Model\Post;

/**
 * Controlador que gerencia os posts
 * 
 * @category Skel
 * @package Controller
 * @author  Elton Minetto<eminetto@coderockr.com>
 */
class IndexController extends ActionController
{
    /**
     * Mostra os posts cadastrados
     * @return void
     */
    public function indexAction()
    {
        return new ViewModel(array(
            'posts' => $this->getTable('Skel\Model\Post')->fetchAll()
        ));
    }

    /**
     * Cria ou edita um post
     * @return void
     */
    public function saveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $post = new Post;
            $post->setData($data);
            $saved =  $this->getTable('Skel\Model\Post')->save($post);
            return $this->redirect()->toUrl('/skel/index');
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 0) {    
            return new ViewModel(array(
                'post' => $this->getTable('Skel\Model\Post')->get($id)
            ));
        }
    }

    /**
     * Exclui um post
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0) {
            throw new \Exception("Código obrigatório");
        }
        
        $this->getTable('Skel\Model\Post')->delete($id);
        return $this->redirect()->toUrl('/skel/index');
    }

}