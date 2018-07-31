<?php
namespace core; 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  Qmvc - Quick PHP MVC 
 *  Developed by Contempative Radical Solutions Consulting Private Ltd. 
 *  Project Initiative By Ankit Kumar
 *  http://www.contemplativeradicals.com
 *  
 *  @copyright  Contempative Radical Solutions Consulting Private Ltd. 
 *  @link
 *  @since      1.0.0
 *  @license
 *  
 * Change Logs
 * *******************************************************************
 * 
 * *******************************************************************
 **/

use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;
use core\view\input as input;
use core\view\tags as tags;
use \Ui;

final class View{
	protected $title;
	protected $validator;
	protected $_bodyContent;
	private $_variable;
	private $_template;
	private $_templateVarName;
	private $_templateVarValue;
	private $_viewPath;
	private $_funct_called = '';
	public $host;
	public $uri;
	public $request;
	public $route;
	public $validation;
	public $url;
	public $viewPath;
	public $section = array();
	public $layout;
	public $islink;
	public $paginate;
	public $component;
	public $ui;
	public $session;
	/******************************HELPER ******************************************/
	private $content = '';
	private $_form = '';
	private $_select = '';
	private $_table = '';
	private $_viewport = null;
	private $_charset = null;
	private $_rowClass = '';
	private $_colClass ='';
	private $_gridClass = '';

	//***************************************************************
	//Description: load the View method called from the controller method.
	//methods available to us in view class are as follows: 
	//$this->host 
	//$this->uri  
	//$this->request 
	//$this->route s
	//$this->url 
	//***************************************************************/
	public function __construct(){
		$this->paginate   = new pages\Pagination();
		$this->ui         = new Ui();
	}

	//--------------------------------------------------------------------------------
	// PUBLIC PAGE FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: Depricated.
	//***************************************************************/
	public function is_link_name(string $name){
		return $this->islink = $name;
	} 

	//***************************************************************
	//Description: Depricated.
	//***************************************************************/
	public function islink(string $name){
		if($name === $this->islink){
			return true;
		}else{
			return false;
		}
	}

	//***************************************************************
	//Description: Helper function for page title.
	//***************************************************************/
	public function title(string $name){
		return $this->title = $name;
	}

	//***************************************************************
	//Description: inc($page) function directly points to the common folder inside 
	//the view folder. This function can be used inside the controller, but most preferably 
	//called from the another file in the view itself. This technique is designed to follow the
	//modulerization technique for repeating pages or a part of any page. 
	//***************************************************************/
	public function inc(string $page){
		$this->pageRender($page);
	}

	//***************************************************************
	//Description: render($page) function directly points to all the file inside the 
	//page folder of the view file. This function renders the full page called from the controller method. 
	//***************************************************************/
	public function render(string $page){
		//$this->pageRender($page,'pages');
		$this->_template = "{$page}_view";
	}

	//***************************************************************
	//Description: Function to render the page with variable header, and footers
	//if noInclude is true, the header and footer will be required fields.
	//***************************************************************/
	public function layout(int $name, bool $noInclude = false, string $header = NULL, string $footer = NULL){
		if($noInclude == true){
			$this->pageRender($header);
			$this->pageRender($name, $this->_getViewPath());
			$this->pageRender($footer);
		}
		else{	 
			$this->pageRender($name,$this->_getViewPath());
		}
	}

	//***************************************************************
	//Description: Function for page render 
	//(INTERNAL USE)
	//***************************************************************/
	public function __toString(){
		$this->pageRender();
		return '';
	}

	//--------------------------------------------------------------------------------
	// PUBLIC FOLDER FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: Helper function for CSS link tag.
	//***************************************************************/

	/**
	 * Gets the MVC CSS path from the public accessible web folder
	 */
	public function css(string $namespace,string $link,array $extra = []){
		$env = strtolower(ENVIRONMENT);
		$link = trim($link);
		if($env == 'production' || $env == 'testing'){
			$css = "<LINK href=\"public/assets/{$namespace}/{$link}.css\" rel=\"stylesheet'/>";
		}

		if($env == 'production' || $env == 'testing' && !empty($extra)){
			$css = "<LINK href=\"public/assets/{$namespace}/{$link}.css\" rel=\"stylesheet\" ";
			foreach($extra as $key => $value){
				$css .= "{$key}='{$value}' ";
			}
			$css .= "/>";
		}

		$dev_link = $this->link();
		if($env == 'development' && $extra == NULL)
		{
			$css = "<LINK href\"{$this->link()}public/assets/{$namespace}/{$link}.css\" rel=\"stylesheet\" />";
		}

		if($env == 'development' && $extra !== NULL){
			$css = "<LINK href=\"{$this->link()}public/assets/{$namespace}/{$link}.css\" rel=\"stylesheet\" ";
			foreach($extra as $key => $value){
				$css .= " {$key}=\"{$value}\" ";
			}
			$css .= "/>";
		}
		echo $css;
	}

	//***************************************************************
	//Description: Helper function for JS script tag.
	//***************************************************************/
	public function js(string $namespace, string $link,array $extra = []){
		$env = strtolower(ENVIRONMENT);
		if($env == 'production' || $env == 'testing' && $extra == NULL){
			$js = "<SCRIPT src=\"public/assets/{$link}.js\"></script>";
		}

		if($env == 'production' || $env == 'testing' && $extra != NULL){
			$js = "<SCRIPT src=\"public/assets/{$link}.js\" ";
			foreach($extra as $key => $value){
				$js .= "{$key}=\"{$value}\" ";
			}
			$js .= "></SCRIPT>";
		}

		if($env == 'development' && $extra == NULL){
			$js = "<SCRIPT src=\"{$this->link()}public/assets/{$namespace}/{$link}.js\"></script>";
		}

		if($env == 'development' && $extra !== NULL){
			$js = "<SCRIPT src=\"{$this->link()}public/assets/{$namespace}/{$link}.js\" " ;  
			foreach($extra as $key => $value){
				$js .= " {$key}=\"{$value}\" ";
			}
			$js .= "></SCRIPT>";
		}
		echo $js;

	}

	//--------------------------------------------------------------------------------
	// PUBLIC LINK FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: Helper function for API declarations (GOOGLE MAPS).
	//***************************************************************/
	public function API(string $component_path, bool $async = false, bool $defer = false){
		$js = '<SCRIPT ';
		if($async == true){
			$js .= 'async '; 
		}
		if($defer == true){
			$js .= 'defer '; 
		}
		$comp_path = trim($component_path);
		$js .= "src=\"{$comp_path}\"></SCRIPT> ";
		echo $js;
	}

	//***************************************************************
	//Description: Helper function for cdn (content delvery network) declarations.
	//***************************************************************/
	public function cdn(string $componentType, string $component_path, array $extra = []){
		$cdn = '';
		$component_path = trim($component_path);
		if(trim($componentType) == 'css'){
			$cdn = "<LINK href=\"{$component_path}\"";
			if(!empty($extra) && is_array($extra)){
				foreach($extra as $key => $value){
					$cdn .= "{$key}=\"{$value}\" ";
				}
			}
			$cdn .=	"/>";
			echo $cdn;
		}

		if(trim($componentType) == 'js'){
			$cdn = "<SCRIPT src=\"{$component_path}\" ";
			if(!empty($extra) && is_array($extra)){
				foreach($extra as $key => $value){
					$cdn .= "{$key}=\"{$value}\" ";
				}
			}
			$cdn .= "></SCRIPT>";
			echo $cdn;
		}
	}

	//--------------------------------------------------------------------------------
	// PUBLIC PAGE OBJECT FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: Helper function for img tag.
	//***************************************************************/
	public function img(string $namespace,string $name, array $extra = []){
		$env = strtolower(ENVIRONMENT);
		$name = trim($name);
		if($env == 'production' || $env == 'testing'){
			$img = "<IMG src=\"/public/assets/{$namespace}/{$name}\" ";
			if (!empty($extra)){
				foreach($extra as $key => $value){
					$img .= " {$key}=\"{$value}\" ";
				}
			}	
			$img .= "/>";
			echo $img;
		}

		if($env == 'development'){
			$img = "<IMG src=\"{$this->link()}public/assets/{$namespace}/{$name}\" ";
			if (!empty($extra)){
				foreach($extra as $key => $value){
					$img .= " {$key}=\"{$value}\" ";
				}
			}	
			$img .= "/>";
			echo $img;
		}
	}

	//***************************************************************
	//Description: Helper function for icon tag.
	//***************************************************************/
	public function icon(string $namespace,string $path,array $extra = []){
		$env = strtolower(ENVIRONMENT);
		$path = trim($path);
		$icon = '';
		if($env == 'production' || $env == 'testing'){
			if($size != null){
				$icon = "<LINK href=\"public/assets/{$namespace}/{$path}\" ";
				if(!empty($extra) && is_array($extra)){
					foreach($extra as $key => $value){
						$icon .= " {$key}=\"{$value}\" ";
					}
				}
				$icon .= "/>";
				echo $icon;
			}
		}

		if($env == 'development'){
			if($size != null){
				$icon = "<LINK href=\"{$this->link()}public/assets/{$namespace}/{$path}\" ";
				if(!empty($extra) && is_array($extra)){
					foreach($extra as $key => $value){
						$icon .= "{$key}='{$value}' ";
					}
				}
				$icon .= "/>";
				echo $icon;
			}
		}
	}

	//--------------------------------------------------------------------------------
	// PUBLIC PAGE VARIABLE FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: Helper function for page variables between controller
	//and view.
	//***************************************************************/
	// Example: $this->view->set('res',$arr); //Within the controller;
	public function set(string $name,$value){
		$this->_templateVarName = $name;
		$this->_templateVarValue = $value;

		if(isset($this->_templateVarName) && isset($this->_templateVarValue))
			$this->__assignVars($this->_templateVarName, $this->_templateVarValue);
	}

	// Example: $this->get('res'); //Within the View;
	public function get(string $name){
		// $this->_templateVars = $name;
		if(isset($this->_variables[$name])){
			return $this->_variables[$name];
		} else {
			$this->_variables[$name] = NULL;
			return $this->_variables[$name];
		}   
	}

	private function __assignVars(string $name,$value){
		$this->_variables[$name] = $value;
	}

	//--------------------------------------------------------------------------------
	// PUBLIC PAGE META FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: Helper function for meta tag
	//***************************************************************/
	public function meta(array $extra = []){
		$meta = '';
		$meta = '<META ';
		if(!empty($extra) && is_array($extra)){
			foreach($extra as $key => $value){
				$meta .= " {$key}='{$value}' ";
			}
		}
		$meta .= '">';
		echo $meta;
	}

	//--------------------------------------------------------------------------------
	// PRIVATE FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: pageRender($name,$subFolder = NULL) is a private function for 
	//rendring the full page/content, also handeling the sessions and cookies.
	//***************************************************************/
	private function pageRender(string $name, string $subFolder = NULL) {		
		// extract the variables for view pages
		if(!empty($this->variables)){
			extract($this->variables,EXTR_PREFIX_SAME,"wddx");
		}
		// the view path
		if($subFolder != NULL){
			$path = pages\Page::UrlContent('~/'.trim($subFolder).trim($name).'.php');
		} else {
			$path = pages\Page::templateContent('~/'.trim($name).'.php');
		}
		// start buffering
		ob_start();
		\Session::init();
		// render page content
		require_once($path);
		// get the body contents
		$this->_bodyContent = ob_get_contents();
		// clean the buffer
		ob_end_clean();
		// check if we have any layout defined
		if(!empty($this->layout) && (Page::isAjax())){
			// we need to check the path contains core prefix (~)
			$this->layout = pages\Page::UrlContent($this->layout);
			// start buffer 
			(utils\Config::get('project/code_minify') == 'on') ? ob_start("sanitize_output") : ob_start();
			// include the template
			require_once($this->layout);
		}else{
			(utils\Config::get('project/code_minify') == 'on') ? ob_start("sanitize_output") : ob_start();
			ob_start();
			// just output the content
			echo $this->_bodyContent;
		}
		// end buffer
		ob_end_flush();
	}

	//***************************************************************
	//Description: renderBody() is private function for rendering the body of the page. 
	//***************************************************************/
	private function renderBody(){
		// if we have content, then deliver it
		if(!empty($this->_bodyContent)){
			return $this->_bodyContent;
		}
	}

	//***************************************************************
	//Description: renderSection($section) is a private function for rendering the page
	//of the view file. 
	//***************************************************************/
	private function renderSection(section $section){
		if(!empty($this->section) && array_key_exists($section, $this->section)){
			return $this->section[$section];
		}
	}

	//***************************************************************
	//Description: link($link = null) function is similar to URL function 
	//in the core library. This is an alias function written for the ease of 
	//understanding while writing the view functions.
	//***************************************************************/
	/**
	 *  call url link for form & anchor.
	 */
	public function link(string $link = null){
		$ret = url($link);
		return $ret;
	}

	public function formlink(string $link = null){
		$ret = url($link);
		echo $ret;
	}

	//***************************************************************
	//Description: 
	//***************************************************************/
	public function closeTemplate($google_key = 'UA-XXXXX-Y'){
		$footer  = "<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->";
		$footer .= "<SCRIPT>";
		$footer .= "window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;";
		$footer .= "ga('create','{$google_key}','auto');ga('send','pageview')";
		$footer .= "</SCRIPT>";
		$footer .= '<SCRIPT src="https://www.google-analytics.com/analytics.js" async defer></SCRIPT>';
		$footer .= '</BODY></HTML>';
		echo $footer;

	}

	//***************************************************************
	//Description: Helper function for CSS link tag.
	//***************************************************************/

	/**
	 * UI Vendor - Stylesheets - public/vendor
	 */
	public function lib_css(string $link,array $extra = []){
		$env = strtolower(ENVIRONMENT);
		$link = trim($link);
		if($env == 'production' || $env == 'testing'){
			$css = "<LINK href=\"public/vendor/{$link}.css'rel='stylesheet'/>";
		}

		if($env == 'production' || $env == 'testing' && !empty($extra)){
			$css = "<LINK href=\"public/vendor/{$link}.css\" rel='stylesheet\" ";
			foreach($extra as $key => $value){
				$css .= " {$key}='{$value}' ";
			}
			$css .= "/>";
		}

		if($env == 'development' && $extra == NULL){
			$css = "<LINK href=\"{$this->link()}public/vendor/{$link}.css\" rel=\"stylesheet\"/>";
		}

		if($env == 'development' && $extra !== NULL){
			$css = "<LINK href=\"{$this->link()}public/vendor/{$link}.css\" rel=\"stylesheet\" ";
			foreach($extra as $key => $value){
				$css .= "{$key}=\"{$value}\" ";
			}
			$css .= "/>";
		}
		echo $css;
	}

	public function jsPath(string $location, string $step = 'core'){
		$path = "{$this->link()}public/assets/{$step}/{$location}.js";
		return $path;
	}

	public function cssPath(string $location, string $step = 'core'){
		$path = "{$this->link()}public/assets/{$step}/{$location}.css";
		return $path;
	}

	//***************************************************************
	//Description: Helper function for JS script tag.
	//***************************************************************/

	/**
	 * UI vendor - Javascript - public/vendor
	 */
	public function lib_js(string $link,array $extra = []){
		$env = strtolower(ENVIRONMENT);
		$link = trim($link);
		if($env == 'production' || $env == 'testing' && $extra == NULL){
			$js = "<SCRIPT src=\"public/vendor/{$link}.js\"></SCRIPT>";
		}

		if($env == 'production' || $env == 'testing' && $extra != NULL){
			$js = "<SCRIPT src=\"public/vendor/{$link}.js\" ";
			foreach($extra as $key => $value){
				$js .= " {$key}='{$value}' ";
			}
			$js .= "></SCRIPT>";
		}

		if($env == 'development' && $extra == NULL){			
			$js = "<SCRIPT src=\"{$this->link()}public/vendor/{$link}.js\"></SCRIPT>";
		}

		if($env == 'development' && $extra !== NULL){
			$js = "<SCRIPT src=\"{$this->link()}public/vendor/{$link}.js\" "; 
			foreach($extra as $key => $value){
				$js .= "{$key}=\"{$value}\" ";
			}
			$js .= "></SCRIPT>";
		}
		echo $js;
	}

	//***************************************************************
	//Description: get list from table.
	//***************************************************************/
	public function  _setViewPath(string $path){
		$this->_viewPath = '';
		$this->_viewPath = $path;
	}

	private function  _getViewPath(){
		$ret = $this->_viewPath.DS.utils\Config::get('src/view').DS;
		return $ret;
	}

	private function _commonTemplate(){
		return TEMPLATE_PATH.$page;
	}

	protected function setErrorPage(string $name, string $error_link = ''){
		if($error_link !== ''){
			$error_link = str_replace ('.' , DS , $error_link);
			$this->pageRender($name,'',$error_link);
		}else{
			$this->pageRender($name);
		}

	}

	/****************************** Helper Classes  **/
	public function content(string $extra = null){
		$body  = "</HEAD><BODY {$extra}>";
		$body .= '<!--[if lte IE 9]>';
		$body .= '<P class="browserupgrade">You are using an <strong>outdated</strong> browser.';
		$body .= 'Please <A href="https://browsehappy.com/">upgrade your browser</A> to improve your experience and security.';
		$body .= '</P>';
		$body .= '<![endif]-->';
		$body .= '<!--[if IE]>';
		$body .= '<SCRIPT src="//html5shiv.googlecode.com/svn/trunk/html5.js"></SCRIPT>';
		$body .= '<![endif]-->';
		echo $body;
	}

	public function endContent(){
		$this->closeTemplate(); 
		$this->_funct_called = 'X';
	}

	//***************************************************************************DIVS
	//	public function div($id, $attrib, $comment = ''){

	public function div(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<DIV {$attributes}>";
	}

	public function enddiv(string $id, string $comment = ''){
		echo "</DIV> <!-- END OF DIV: {$id} / {$comment} -->";
	}

	public function _div(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<DIV {$attributes}></DIV> <!-- END OF DIV: {$id} -->";
	}

	//***************************************************************************NAV
	public function nav(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<NAV {$attributes}>";
	}

	public function endnav(string $id, string $comment = ''){
		echo "</NAV> <!-- END OF NAV: {$id} / {$comment} -->";
	}

	public function _nav(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<NAV {$attributes}></NAV> <!-- END OF NAV: {$id} -->";
	}

	//***************************************************************************ARTICLE
	public function article(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<ARTICLE {$attributes}>";
		echo $this;
	}

	public function endarticle(string $id, string $comment = ''){
		echo "</ARTICLE> <!-- END OF ARTICLE {$id} / {$comment} -->";
	}

	public function _article(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<ARTICLE {$attributes}></ARTICLE> <!-- END OF ARTICLE: {$id}-->";
	}

	//***************************************************************************SECTION
	public function section(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<SECTION {$attributes}>";
	}

	public function endsection(string $id, string $comment = ''){
		echo "</SECTION> <!-- END OF SECTION: {$id} / {$comment} -->";
	}

	public function _section(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<SECTION {$attributes}></SECTION> <!-- END OF SECTION: {$id}-->";
	}

	//***************************************************************************ASIDE
	public function aside(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<ASIDE {$attributes}>";
	}

	public function endaside(string $id, string $comment = ''){
		echo "</ASIDE> <!-- END OF ASIDE: {$id} / {$comment} -->";
	}

	public function _aside(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<ASIDE {$attributes}></ASIDE> <!-- END OF ASIDE: {$id}-->";
	}

	//***************************************************************************HEADER
	public function header(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<HEADER {$attributes}>";
	}

	public function endheader(string $id,string $comment = ''){
		echo "</HEADER> <!-- END OF HEADER: {$id} / {$comment} -->";
	}

	public function _header(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<HEADER {$attributes}></HEADER> <!-- END OF HEADER: {$id} -->";
	}

	//***************************************************************************FOOTER
	public function footer(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<FOOTER {$attributes}>";
	}

	public function endfooter(string $id,string $comment = ''){
		echo "</FOOTER> <!-- END OF FOOTER: {$attrib} / {$comment} -->";
	}

	public function _footer(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<FOOTER {$attributes}></FOOTER> <!-- END OF FOOTER: {$id} -->";
	}

	//***************************************************************************CANVAS
	public function _canvas(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<CANVAS {$attributes}></CANVAS>";
	}

	public function canvas(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<CANVAS {$attributes}>";
	}

	public function endcanvas(string $id,string $comment = ''){
		echo "</CANVAS><!-- END OF CANVAS: {$id} / {$comment} -->";
	}

	//***************************************************************************DETAILS
	public function _details(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<DETAILS {$attributes}></DETAILS>";
	}

	public function details(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<DETAILS {$attributes}>";
	}

	public function enddetails(string $id,string $comment = ''){
		echo "</DETAILS><!-- END OF DETAILS: {$id} / {$comment} -->";
	}

	//***************************************************************************SUMMARY
	public function _summary(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<SUMMARY {$attributes}></SUMMARY>";
	}

	public function summary(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<SUMMARY {$attributes}><!-- END OF SUMMERY: {$id} -->";
	}

	public function endsummary(string $id, string $comment = ''){
		echo "</SUMMARY><!-- END OF SUMMERY: {$id} / {$comment} -->";
	}

	//***************************************************************************LISTS
	public function ul(string $id, array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<UL {$attributes}>";
	}

	public function ol(string $id, string $attrib = ''){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<OL {$attributes}";
	}

	public function li(array $arr = []){
		$attributes = tags\Tag::arributes2(__FUNCTION__,$arr,debug_backtrace());
		echo "<LI {$attributes}>";
	}
	
	public function endli(string $comment = ''){
		echo "</LI><!-- {$comment} -->";
	}

	// Output List
	public function endul(string $id, string $comment = ''){
		echo  "</UL><!-- END OF UL: {$id} / {$comment} -->";
	}

	public function endol(string $id, string $comment = ''){
		echo "</OL><!-- END OF OL: {$id} / {$comment} -->";
	}

	public function _li(string $attrib = ''){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<LI {$attributes}> </LI>";
	}

	public function _ul(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<UL {$attributes}> </UL>";
	}

	//***************************************************************************TABLE
	public function table(string $id,array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TABLE {$attributes}>";
		echo $this;
	}

	public function thead(array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<THEAD {$attributes}>";
	}

	public function endthead(string $comment = ''){
		echo  "</TBODY> <!--  {$comment} -->";
	}

	public function tbody(array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<TBODY {$attributes}>";
	}

	public function endtbody(string $comment = ''){
		echo  "</TBODY> <!--  {$comment} -->";
	}

	public function td(array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo  "<TD {$attributes}>";
	}

	public function endtd(string $comment = ''){
		echo  "</TD><!--  {$comment} -->";
	}

	public function th(array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo  "<TH {$attributes}>";
	}

	public function endth(string $comment = ''){
		echo  "</TH><!--  {$comment} -->";
	}

	public function tr(array $arr = []){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo  "<TR {$attributes}>";
	}

	public function endtr(string $comment = ''){
		echo "</TR><!--  {$comment} -->";
	}

	// Output TABLE
	public function endtable(string $id, string $comment = ''){
		echo  "</TABLE><!-- END OF {$id}  {$comment} -->";
	}

	public function _table(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo  "<TABLE {$attributes}></TABLE>";
	}

	public function _thead(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo  "<THEAD {$attributes}></THEAD>";
	}

	public function _tfoot(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<TFOOT {$attributes}></TFOOT>";
	}

	public function _td(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<TD {$attributes}></TD>";
	}

	public function _th(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<TH {$attributes}></TH>";
	}

	public function _tr(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<TR {$attributes}></TR>";
	}

	//**********************COMMENT*****************************/

	/**
	 *  Places an HTML comment within the view HTML Page.
	 */
	public function comment(string $comment){
		echo "<!-- {$comment} -->";
	}

	//**********************HEADER*****************************/
	public function h(string $no,string $content = ''){
		echo "<H{$no}>{$content}</H{$no}>";
	}

	public function _h(string $no, string $id){
		echo "<H{$no} $id=\"{$id}\"></H{$no}>";
	}

	//**********************HEADER*****************************/
	public function hr(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<HR {$attributes}/>";
	}

	//**********************HEADER*****************************/
	public function br(int $no = null){
		if($no !== null && is_numeric($no)){
			for($i = 0; $i < $no; $i++ ){
				echo "<BR />";
			}
		}else{
			echo "<BR />";
		}
	}
	//**********************FONT*****************************/
	public function i(string $content, string $class, string $extra = ''){
		echo "<I class=\"{$class}\" {$extra}>{$content}</I>";
	}

	public function _i(string $class,string $extra = ''){
		echo "<I class=\"{$class}\" {$extra}></I>";
	}

	//**********************SPAN*****************************/
	public function span(string $content,string $class='', string $extra = ''){
		echo "<SPAN class=\"{$class}\" {$extra}>{$content}</SPAN>";
	}

	/**
	 * Gets the default header from the view library. 
	 * The default options has set options for charset, viewport and clouds. 
	 */

	public function get_default_header(string $title, string $attrib = 'class="no-js" lang=""'){
		$header = "<!DOCTYPE html>";
		$header .= "<HTML {$attrib}>";
		$header .= "<HEAD>";
		$header .= $this->charset();
		$header .= "<META http-equiv=\"x-ua-compatible\" content=\"ie=edge\">";
		$header .= $this->viewport();
		$header .= "<TITLE>{$title}</TITLE>";
		$header .= $this->_getCloudLibs();
		echo $header;
	}

	private function _getCloudLibs(){
		$output = '';
		require PROJECT_PATH . 'router.php';
		if(!empty($cdn_css)){
			foreach($cdn_css as $css_links){
				$output .= $this->cdn('css',$css_links);
			}			
		}

		if(!empty($cdn_js)){
			foreach($cdn_js as $js_links){
				$output .= $this->cdn('js',$js_links);
			}	
		}
		return $output;
	}

	public function description(string $content){
		return  "<META name=\"description\" content=\"{$content}\">";
	}

	public function charset(string $content = 'utf-8'){
		if(isset($this->_charset)){
			return "<META charset=\"{$this->_charset}\">";
		}else{
			return "<META charset=\"{$content}\">";
		}
	}

	public function viewport(string $content = 'width=device-width, initial-scale=1'){
		if(isset($this->_viewport)){
			return "<META name=\"viewport\" content=\"{$this->_viewport}\">";
		}else{
			return "<META name=\"viewport\" content=\"{$content}\">";
		}
	}

	public function set_charset(string $content){
		$this->_charset = $content;
	}

	public function set_viewport(string $content){
		$this->_viewport = $content;
	}

	public function a(string $id, string $content,string  $herf = '#', string $class='', string $attrib=''){
		echo  "<A id=\"{$id}\" class=\"{$class}\" href=\"{$herf}\" {$attrib}>{$content}</A>";
	}

	public function tag(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<{$tag} {$attributes}>{$content}<{$tag}>";
	}

	public function itag(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		echo "<{$tag} {$attributes} />";
	}

	//*******************************************************FORM**********************************/
	public function form(string $id,string $action = '',string $method = 'post', string $attrib = ''){
		echo "<FORM id='{$id}' method='{$method}' action='{$action}' {$attrib} >";
	} 

	public function endform($comment = ''){
		echo  "</FORM><!-- {$comment} -->";
	}

	public function _form($attrib = ''){
		echo "<FORM {$attrib} ></FORM>";
	}
	public function fieldset($fieldlist, $attrib = ''){
		echo  "<FIELDSET {$attrib}>{$fieldlist}</FIELDSET>";
	}

	public function legend($fieldlist, $attrib = ''){
		echo  "<LEGEND {$attrib}>{$fieldlist}</LEGEND>";
	}

	//*******************************************************LABEL**********************************/
	public function label(string $id, array $arr = []){
		$label = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $label;
	}

	//*******************************************************INPUT TEXT**********************************/
	public function text(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT RADIO**********************************/
	public function radio(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT CHECKBOX**********************************/
	public function checkbox(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT PASSWORD**********************************/
	public function password(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT CHECKBOX**********************************/
	public function button(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT SUBMIT**********************************/
	public function submit(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT RESET**********************************/
	public function reset(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT COLOR**********************************/
	public function color(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT DATE**********************************/
	public function date(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT datetime-local**********************************/
	public function datetime(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT email**********************************/
	public function email(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT month**********************************/
	public function month(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT number**********************************/
	public function number(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT range**********************************/
	public function range(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT search**********************************/
	public function search(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT tel**********************************/
	public function tel(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT time**********************************/
	public function time(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT url**********************************/
	public function url(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************INPUT week**********************************/
	public function week(string $id, array $arr = []){
		$input = input\Input::field(__FUNCTION__,$id, $arr);	
		echo $input;
	}

	//*******************************************************TEXTAREA********************************/
	public function textarea(string $id, array $arr = [], $content){
		$textarea = input\Input::field(__FUNCTION__,$id, $arr);	
		$textarea .= $content;
		$textarea .= "</TEXTAREA><!-- END OF TEXTAREA: {$id} -->";
		echo $textarea;
	}

	//*******************************************************SELECT**********************************/
	public function select(string $id, array $arr = []){
		$this->_select = input\Input::field(__FUNCTION__,$id, $arr);	
		return $this;
	}

	public function options($contents = [], bool $condition = false){
		$options = '';
		$vad = '';
		if(!empty($contents)){
			foreach($contents as $content){
				if($opt = explode(':',$content)){		
					if(!empty($opt) && count($opt) == '2')
						$vad = trim($opt[1]);
					$options .= "<OPTION value=\"{$opt[0]}\" "; 
					if($condition) $options .= 'SELECTED';
					if(!isset($vad))$options .= "></OPTION>";
					if(isset($vad))$options .= ">{$vad}</OPTION>";
				}else{
					$options .= "<OPTION></OPTION>"; 
				}
			}
		}else{
			$options .= "<OPTION></OPTION>"; 	
		}
		$this->_select .= $options;
		return $this;
	}

	public function endselect(string $id, string $attrib = ''){
		$select = "</SELECT><!-- END OF SELECT-OPTION: {$id} -->";
		$this->_select .= $select;
		echo $this->_select;
	}

	//*******************************************************FRAME**********************************/
	public function frame(string $url, string $attrib = ''){
		echo "<IFRAME src='{$url}' {$attrib} ></IFRAME><!-- END OF IFRAME: {$id} -->";
	}
	//*******************************************************UPLOAD**********************************/
	public function upload(string $id, string $iclass = '', string $lclass = 'button'){
		$upload =  "<LABEL for=\"{$id}\" class=\"{$lclass}\">Upload File</LABEL>";
		$upload .= "<INPUT type=\"file\" id=\"{$id}\" name=\"upl_{$id}\" class=\"show-for-sr {$iclass}\">";
		echo $upload;
	}

	//*******************************************************UPLOAD**********************************/
	public function click(string $id, string $href = '#',string $content, string $class = '', string $attrib = ''){
		$click .= "<A id=\"{$id}\" href=\"{$href}\" class=\"button {$class}\" {$attrib}>{$content}</A>";
		echo $click;
	}
	
	public function clearfix($comment = ''){
		echo  "<div class=\"clearfix\" style=\"clear:both\"></div><!-- CLEARFIX-{$comment} -->";
	}

	//***************************************************************
	//Description: Execute at the end.
	//***************************************************************/
	public function __destruct(){
		if($this->_template !== null){
			$this->pageRender($this->_template,$this->_getViewPath());
			if($this->_funct_called !== 'X') $this->closeTemplate();            
		}
	}
}
