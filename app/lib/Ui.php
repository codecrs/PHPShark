<?php
final class Ui{
	private $_jsLocation = '';
	private $_cssLocation = '';
	private $_jsPlugLocation = '';
	private $_cssPlugLocation = '';
	private $_name = '';
	private $_css = '';
	private $_js = '';
	private $_cssOptions = '';
	private $_jsOptions = '';
	private $_jsOptsArray = array();
	private $_cssOptsArray = array();
	private $_cssArray = array();
	private $_jsArray = array();

	public function __construct(){
	} // construct

	public function __destruct(){
		$this->_jsOptsArray = null;
		$this->_cssOptsArray = null;
		$this->_cssArray = null;
		$this->_jsArray = null;
		
		$this->_css = null;
		$this->_cssOptions = null;
		
		$this->_js = null;
		$this->_jsOptions = null;
	} //destruct
	
	public function splash(){
		$fparam = func_num_args();
		switch($fparam){
			case 1: 
				$classname = func_get_arg(0);
				$function  = null;
				$param     = null;
				$cssPath   = '';
			break;
				
			case 2: 
				$classname = func_get_arg(0);
				$function  = null;
				$param 	   = null;
				$cssPath   = func_get_arg(1);
			break;
				
			case 3: 
				$classname = func_get_arg(0);
				$function  = func_get_arg(1);
				$param     = func_get_arg(2);
				$cssPath   = '';
			break;
				
			case 4: 
				$classname = func_get_arg(0);
				$function  = func_get_arg(1);
				$param     = func_get_arg(2);
				$cssPath   = func_get_arg(3);
			break;
		}
		
		$snippet = '';
		$cssFullPath = '';
		$cssExplode = array();
		if($cssPath !== null){
			$cssExplode = explode('-',$cssPath);
			if(!empty($cssExplode) && count($cssExplode) == 2){
				if($cssExplode[0] !== null){
					$cssFullPath  = $this->defaultCSSVendorPlug($cssExplode[0]);
					$cssFullPath .= "{$cssExplode[1]}.css";						
				}
			}

			$snippet .= '<SCRIPT>';
			$snippet .= "$(window).on('load', function(){";
			$snippet .= "$('head').append(";
			$snippet .= "$('<link rel=\"stylesheet\" type=\"text/css\"/>').attr('href', '{$cssFullPath}'";
			$snippet .= "));";
			$snippet .= "});";
			$snippet .= '</SCRIPT>';
		}			
		$snippet = '';
		$snippet .= "<div id=\"q-splash-screen\" class=\"{$classname}\"></div>";
		$snippet .= '<SCRIPT  type="text/javascript">';
		$snippet .= "$(window).on('load',function() {";
		if($function !== null){
			$snippet .=	"$(\".{$classname}\").{$function}({$param});";
		}else{
			$snippet .=	"$(\".{$classname}\").fadeOut(\"slow\");";			
		}
		$snippet .= 'document.getElementsByTagName("html")[0].style.visibility = "visible";';
		$snippet .= '});';
		$snippet .= "$(document).ready(function(){";
		$snippet .= 'document.getElementsByTagName("html")[0].style.visibility = "visible";';
		$snippet .= '});';
		$snippet .= '</SCRIPT>';
		return $snippet;
	}

	public function import(array $array){
		foreach($array as $key => $value){	
			$this->loader($key,$value);		
		}
		$snippet = $this->loadJquerySnippet();
		return $snippet;
	} // import

	public function plug(array $array){
		foreach($array as $key => $value){	
			$this->plug_loader($key,$value);		
		}
		$plug = $this->loadJqueryPlug();
		return $plug;
	} // import

	private function loader($key, $value){	
		$version = null;
		$name = null;
		$key = strtolower($key);
		
		if(!is_array($value)){
			//Error 
			die('QMVC View ERROR: Improper UI Loader Defined.');
		}else{
			foreach($value as $opt => $val){
				switch($opt){
					case 'name':
						$name = $val;
						break; 	
					case 'version':
						$version = $val;
						break; 
					case 'css':
						$this->getCss($key,$name,$val);
						break; 
					case 'js':
						$this->getJs($key,$name,$val);
						break; 
				}	
			}
			
			if(!isset($value['js'])){
				$this->_js = null;
				$this->_jsOptions = null;
			}
			
			if(!isset($value['css'])){
				$this->_css = null;
				$this->_cssOptions = null;
			}

			$vendors = array();
			require_once(PROJECT_PATH.'version.php');
			if(!isset($version) && array_key_exists("{$key}.{$name}",$vendors)){
				$version = $vendors["{$key}.{$name}"];
			}
			$this->_jsLocation = $this->defaultJSVendorLib($key, $name, $version);
			$this->_cssLocation = $this->defaultCSSVendorLib($key, $name, $version);
		}
	}

	private function plug_loader($key, $value){	
		$version = null;
		$name = null;
		$key = strtolower($key);
		
		if(!is_array($value)){
			//Error 
			die('QMVC View ERROR: Improper UI Loader Defined.');
		}else{
			foreach($value as $opt => $val){
				switch($opt){
					case 'name':
						$name = $val;
						break; 	
					case 'version':
						$version = $val;
						break; 
					case 'css':
						$this->plugCss($key,$name,$val);
						break; 
					case 'js':
						$this->plugJs($key,$name,$val);
						break; 
				}	
			}
			
			if(!isset($value['js'])){
				$this->_js = null;
				$this->_jsOptions = null;
			}
			
			if(!isset($value['css'])){
				$this->_css = null;
				$this->_cssOptions = null;
			}

			$this->_jsPlugLocation = $this->defaultJSVendorPlug($key, $name, $version);
			$this->_cssPlugLocation = $this->defaultCSSVendorPlug($key, $name, $version);
		}
	}

	private function getCss($key,$name,$opt){
		$icss = '';
		$cssOpts = '';
		$cssinc = '';
		if(!is_array($opt)){
			return "{$key}.{$opt}.css";
		}else{	
			foreach($opt as $value => $css){
				switch($value){
					case 'include':
						$cssinc .= $this->consolidateAllCssFiles($css);
						break;
					case 'option':
						$cssOpts .= $this->consolidateAllCssOptions($css);
						break;
				}
			}
		}

		$this->_css = $cssinc;
		$this->_cssOptions = $cssOpts;
	}
	
	
	private function plugCss($key,$name,$opt){
		$icss = '';
		$cssOpts = '';
		$cssinc = '';
		if(!is_array($opt)){
			return "{$opt}.css";
		}else{	
			foreach($opt as $value => $css){
				switch($value){
					case 'include':
						$cssinc .= $this->consolidateAllCssFiles($css);
						break;
					case 'option':
						$cssOpts .= $this->consolidateAllCssOptions($css);
						break;
				}
			}
		}

		$this->_css = $cssinc;
		$this->_cssOptions = $cssOpts;
	}


	private function consolidateAllCssFiles($value){
		$icss = '';
		if(!is_array($value)){
			return $value;
		}else{
			foreach($value as $css){
				$icss .= "{$css}.css | ";					
			}
			$icss = rtrim($icss," | ");
			return $icss;
		}
	}

	private function consolidateAllCssOptions($val){
		$extra = '';
		if(!is_array($val)){
			//error
		}else{
			foreach($val as $key => $value){
				$extra .="{$key}: {$value} | ";
			}
			$extra = rtrim($extra,' | ');
			return $extra;			
		}
	}

	private function getJs($key,$name,$opt){
		$ijs = '';
		$jsOpts = '';
		$jsinc = '';
		if(!is_array($opt)){
			return "{$key}.{$opt}.js";
		}else{		
			foreach($opt as $value => $js){
				switch($value){
					case 'include':
						$jsinc .= $this->consolidateAllJsFiles($js);
						break;
					case 'option':
						$jsOpts .= $this->consolidateAllJsOptions($js);
						break;
				}	
			}
		}

		$this->_js = $jsinc;
		$this->_jsOptions = $jsOpts;
	}
	
	private function plugJs($key,$name,$opt){
		$ijs = '';
		$jsOpts = '';
		$jsinc = '';
		if(!is_array($opt)){
			return "{$opt}.js";
		}else{		
			foreach($opt as $value => $js){
				switch($value){
					case 'include':
						$jsinc .= $this->consolidateAllJsFiles($js);
						break;
					case 'option':
						$jsOpts .= $this->consolidateAllJsOptions($js);
						break;
				}	
			}
		}

		$this->_js = $jsinc;
		$this->_jsOptions = $jsOpts;
	}

	private function consolidateAllJsFiles($value){
		$ijs = '';
		if(!is_array($value)){
			return $value;
		}else{
			foreach($value as $js){
				$ijs .= "{$js}.js | ";					
			}
			$ijs = rtrim($ijs," | ");
			return $ijs;
		}
	}

	private function consolidateAllJsOptions($val){
		$extra = '';
		if(!is_array($val)){
			//error
		}else{
			foreach($val as $key => $value){
				$extra .="{$key}: {$value} | ";
			}
			$extra = rtrim($extra,' | ');
			return $extra;
		}
	}

	private function loadJquerySnippet(){	
		$value = '';
		$snippet = '';

		$this->_cssArray     = explode(' | ',trim($this->_css));
		$this->_cssOptsArray = explode(' | ',trim($this->_cssOptions));
		$this->_jsArray      = explode(' | ',trim($this->_js));
		$this->_jsOptsArray  = explode(' | ',trim($this->_jsOptions));

		array_map('trim',$this->_jsOptsArray);
		array_map('trim',$this->_cssOptsArray);
		array_map('trim',$this->_jsArray);
		array_map('trim',$this->_cssArray);

		if(!empty($this->_cssArray[0])){
			$snippet .= '<SCRIPT>';
			$snippet .= "$(window).on('load', function(){";
			foreach($this->_cssArray as $cssFiles){
				foreach($this->_cssOptsArray as $cssKeys){
					$cssExplode = explode(":",$cssKeys);
					array_map('trim',$cssExplode);	
					if($cssExplode[0].'.css' == $cssFiles){
						$value .= $cssExplode[1];
					}
				}
				$snippet .= "$('head').append(";
				$snippet .= "$('<link rel=\"stylesheet\" type=\"text/css\" {$value}/>').attr('href', '{$this->_cssLocation}{$cssFiles}'";
				$snippet .= "));";
			}
			$snippet .= "});";
			$snippet .= '</SCRIPT>';			
		}
		if(!empty($this->_jsArray[0])){
			foreach($this->_jsArray as $jsFiles){
				foreach($this->_jsOptsArray as $jsKeys){
					$jsExplode = explode(":",$jsKeys);
					array_map('trim',$jsExplode);		
					if($jsExplode[0].'.js' == $jsFiles){
						$value .= $jsExplode[1];
					}
				}
				$snippet .= "<SCRIPT src=\"{$this->_jsLocation}{$jsFiles}\" {$value} ></SCRIPT>";
			}
		}
		return $snippet;

	} //loadJquerySnippet

	private function loadJqueryPlug(){	
		$value = '';
		$snippet = '';

		$this->_cssArray     = explode(' | ',trim($this->_css));
		$this->_cssOptsArray = explode(' | ',trim($this->_cssOptions));
		$this->_jsArray      = explode(' | ',trim($this->_js));
		$this->_jsOptsArray  = explode(' | ',trim($this->_jsOptions));

		array_map('trim',$this->_jsOptsArray);
		array_map('trim',$this->_cssOptsArray);
		array_map('trim',$this->_jsArray);
		array_map('trim',$this->_cssArray);

		if(!empty($this->_cssArray[0])){
			$snippet .= '<SCRIPT>';
			$snippet .= "$(window).on('load', function(){";
			foreach($this->_cssArray as $cssFiles){
				foreach($this->_cssOptsArray as $cssKeys){
					$cssExplode = explode(":",$cssKeys);
					array_map('trim',$cssExplode);	
					if($cssExplode[0].'.css' == $cssFiles){
						$value .= $cssExplode[1];
					}
				}
				$snippet .= "$('head').append(";
				$snippet .= "$('<link rel=\"stylesheet\" type=\"text/css\" {$value}/>').attr('href', '{$this->_cssPlugLocation}{$cssFiles}'";
				$snippet .= "));";
			}
			$snippet .= "});";
			$snippet .= '</SCRIPT>';
		}
		if(!empty($this->_jsArray[0])){
		foreach($this->_jsArray as $jsFiles){
			foreach($this->_jsOptsArray as $jsKeys){
				$jsExplode = explode(":",$jsKeys);
				array_map('trim',$jsExplode);		
				if($jsExplode[0].'.js' == $jsFiles){
					$value .= $jsExplode[1];
				}
			}
			$snippet .= "<SCRIPT src=\"{$this->_jsPlugLocation}{$jsFiles}\" {$value} ></SCRIPT>";
		}		    
		}
		return $snippet;

	} //loadJqueryPlug

	private function defaultJSVendorLib($library, $name = null, $version = null){
		$env = strtolower(ENVIRONMENT);
		if($env == 'production' || $env == 'testing' && $name == NULL && $version == null){
			$js = "public/vendor/{$library}/js/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL && $version == null){
			$js = "public/vendor/{$library}/{$library}.{$name}/js/";
		}

		if($env == 'production' || $env == 'testing' && $name == NULL && $version != null){
			$js = "public/vendor/{$library}/{$version}/js/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL && $version != null){
			$js = "public/vendor/{$library}/{$library}.{$name}/{$version}/js/";
		}

		if($env == 'development' && $name == NULL && $version == null){			
			$js = url(). "public/vendor/{$library}/js/";
		}

		if($env == 'development' && $name != NULL && $version == null){			
			$js = url(). "public/vendor/{$library}/{$library}.{$name}/js/";
		}

		if($env == 'development' && $name == NULL && $version != null){			
			$js = url(). "public/vendor/{$library}/{$version}/js/";
		}

		if($env == 'development' && $name != NULL && $version != null){			
			$js = url(). "public/vendor/{$library}/{$library}.{$name}/{$version}/js/";
		}
		$vendorJSLoc = $js;
		return $vendorJSLoc;
	}

	private function defaultCSSVendorLib($library, $name = null, $version = null){
		$env = strtolower(ENVIRONMENT);
		if($env == 'production' || $env == 'testing' && $name == NULL  && $version == null){
			$css = "public/vendor/{$library}/css/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL  && $version == null){
			$css = "public/vendor/{$library}/{$library}.{$name}/css/";
		}


		if($env == 'production' || $env == 'testing' && $name == NULL  && $version != null){
			$css = "public/vendor/{$library}/{$version}/css/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL  && $version != null){
			$css = "public/vendor/{$library}/{$library}.{$name}/{$version}/css/";
		}

		if($env == 'development' && $name == NULL  && $version == null){			
			$css = url()."public/vendor/{$library}/css/";
		}

		if($env == 'development' && $name != NULL  && $version == null){			
			$css = url()."public/vendor/{$library}/{$library}.{$name}/css/";
		}

		if($env == 'development' && $name == NULL  && $version != null){			
			$css = url()."public/vendor/{$library}/{$version}/css/";
		}

		if($env == 'development' && $name != NULL  && $version != null){			
			$css = url()."public/vendor/{$library}/{$library}.{$name}/{$version}/css/";
		}
		$vendorCSSLoc = $css;
		return $vendorCSSLoc;
	}


	private function defaultJSVendorPlug($library, $name = null, $version = null){
		$env = strtolower(ENVIRONMENT);
		if($env == 'production' || $env == 'testing' && $name == NULL && $version == null){
			$js = "public/assets/{$library}/js/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL && $version == null){
			$js = "public/assets/{$library}/{$library}.{$name}/js/";
		}

		if($env == 'production' || $env == 'testing' && $name == NULL && $version != null){
			$js = "public/assets/{$library}/{$version}/js/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL && $version != null){
			$js = "public/assets/{$library}/{$library}.{$name}/{$version}/js/";
		}

		if($env == 'development' && $name == NULL && $version == null){			
			$js = url(). "public/assets/{$library}/js/";
		}

		if($env == 'development' && $name != NULL && $version == null){			
			$js = url(). "public/assets/{$library}/{$library}.{$name}/js/";
		}

		if($env == 'development' && $name == NULL && $version != null){			
			$js = url(). "public/assets/{$library}/{$version}/js/";
		}

		if($env == 'development' && $name != NULL && $version != null){			
			$js = url(). "public/assets/{$library}/{$library}.{$name}/{$version}/js/";
		}
		$vendorJSLoc = $js;
		return $vendorJSLoc;
	}

	private function defaultCSSVendorPlug($library, $name = null, $version = null){
		$env = strtolower(ENVIRONMENT);
		if($env == 'production' || $env == 'testing' && $name == NULL  && $version == null){
			$css = "public/assets/{$library}/css/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL  && $version == null){
			$css = "public/assets/{$library}/{$name}/css/";
		}


		if($env == 'production' || $env == 'testing' && $name == NULL  && $version != null){
			$css = "public/assets/{$library}/{$version}/css/";
		}

		if($env == 'production' || $env == 'testing' && $name != NULL  && $version != null){
			$css = "public/assets/{$library}/{$name}/{$version}/css/";
		}

		if($env == 'development' && $name == NULL  && $version == null){			
			$css = url()."public/assets/{$library}/css/";
		}

		if($env == 'development' && $name != NULL  && $version == null){			
			$css = url()."public/assets/{$library}/{$name}/css/";
		}

		if($env == 'development' && $name == NULL  && $version != null){			
			$css = url()."public/assets/{$library}/{$version}/css/";
		}

		if($env == 'development' && $name != NULL  && $version != null){			
			$css = url()."public/assets/{$library}/{$name}/{$version}/css/";
		}
		$vendorCSSLoc = $css;
		return $vendorCSSLoc;
	}
} // class