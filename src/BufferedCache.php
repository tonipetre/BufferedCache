<?php

namespace BufferedCache;

class BufferedCache
{
    
    private $maxSize = 1;
    
    /**
     * @var \BufferedCache\ChainList
     */
    private $chain;
    
    /**
     * @param int $maxSize
     */
    public function __construct($maxSize)
    {
        $this->maxSize = max(array($maxSize, 1));
        $this->chain = new ChainList();
    }
    
    /**
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->chain[$key]);
    }
    
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }
        
        return $this->chain[$key];
    }
    
    /**
     * @param string $key
     * @param mixed $value
     * @return \BufferedCache\BufferedCache
     */
    public function set($key, $value)
    {
        $this->chain[$key] = $value;
        
        while ($this->chain->length() > $this->maxSize) {
            $last = $this->chain->getLast();
            if ($last) {
                unset($this->chain[$last->getKey()]);
                unset($last);
            }
        }
        
        return $this;
    }
    
    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->chain;
    }
    
}