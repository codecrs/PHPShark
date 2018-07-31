<?php 

namespace view{
    class Html{

        private $_tag; 
        private $_param;
        private $_content;
        private $content;

        public function tag($tag, $param){
            $this->content = '';
            $this->_tag = $tag;
            $this->_param = $param;
            return $this;
        }

        public function content($content){
            $tag = view\Html::$_tag;
            $param = view\Html::$_param;
            $content = _t($content);
            $this->content .= "<{$tag} {$param}>{$content}</{$tag}>";
            return $this;
        }

        public function render(){
            return $this->content;
        }

    }

}