<?php

namespace BufferedCache;

class ChainElement
{
    
    /**
     * @var mixed
     */
    private $data;
    
    /**
     * @var string
     */
    private $key;
    
    /**
     * @var \BufferedCache\ChainElement
     */
    private $next;
    
    /**
     * @var \BufferedCache\ChainElement
     */
    private $prev;
    
    /**
     * @param string $key
     * @param mixed $data
     */
    public function __construct($key, $data)
    {
        $this->key = $key;
        $this->setData($data);
    }
    
    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * @param mixed $data
     * @return \BufferedCache\ChainElement
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * @return \BufferedCache\ChainElement
     */
    public function getNext()
    {
        return $this->next;
    }
    
    /**
     * @return \BufferedCache\ChainElement
     */
    public function getPrev()
    {
        return $this->prev;
    }
    
    /**
     * @param \BufferedCache\ChainElement $element
     * @return \BufferedCache\ChainElement
     */
    public function setNext(\BufferedCache\ChainElement $element = null)
    {
        $this->next = $element;
        return $this;
    }
    
    /**
     * @param \BufferedCache\ChainElement $element
     * @return \BufferedCache\ChainElement
     */
    public function setPrev(\BufferedCache\ChainElement $element = null)
    {
        $this->prev = $element;
        return $this;
    }
    
    /**
     * @return \BufferedCache\ChainElement
     */
    public function remove()
    {
        if ($this->prev) {
            $this->prev->setNext($this->next);
        }
        if ($this->next) {
            $this->next->setPrev($this->prev);
        }
        return $this;
    }
}