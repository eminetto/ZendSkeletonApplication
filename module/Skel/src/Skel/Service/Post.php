<?php
namespace Skel\Service;

use Core\Service\Service;
use Zend\Db\Sql\Select;

/**
 * Serviço responsável por fazer tratamento especial aos posts
 * 
 * @category Skel
 * @package Service
 */
class Post extends Service
{
    /**
     * Retorna um array com todos os comentários de um post
     * 
     * @param int $post_id  Id do post
     * @return array
     */
    public function getComments($post_id)
    {
        $posts = $this->getTable('Skel\Model\Post')->get($post_id)->toArray();
        //verifica se existem comments
        $posts['comments'] = $this->getTable('Skel\Model\Comment')
                                  ->fetchAll(null, "post_id = $post_id")
                                  ->toArray();
        return $posts;
    }
}