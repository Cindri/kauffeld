<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:19
 */
class View
{
    private $tmplPath = "templates/";
    private $template = "";
    private $fileExt = ".phtml";
    private $_ = array();

    public function assign($key, $value) {
        $this->_[$key] = $value;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param string $fileExt
     */
    public function setFileExt($fileExt)
    {
        $this->fileExt = $fileExt;
    }

    public function loadTemplate(){
        $tpl = $this->template;
        $file = $this->tmplPath . $tpl . $this->fileExt;
        $exists = file_exists($file);

        if ($exists){

            ob_start();
            if (in_array($this->fileExt, array(".php", ".php5", ".phtml"))) {
                include $file;
                $output = ob_get_contents();
            }
            else {
                $output = file_get_contents($file);
            }

            ob_end_clean();

            return $output;
        }
        else {
            return 'could not find template:  '.$file;
        }
    }

}