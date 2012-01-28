<?php
 
class TestDataBuilder_ArrayBuilder extends TestDataBuilder_CustomBuilder implements ArrayAccess
{
    /**
     * @param array $fields
     */
    public function __construct(array $fields = array())
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function build()
    {
        return $this->fields;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetGet($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->with($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }
}
