<?php

namespace yii\mongodb;

use Iterator;
use MongoDB\Driver\Cursor;
use DBStorage\Codec\CodecInterface;
use MongoDB\Driver\CursorInterface;

/**
 * proxy Cursor for \MongoDB\Driver\Cursor
 */
class CursorProxy implements Iterator, CursorInterface
{
    /**
     * codec
     *
     * @var CodecInterface
     */
    private $_codec;
    /**
     * original Cursor
     *
     * @var \MongoDB\Driver\Cursor
     */
    private $_cursor;

    public function __construct(Cursor $cursor, CodecInterface $codec)
    {
        $this->_cursor = $cursor;
        $this->_codec = $codec;
    }

    public function current()
    {
        if ($val = $this->_cursor->current()) {
            return $this->_codec->encode($val);
        }

        return $val;
    }

    public function key()
    {
        return $this->_cursor->key();
    }

    public function next()
    {
        return $this->_cursor->next();
    }

    public function rewind()
    {
        return $this->_cursor->rewind();
    }

    public function valid()
    {
        return $this->_cursor->valid();
    }

    public function getId()
    {
        return $this->_cursor->getId();
    }

    public function getServer()
    {
        return $this->_cursor->getServer();
    }

    public function isDead()
    {
        return $this->_cursor->isDead();
    }

    public function setTypeMap(array $typemap)
    {
        return $this->_cursor->setTypeMap($typemap);
    }

    public function toArray()
    {
        $res = [];
        foreach ($this->_cursor->toArray() as $v) {
            $res[] = $this->_codec->decode($v);
        }
        return $res;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->_cursor, $name], $arguments);
    }
}
