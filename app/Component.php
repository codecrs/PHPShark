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

final class Component {
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
	/******************************HELPER ******************************************/
	private $content = '';
	private $_form = '';
	private $_select = '';
	private $_table = '';
	private $_viewport = '';
	private $_charset = '';
	private $_rowClass = '';
	private $_colClass ='';
	private $_gridClass = '';

	//***************************************************************
	//Description: load the View method called from the controller method.
	//methods available to us in view class are as follows: 
	//$this->host 
	//$this->uri  
	//$this->request 
	//$this->route 
	//$this->url 
	//***************************************************************/
	public function __construct(){
		$this->paginate   = new pages\Pagination();
		$this->content = '';
	}

	//--------------------------------------------------------------------------------
	// PUBLIC PAGE FUNCTIONS
	//--------------------------------------------------------------------------------
	//***************************************************************
	//Description: link($link = null) function is similar to URL function 
	//in the core library. This is an alias function written for the ease of 
	//understanding while writing the view functions.
	//***************************************************************/
	public function link($link = null){
		$ret = url($link);
		return $ret;
	}

	//***************************************************************
	//Description: 
	//***************************************************************/
	public function closeTemplate(){
		$footer  = "<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->";
		$footer .= "<SCRIPT>";
		$footer .= "window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;";
		$footer .= "ga('create','UA-XXXXX-Y','auto');ga('send','pageview')";
		$footer .= "</SCRIPT>";
		$footer .= '<SCRIPT src="https://www.google-analytics.com/analytics.js" async defer></SCRIPT>';
		$footer .= '</BODY></HTML>';
		echo $footer;

	}

	/****************************** Helper Classes  **/
	public function content($extra = null){
		$body  = "</DIV><BODY {$extra}>";
		$body .= '<!--[if lte IE 9]>';
		$body .= '<p class="browserupgrade">You are using an <strong>outdated</strong> browser.';
		$body .= 'Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.';
		$body .= '</p>';
		$body .= '<![endif]-->';
		$this->content .= $body;
	}

	public function endContent(){
		$this->closeTemplate(); 
		$this->_funct_called = 'X';
	}

	//***************************************************************************DIVS
	//	public function div($id, $attrib, $comment = ''){

	public function div($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<DIV {$attributes}>";
		return $this;
	}

	public function enddiv($id,$comment = ''){
		$this->content .= "</DIV> <!-- END OF DIV: {$id} / {$comment} -->";
		return $this;
	}

	public function _div($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<DIV {$attributes}></DIV> <!-- END OF DIV: {$id} -->";
		return $this;
	}

	//***************************************************************************NAV
	public function nav($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<NAV {$attributes}>";
		return $this;
	}

	public function endnav($id, $comment = ''){
		$this->content .= "</NAV> <!-- END OF NAV: {$id} / {$comment} -->";
		return $this;
	}

	public function _nav($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<NAV {$attributes}></NAV> <!-- END OF NAV: {$id} -->";
		return $this;
	}

	//***************************************************************************ARTICLE
	public function article($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<ARTICLE {$attributes}>";
		return $this;
	}

	public function endarticle($id, $comment = ''){
		$this->content .= "</ARTICLE> <!-- END OF ARTICLE {$id} / {$comment} -->";
		return $this;
	}

	public function _article($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<ARTICLE {$attributes}></ARTICLE> <!-- END OF ARTICLE: {$id}-->";
		return $this;
	}

	//***************************************************************************SECTION
	public function section($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<SECTION {$attributes}>";
		return $this;
	}

	public function endsection($id, $comment = ''){
		$this->content .= "</SECTION> <!-- END OF SECTION: {$id} / {$comment} -->";
		return $this;
	}

	public function _section($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<SECTION {$attributes}></SECTION> <!-- END OF SECTION: {$id}-->";
		return $this;
	}

	//***************************************************************************ASIDE
	public function aside($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<ASIDE {$attributes}>";
		return $this;
	}

	public function endaside($id, $comment = ''){
		$this->content .= "</ASIDE> <!-- END OF ASIDE: {$id} / {$comment} -->";
		return $this;
	}

	public function _aside($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<ASIDE {$attributes}></ASIDE> <!-- END OF ASIDE: {$id}-->";
		return $this;
	}

	//***************************************************************************HEADER
	public function header($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<HEADER {$attributes}>";
		return $this;
	}

	public function endheader($id,$comment = ''){
		$this->content.= "</HEADER> <!-- END OF HEADER: {$id} / {$comment} -->";
		return $this;
	}

	public function _header($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<HEADER {$attributes}></HEADER> <!-- END OF HEADER: {$id} -->";
		return $this;
	}

	//***************************************************************************FOOTER
	public function footer($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<FOOTER {$attributes}>";
		return $this;
	}

	public function endfooter($id,$comment = ''){
		$this->content .= "</FOOTER> <!-- END OF FOOTER: {$attrib} / {$comment} -->";
		return $this;
	}

	public function _footer($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<FOOTER {$attributes}></FOOTER> <!-- END OF FOOTER: {$id} -->";
		return $this;
	}

	//***************************************************************************CANVAS
	public function _canvas($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<CANVAS {$attributes}></CANVAS>";
		return $this;
	}

	public function canvas($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<CANVAS {$attributes}>";
		return $this;
	}

	public function endcanvas($id,$comment = ''){
		$this->content .= "</CANVAS><!-- END OF CANVAS: {$id} / {$comment} -->";
		return $this;
	}

	//***************************************************************************DETAILS
	public function _details($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<DETAILS {$attributes}></DETAILS>";
		return $this;
	}

	public function details($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<DETAILS {$attributes}>";
		return $this;
	}

	public function enddetails($id,$comment = ''){
		$this->content .= "</DETAILS><!-- END OF DETAILS: {$id} / {$comment} -->";
		return $this;
	}

	//***************************************************************************SUMMARY
	public function _summary($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content.= "<SUMMARY {$attributes}></SUMMARY>";
		return $this;
	}

	public function summary($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<SUMMARY {$attributes}><!-- END OF SUMMERY: {$id} -->";
		return $this;
	}

	public function endsummary($id, $comment = ''){
		$this->content .= "</SUMMARY><!-- END OF SUMMERY: {$id} / {$comment} -->";
		return $this;
	}

	//***************************************************************************LISTS
	public function ul($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<UL {$attributes}>";
		return $this;
	}

	public function ol($id, $attrib = ''){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<OL {$attributes}";
		return $this;
	}

	public function li($arr = array()){
		$attributes = tags\Tag::arributes2(__FUNCTION__,$arr,debug_backtrace());
		$this->content .=  "<LI {$attributes}>";
		return $this;
	}
	
	public function endli($comment = ''){
		$this->content .=  "</LI><!-- {$comment} -->";
		return $this;
	}

	// Output List
	public function endul($id, $comment = ''){
		$this->content .=  "</UL><!-- END OF UL: {$id} / {$comment} -->";
		return $this;
	}

	public function endol($id, $comment = ''){
		$this->content .= "</OL><!-- END OF OL: {$id} / {$comment} -->";
		return $this;
	}

	public function _li($attrib = ''){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<LI {$attributes}> </LI>";
		return $this;
	}

	public function _ul($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<UL {$attributes}> </UL>";
		return $this;
	}

	//***************************************************************************TABLE
	public function table($id,$arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TABLE {$attributes}>";
		return $this;
	}

	public function thead($arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .=  "<THEAD {$attributes}>";
		return $this;
	}

	public function endthead($comment = ''){
		$this->content .=  "</TBODY> <!--  {$comment} -->";
		return $this;
	}

	public function tbody($arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TBODY {$attributes}>";
		return $this;
	}

	public function endtbody($comment = ''){
		$this->content .= "</TBODY> <!--  {$comment} -->";
		return $this;
	}

	public function td($arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .=  "<TD {$attributes}>";
		return $this;
	}

	public function endtd($comment = ''){
		$this->content .=  "</TD><!--  {$comment} -->";
		return $this;
	}

	public function th($arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .=  "<TH {$attributes}>";
		return $this;
	}

	public function endth($comment = ''){
		$this->content .= "</TH><!--  {$comment} -->";
		return $this;
	}

	public function tr($arr = array()){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TR {$attributes}>";
		return $this;
	}

	public function endtr($comment = ''){
		$this->content .= "</TR><!--  {$comment} -->";
		return $this;
	}

	// Output TABLE
	public function endtable($id, $comment = ''){
		$this->content .= "</TABLE><!-- END OF {$id}  {$comment} -->";
		return $this;
	}

	public function _table(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TABLE {$attributes}></TABLE>";
		return $this;
	}

	public function _thead(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<THEAD {$attributes}></THEAD>";
		return $this;
	}

	public function _tfoot(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TFOOT {$attributes}></TFOOT>";
		return $this;
	}

	public function _td(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TD {$attributes}></TD>";
		return $this;
	}

	public function _th(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TH {$attributes}></TH>";
		return $this;
	}

	public function _tr(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<TR {$attributes}></TR>";
		return $this;
	}

	//**********************COMMENT*****************************/
	public function comment($comment){
		$this->content .= "<!-- {$comment} -->";
		return $this;
	}

	//**********************HEADER*****************************/
	public function h($no,$content = ''){
		$this->content .= "<H{$no}>{$content}</H{$no}>";
		return $this;
	}

	public function _h($no, $id){
		$this->content .= "<H{$no} $id=\"{$id}\"></H{$no}>";
		return $this;
	}

	//**********************HEADER*****************************/
	public function hr(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<HR {$attributes}/>";
		return $this;
	}

	//**********************HEADER*****************************/
	public function br(){
		$this->content .= "<BR />";
		return $this;
	}

	//**********************FONT*****************************/
	public function i($content,$class,$extra = ''){
		$this->content .= "<I class=\"{$class}\" {$extra}>{$content}</I>";
		return $this;
	}

	public function _i($class,$extra = ''){
		$this->content .= "<I class=\"{$class}\" {$extra}></I>";
		return $this;
	}

	//**********************SPAN*****************************/
	public function span($content,$class='', $extra = ''){
		$this->content .= "<SPAN class=\"{$class}\" {$extra}>{$content}</SPAN>";
		return $this;
	}

	public function get_default_header($title, $attrib = 'class="no-js" lang=""'){
		$header = "<!DOCTYPE html>";
		$header .= "<HTML {$attrib}>";
		$header .= "<HEAD>";
		$header .= $this->charset();
		$header .= "<META http-equiv=\"x-ua-compatible\" content=\"ie=edge\">";
		$header .= $this->viewport();
		$header .= "<TITLE>{$title}</TITLE>";
		$header .= $this->_getCloudLibs();
		$this->content .= $header;
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

	public function description($content){
		$this->content .=  "<META name=\"description\" content=\"{$content}\">";
		return $this;
	}

	public function charset($content = 'utf-8'){
		if(isset($this->_charset)){
			return "<META charset=\"{$this->_charset}\">";
		}else{
			return "<META charset=\"{$content}\">";
		}
	}

	public function viewport($content = 'width=device-width, initial-scale=1'){
		if(isset($this->_charset)){
			return "<META name=\"viewport\" content=\"{$this->_charset}\">";
		}else{
			return "<META name=\"viewport\" content=\"{$content}\">";
		}
	}

	public function set_charset($content){
		$this->_charset = $content;
	}

	public function set_viewport($content){
		$this->_viewport = $content;
	}

	public function a($id, $content, $herf = '#', $class='', $attrib=''){
		$this->content .= "<a id=\"{$id}\" class=\"{$class}\" href=\"{$herf}\" {$attrib}>{$content}</a>";
		return $this;
	}

	public function tag(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<{$tag} {$attributes}>{$content}<{$tag}>";
		return $this;
	}

	public function itag(){
		$attributes = tags\Tag::arributes(__FUNCTION__,$id,$arr,debug_backtrace());
		$this->content .= "<{$tag} {$attributes} />";
		return $this;
	}

	//*******************************************************FORM**********************************/
	public function form($id,$action = '',$method = 'post', $attrib = ''){
		$this->content .= "<FORM id='{$id}' method='{$method}' action='{$action}' {$attrib} >";
		return $this;
	} 

	public function endform($comment = ''){
		$this->content .= "</FORM><!-- {$comment} -->";
		return $this;
	}

	public function _form($attrib = ''){
		$this->content .= "<FORM {$attrib} ></FORM>";
		return $this;
	}
	public function fieldset($fieldlist, $attrib = ''){
		$this->content .= "<FIELDSET {$attrib}>{$fieldlist}</FIELDSET>";
		return $this;
	}

	public function legend($fieldlist, $attrib = ''){
		$this->content .= "<LEGEND {$attrib}>{$fieldlist}</LEGEND>";
		return $this;
	}

	//*******************************************************LABEL**********************************/
	public function label($id, $arr = array()){
		$label = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $label;
		return $this;
	}

	//*******************************************************INPUT TEXT**********************************/
	public function text($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT RADIO**********************************/
	public function radio($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT CHECKBOX**********************************/
	public function checkbox($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT PASSWORD**********************************/
	public function password($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT CHECKBOX**********************************/
	public function button($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT SUBMIT**********************************/
	public function submit($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT RESET**********************************/
	public function reset($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT COLOR**********************************/
	public function color($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT DATE**********************************/
	public function date($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT datetime-local**********************************/
	public function datetime($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT email**********************************/
	public function email($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT month**********************************/
	public function month($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT number**********************************/
	public function number($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT range**********************************/
	public function range($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT search**********************************/
	public function search($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT tel**********************************/
	public function tel($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT time**********************************/
	public function time($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT url**********************************/
	public function url($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************INPUT week**********************************/
	public function week($id, $arr = array()){
		$input = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$this->content .= $input;
		return $this;
	}

	//*******************************************************TEXTAREA********************************/
	public function textarea($id, $arr = array(), $content){
		$textarea = input\Input::field(__FUNCTION__,$id, $arr = array());	
		$textarea .= $content;
		$textarea .= "</TEXTAREA><!-- END OF TEXTAREA: {$id} -->";
		$this->content .= $textarea;
		return $this;
	}

	//*******************************************************SELECT**********************************/
	public function select($id, $arr = array()){
		$this->_select = input\Input::field(__FUNCTION__,$id, $arr = array());	
		return $this;
	}

	public function options($contents = [], $condition = false){
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

	public function endselect($id, $attrib = ''){
		$select = "</SELECT><!-- END OF SELECT-OPTION: {$id} -->";
		$this->_select .= $select;
		$this->content .= $this->_select;
		return $this;
	}

	//*******************************************************FRAME**********************************/
	public function frame($url, $attrib = ''){
		$this->content .= "<IFRAME src='{$url}' {$attrib} ></IFRAME><!-- END OF IFRAME: {$id} -->";
		return $this;
	}
	//*******************************************************UPLOAD**********************************/
	public function upload($id, $iclass = '', $lclass = 'button'){
		$upload =  "<LABEL for=\"{$id}\" class=\"{$lclass}\">Upload File</LABEL>";
		$upload .= "<INPUT type=\"file\" id=\"{$id}\" name=\"upl_{$id}\" class=\"show-for-sr {$iclass}\">";
		$this->content .= $upload;
		return $this;
	}

	//*******************************************************UPLOAD**********************************/
	public function click($id, $href = '#',$content, $class = '', $attrib = ''){
		$click .= "<A id=\"{$id}\" href=\"{$href}\" class=\"button {$class}\" {$attrib}>{$content}</A>";
		$this->content .= $click;
		return $this;
	}

	//*******************************************************ROW & COLUMN**********************************/
	public function row(){
		if(!empty(func_get_args()))
			$this->_rowClass = implode(' ',func_get_args());
		$this->content .= "<DIV class=\"{$this->_rowClass}\">";
		return $this;
	}

	public function endrow($id, $comment = ''){
		$this->content .=  "</DIV><!-- END OF GRID: {$id} / {$comment} -->";	
		return $this;
	} 

	public function _row($comment){
		$this->content .= "<DIV class=\"{$this->_rowClass}\"><!-- $comment -->"; 
		return $this;
	}

	public function col(){
		if(!empty(func_get_args()))
			$this->_colClass = implode(' ',func_get_args());
		$this->content .= "<DIV class=\"cell {$this->_colClass}\">"; 
		return $this;
	}

	public function _col(){
		if(!empty(func_get_args()))
			$this->_colClass .= implode(' ',func_get_args());
			$this->content .= "<DIV class=\"cell {$this->_colClass}\">
				 </DIV><!-- END OF GRID: {$id} / {$comment} -->"; 
		return $this;
	}

	public function endcol($id, $comment = ''){
		$this->content.= "</DIV><!-- END OF GRID: {$id} / {$comment} -->";	
		return $this;
	} 
	
	//***************************************************************
	//Description: Execute at the end.
	//***************************************************************/
	public function __destruct(){} 
}
