<?php

namespace BufferedCache;

class ChainList implements \Iterator, \ArrayAccess
{
    
    /**
     * @var \BufferedCache\ChainElement
     */
    private $first;
    
    /**
     * @var \BufferedCache\ChainElement
     */
    private $last;
    
    /**
     * @var array
     */
    private $map = array();
    
    /**
     * @var int
     */
    private $count;
    
    /**
     * @var \BufferedCache\ChainElement
     */
    private $cursor;

    public function current()
    {
        return $this->cursor->getData();
    }

    public function key()
    {
        return $this->cursor->getKey();
    }

    public function next()
    {
        $this->cursor = $this->cursor->getNext();
    }

    public function offsetExists($key)
    {
        return isset($this->map[$key]);
    }

    public function offsetGet($key)
    {
        $element = $this->get($key)->remove();
        
        if ($this->last == $element) {
            $this->last = $element->getPrev();
        }
        
        $element->setPrev(null)->setNext($this->first);
        if ($this->first) {
            $this->first->setPrev($element);
        }
        $this->first = $element;
        
        return $element->getData();
    }

    public function offsetSet($key, $value)
    {
        $element = new ChainElement($key, $value);
        $element->setNext($this->first);
        if ($this->first) {
            $this->first->setPrev($element);
        }
        $this->first = $element;
        if ($this->last == null) {
            $this->last = $element;
        }
        $this->map[$key] = $element;
        $this->count++;
    }

    public function offsetUnset($key)
    {
        $element = $this->get($key)->remove();
        
        if ($this->first == $element) {
            $this->first = $element->getNext();
        }
        if ($this->last == $element) {
            $this->last = $element->getPrev();
        }
        
        $this->count--;
    }

    public function rewind()
    {
        $this->cursor = $this->first;
    }

    public function valid()
    {
        return $this->cursor != null;
    }
    
    /**
     * @return \BufferedCache\ChainElement
     */
    public function getLast()
    {
        return $this->last;
    }
    
    /**
     * @return \BufferedCache\ChainElement
     */
    public function getFirst()
    {
        return $this->first;
    }
    
    /**
     * @param string $key
     * @return \BufferedCache\ChainElement
     */
    private function get($key)
    {
        return $this->map[$key];
    }
    
    /**
     * @return int
     */
    public function length()
    {
        return $this->count;
    }
}