<?php
require_once 'interface.Object.php';

class GenericObject implements Object_Interface{
    protected $_properties = array();
    public static $inst;
    protected $html;
    public $data;
    protected $action_name;
    protected $id = 0;

    public function __construct(){}

    public function  __set($name, $value)
    {
        $this->_properties[$name] = $value;
    }

    public function __get($name)
    {
        if( !isset($this->_properties[$name]) )
            return false;
            
        return $this->_properties[$name];
    }
                
    public function __isset($name)
    {
        return isset($this->_properties[$name]);
    }
                
    public function process()
    {
        return 'This is the default Object.';
    }
                
    public function render($immediate = false)
    {
        return __METHOD__;
    }
                
    public function one($id)
    {
        return __METHOD__;
    }
            
    public function search($search_term)
    {
        return __METHOD__;
    }
            
    public function listing($num)
    {
        return __METHOD__;
    }
            
    public function getData()
    {
        return $this->data;
    }
            
    public function setData(Array $data)
    {
        $this->data = $data;
    }
            
    public function save($data = array())
    {
        return __METHOD__;
    }
            
    public function delete($id, $type = 'appointment')
    {
        return __METHOD__;
    }
            
    public function getActionName()
    {
        return $this->action_name;
    }

    public static function getInstance()
    {
        if( !isset(self::$inst) )
            self::$inst = new self;

        return self::$inst; 
    }
            
    public function setID($id = '')
    {             
        if (!is_numeric($id))
            return false;

        $this->id = $id;
        return true;
    }
            
    public function getID()
    {
        if (!is_numeric($this->id))
            return false;

        return $this->id;
    }
}