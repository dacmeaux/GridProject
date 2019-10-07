<?php
require_once 'class.GenericObject.php';
require_once 'class.LayoutTemplate.php';
        
class Grid_Object extends GenericObject
{
    public static $inst;
    private $columns = array(4,3,2,1);
    private $styles = '';
    private $classname = 'default';
    private $no_content_classname = 'default-no-content';
    private $content = '';
    private $generate_css = false;
    private $debug = false;
    private $colors = array();

    // **************************************************
    // Constructor.
    // **************************************************
    public function __construct()
    {
        parent::__construct();
        $this->colors = range(0, 15);
    }

    public function process()
    {       
        $output = array();
        $columns = $this->columns;
        $content = $this->content;
                
        if( !$content )
            return 'No content specified. Content must be an array';
                
        if( !is_array($columns) )
        {
            if( !is_numeric($columns) )
                return 'The separators should be either an array of numbers or a single number';
                
            $columns = array($columns);
        }
                
        if( !is_array($content) )
            $content = array($content);
                
        $output[] = $this->addGrid($content, $columns);
        $this->addCss($content, $columns);

        $this->data = $output;

        return implode("\n", $output);
    }
        
    private function addGrid($content, $columns)
    {
        $count = 1;
        $output = array();

        foreach( $content as $_data )
        {
            // Create a new Grid template object
            $_tpl = new LayoutTemplate('grid.html');
            $_tpl->class = $this->classname;

            if( !$content )
                $_tpl->no_content_class = $this->no_content_classname;

            $_tpl->content = $_data;
            $_tpl->number = $count;
            $_tpl->clearers = $this->addClearer(array_keys($columns), $count);
            $output[] = $_tpl->getHtml(true);
            unset($_tpl);
            $count++;
        }
                
        return implode("\n", $output);
    }

    public function setContent(Array $content)
    {
        $this->content = $content;
    }

    public function setColumns(Array $columns)
    {
        $this->columns = $columns;
    }

    public function setClassName($classname)
    {
        $this->classname = $classname;
    }
        
    function addClearer($columns, $count)
    {
        $output = array();

        // Add grid separators as needed
        foreach( $columns as $column_at )
        {
            if( !is_numeric($column_at) )
                return 'The columns should be numbers.';
                    
            if( $count % $column_at == 0 )
            {
                $clearer = new LayoutTemplate('grid-clearer.html');
                $clearer->classname = 'grid-'. $column_at .'-clearer '. $this->classname. '-grid-'. $column_at .'-clearer';
                $output[] = $clearer->getHtml(true);
                unset($clearer);
            }
        }
                    
        return implode("\n", $output);
    }

    function addDividers($columns, $count)
    {
        if( $this->get('add_dividers') == 'no' )
            return '';
                
        $output = array();
        $divider = new LayoutTemplate('grid-divideline.tpl');
        $divider->class = '';

        // Add grid separators as needed
        foreach( $columns as $column_at )
        {
            if( !is_numeric($column_at) )
                return 'The separators should be numbers.';
                    
            if( $count % $column_at == 0 )
                $divider->class .= ' grid-'. $column_at .'-divideline';
        }
                
        $output[] = $divider->getHtml(true);
        unset($divider);
                        
        return implode("\n", $output);
    }

    /*
     * Generates CSS for this Grid
     *
     * @param bool generate
     * @access public
     * @return void
     * @since method available since version 2.0
     */
    public function generateCss($generate = true)
    {
        $this->generate_css = $generate;
    }

    private function addCss($content, $columns)
    {
        $css = array();
        $count = 0;
        $class_rules = array();
        $color = '';

        foreach( $content as $_data )
        {
            $x = 0;

            if( $this->debug )
            {
                $color = '#'. dechex($this->colors[rand(0, 15)]) .
                    dechex(rand(0, 15)) .
                    dechex(rand(0, 15)) .
                    dechex(rand(0, 15)) .
                    dechex(rand(0, 15)) .
                    dechex(rand(0, 15)) .';';
            }

            foreach( $columns as $column_at=>$viewport_width)
            {
                $other_columns = $columns;
                unset($other_columns[$column_at]);

                if( !is_numeric($column_at) )
                    return 'The separators should be numbers.';

                $media_tpl = new LayoutTemplate('media.txt');
                $media_tpl->width = ($x == 0 ? 'min-width: ' : 'max-width: ') . $viewport_width;
                $_rules = '.'. $this->classname .'-grid{width:'. round(100 / $column_at, 2) .'%;}' ."\n";
                $_rules .= '.'. $this->classname .'-grid-'. $column_at .'-clearer{display:block;}';

                foreach( $other_columns as $other_column_at=>$other_column_viewport_width )
                {
                    $_rules .= '.grid-'. $other_column_at .'-clearer{display: none;}';
                }

                $media_tpl->class_rules = $_rules;

                if( in_array($_rules, $class_rules) )
                    continue;
                else
                    $css[] = $media_tpl->getHtml(true);

                $class_rules[] = $_rules;

                $x++;
            }

            $count++;

            if( $this->debug )
                $this->styles .= '.'. $this->classname .'-grid-'. $count .'{background-color:'. $color .';}';
        }

        $this->styles .= "\n". implode("\n", $css) ."\n";
    }

    public function getCss()
    {
        return $this->styles;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
        
    public static function getInstance()
    {
        if( !isset(self::$inst) )
            self::$inst = new self;

        return self::$inst; 
    }
}