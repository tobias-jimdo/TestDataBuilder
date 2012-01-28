<?php
/**
 * easy way to build your objects
 */
class TestDataBuilder_ObjectBuilder extends TestDataBuilder_Builder
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $constructorArgs = array();

    /**
     * @var array
     */
    private $methodsToCall = array();

    /**
     * @var array
     */
    private $propertiesToSet = array();

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param array $constructorArgs
     * @return ObjectTestDataBuilder
     */
    public function with(array $constructorArgs)
    {
        $this->constructorArgs = $constructorArgs;
        return $this;
    }

    /**
     * @param string $method
     * @return ObjectTestDataBuilder
     */
    public function call($method)
    {
        $args = func_get_args();
        array_shift($args);
        $this->methodsToCall[$method] = $args;
        return $this;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return ObjectTestDataBuilder
     */
    public function set($property, $value)
    {
        $this->propertiesToSet[$property] = $value;
        return $this;
    }

    /**
     * @return object
     */
    public function build()
    {
        $object = $this->buildObject();
        $this->setProperties($object);
        $this->callMethods($object);
        return $object;
    }

    /**
     * @param object $object
     */
    private function callMethods($object)
    {
        foreach ($this->methodsToCall as $method => $args) {
            $methodReflection = new ReflectionMethod($this->class, $method);
            $methodReflection->invokeArgs($object, $args);
        }
    }

    /**
     * @param object $object
     */
    private function setProperties($object)
    {
        foreach ($this->propertiesToSet as $property => $value) {
            $propertyReflection = new ReflectionProperty($this->class, $property);
            $propertyReflection->setValue($object, $value);
        }
    }

    /**
     * @return object
     */
    private function buildObject()
    {
        $classReflection = new ReflectionClass($this->class);
        if (!$classReflection->getConstructor()) {
            $object = new $this->class;
            return $object;
        } else {
            $object = $classReflection->newInstanceArgs($this->constructorArgs);
            return $object;
        }
    }
}
