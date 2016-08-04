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
    private $tmplExt = ".phtml";
    private $_ = array();
    private $pageData = null;

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
    public function setTmplExt($fileExt)
    {
        $this->tmplExt = $fileExt;
    }

    public function loadTemplate(){
        $tpl = $this->template;
        $file = $this->tmplPath . $tpl . $this->tmplExt;
        $exists = file_exists($file);

        if ($exists){

            ob_start();
            if (in_array($this->tmplExt, array(".php", ".php5", ".phtml"))) {
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

    public function errorBox($errorType, $errorHead, $error) {
        $return = '
            <div class="alert '.$errorType.'">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>'.$errorHead.'</strong>
                '.$error.'
            </div>
        ';
        return $return;
    }

}