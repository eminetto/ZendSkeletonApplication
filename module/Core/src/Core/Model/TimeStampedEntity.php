<?php
/**
 * Entities with creation timestamp 
 * @package    Core\Model
 * @author     Elton Minetto<eminetto@coderockr.com>
 */
namespace Core\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Exception\InvalidArgumentException;

class TimeStampedEntity extends Entity
{
    /**
     * @var Datetime
     */
    protected $created;
    
    /**
     * @param array $data Dados
     * @return void
     */
    public function setData($data)
    {
        parent::setData($data);
        if (!$this->created) {
            $this->created = date('Y-m-d h:i:s');
        }
    }

    /**
     * @param array $data Dados
     * @return void
     */
    public function getData()
    {
        if (!$this->created) {
            $this->created = date('Y-m-d h:i:s');
        }
        return parent::getData();
    }
}