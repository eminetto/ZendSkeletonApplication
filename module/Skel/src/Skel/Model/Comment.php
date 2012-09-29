<?php
namespace Skel\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Core\Model\TimeStampedEntity;

/**
 * Entidade Comment
 * 
 * @category Skel
 * @package Model
 */
class Comment extends TimeStampedEntity
{
    /**
     * Nome da tabela. Campo obrigatório
     * @var string
     */
    protected $tableName ='comment';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $post_id;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $email;


    /**
     * Configura os filtros dos campos da entidade
     *
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'post_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'body',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'EmailAddress',
                    ),
                ),
            )));

            //criado é um campo gerado na inserção
            //por isso não precisa de validação

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}