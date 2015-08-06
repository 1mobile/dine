<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Make{
	var $code = "";
    function __construct(){
    }
    function paramitize($params=array()){
    	$str = "";
    	foreach ($params as $param => $val) {
    		if($param != 'return'){
    			if($val != "")
	 				$str .= ' '.$param.'="'.$val.'" ';
	 			else
		 			$str .= " ".$param." ";
    		}
    	}
    	return $str;
    }
    function classitize($params=array(),$class=null){
    	if(isset($params['class']))
    		$params['class'] .= " ".$params['class']." ".$class." ";
    	else{
    		$params['class'] = " ".$class." ";
    	}
    	return $params;
    }
    function tag($tag=null,$text=null,$params=array(),$standAlone=false){
    	$str = "<".$tag." ";
    		$str .= $this->paramitize($params);
    	$str .= ">";
    	$str .= $text;
    	if(!$standAlone)
    		$str .= "</".$tag.">";
    	return $str;
    }
    function sTag($tag=null,$params=array()){
    	$str = "<".$tag." ";
    		$str .= $this->paramitize($params);
    	$str .= ">";
    	return $str;
    }
    function eTag($tag=null){
    	$str = "</".$tag.">";
    	return $str;
    }
    function returnitize($tags=array()){
    	$return = false;
    	if(isset($tags['return']))
    		$return = $tags['return'];
    	return $return;
    }
 	function code(){
		$code = $this->code;
		$this->clear();
		return $code;
	}
	function append($text=null){
		$this->code .= $text;
	}
	function clear(){
		$this->code = "";
	}
    /////////////////////////////////////////////////////////////
    /////	MAKE HTML CONTAINERS ///////////////////////////////
    ///////////////////////////////////////////////////////////
	    function sDiv($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eDiv($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sDivRow($params=array()){
	  		$str = "";
	  		$params = $this->classitize($params,'row');
	  		$str .= $this->sTag('div',$params);
	  		if($this->returnitize($params))
	  			 return $str;
	  		else
	  			$this->code .= $str;
	    }
	    function eDivRow($return=false){
	    	$str = $this->eTag('div');
	    	if($return)
	  			 return $str;
	  		else
	  			$this->code .= $str;
	    }
	    function sDivCol($length="12",$align="left",$offset=0,$params=array(),$return=false){
			$str = "";
			$off = "";
			if($offset > 0)
				$off = 'col-md-offset-'.$offset;
			$params = $this->classitize($params,"col-md-".$length." ".$off." text-".$align);
	  		$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eDivCol($return=false){
	    	$str = $this->eTag('div');
	    	if($return)
	  			 return $str;
	  		else
	  			$this->code .= $str;
	    }
	    function sBox($type='default',$params=array(),$return=false){
			$str = "";
			$params = $this->classitize($params,"box box-".$type);
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eBox($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sBoxHead($params=array()){
			$str = "";
			$params = $this->classitize($params,"box-header");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function boxTitle($text=null,$params=array()){
			$str = "";
			$params = $this->classitize($params,"box-title");
			$parama = $params;
			$parama['return'] = true;
			$str .= $this->H(3,$text,$parama);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eBoxHead($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sBoxBody($params=array()){
			$str = "";
			$params = $this->classitize($params,"box-body");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eBoxBody($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sBoxFoot($params=array()){
			$str = "";
			$params = $this->classitize($params,"box-footer");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eBoxFoot($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sPaper($params=array(),$return=false){
			$str = "";
			$params = $this->classitize($params,"invoice");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function ePaper($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
		function listGroup($lists=array(),$params=array()){
			$str = "";
			$params = $this->classitize($params," list-group ");
			$str .= $this->sTag('div',$params);
				if(is_array($lists)){
					foreach ($lists as $text => $opts) {
						$listParams = $this->classitize($opts," list-group-item ");
						if(isset($opts['href']))
							$href = $opts['href'];
						else
							$href = "#";
						$str .= $this->tag('a',$text,$listParams);
					}
				}
			$str .= $this->eTag('div');
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function sTab($params=array()){
			$str = "";
			$params = $this->classitize($params," nav-tabs-custom ");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTab($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
        function tabHead($tabs=array(),$active=null,$params=array(),$position_right=false){
			$str = "";
			$pos = "";
			if($position_right)
				$pos = 'pull-right';
			$params = $this->classitize($params," nav nav-tabs ".$pos." ");
			$str .= $this->sTag('ul',$params);
				if(is_array($tabs)){
					$ctr = 1;
					foreach ($tabs as $text => $opts) {
						if($text == "tab-title"){
							$liParams = array();
							$titpos = "pull-right";
							if($position_right)
								$titpos = 'pull-left';
							$liParams = $this->classitize($liParams,$titpos." header");
							$str .= $this->sTag('li',$liParams);
							$str .= $opts;
							$str .= $this->eTag('li');
						}
						else{
							$act = "";
							if($active == null){
								if($ctr == 1)
									$act = "active";
							}
							else{
								$act = $active;
							}
							$addDisbale = "";
							if(isset($opts['disabled']))
								$addDisbale='disabled';
							$liParams = array();
							$liParams = $this->classitize($liParams," ".$act." ".$addDisbale);
							$str .= $this->sTag('li',$liParams);
								if(!isset($opts['data-toggle']))
									$opts['data-toggle'] = "tab";
								if($addDisbale != "")
									unset($opts['data-toggle']);
								$str .= $this->tag('a',$text,$opts);
							$str .= $this->eTag('li');
							$ctr++;
						}
					}
				}
			$str .= $this->eTag('ul');
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function sTabBody($params=array()){
			$str = "";
			$params = $this->classitize($params," tab-content ");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTabBody($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sTabPane($params=array()){
			$str = "";
			$params = $this->classitize($params," tab-pane ");
			$str .= $this->sTag('div',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTabPane($return=false){
	    	$str = $this->eTag('div');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sUl($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('ul',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eUl($return=false){
	    	$str = $this->eTag('ul');
	    	if($return) return $str; else $this->code .= $str;
	    }
	  	function li($text=null,$params=array()){
			$str = "";
			$str .= $this->tag('li',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function sLi($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('li',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eLi($return=false){
	    	$str = $this->eTag('li');
	    	if($return) return $str; else $this->code .= $str;
	    }
		function sTable($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('table',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTable($return=false){
	    	$str = $this->eTag('table');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sTablehead($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('thead',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTableHead($return=false){
	    	$str = $this->eTag('thead');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sTableBody($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('tbody',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTableBody($return=false){
	    	$str = $this->eTag('tbody');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sRow($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('tr',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eRow($return=false){
	    	$str = $this->eTag('tr');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function sTd($params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('td',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eTd($return=false){
	    	$str = $this->eTag('td');
	    	if($return) return $str; else $this->code .= $str;
	    }
		function td($text=null,$params=array(),$return=false){
			$str = "";
			$str .= $this->tag('td',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function th($text=null,$params=array(),$return=false){
			$str = "";
			$str .= $this->tag('th',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function rBreak($text='&nbsp;',$params=array(),$return=false){
			$str = "";
			$str .= $this->sTag('tr',$params);
				$paramCell['colspan'] = "100%";
				$str .= $this->tag('td',$text,$paramCell);
			$str .= $this->eTag('tr');
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
	/////////////////////////////////////////////////////////////
    /////	MAKE HTML INPUTS     ///////////////////////////////
    ///////////////////////////////////////////////////////////
	    function sForm($action="",$params=array(),$method="POST"){
			$str = "";
			$params['method'] = $method;
			$params['action'] = $action;
			$params['role'] = 'form';
			$str .= $this->sTag('form',$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function eForm($return=false){
	    	$str = $this->eTag('form');
	    	if($return) return $str; else $this->code .= $str;
	    }
	    function input($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1=null,$icon2=null,$container=null){
	    	$str = "";

	    	if($container != null)
	    		$str .= $this->sTag('div',array('class'=>$container['class']));
			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'text';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null)
						$str .= $this->tag('span',$icon1,array('class'=>'input-group-addon'));
				}

				$params = $this->classitize($params,"form-control");
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null)
						$str .= $this->tag('span',$icon2,array('class'=>'input-group-addon'));
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');
			if($container != null)
				$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function inputWithBtn($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1=null,$icon2=null,$container=null){
	    	$str = "";

	    	if($container != null)
	    		$str .= $this->sTag('div',array('class'=>$container['class']));
			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'text';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null){
						$str .= $this->sTag('div',array('class'=>'input-group-btn'));
							$str .= $icon1;
						$str .= $this->eTag('div');
					}
				}

				$params = $this->classitize($params,"form-control");
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null){
						$str .= $this->sTag('div',array('class'=>'input-group-btn'));
							$str .= $icon2;
						$str .= $this->eTag('div');
					}
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');
			if($container != null)
				$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function pwdWithBtn($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1=null,$icon2=null,$container=null){
	    	$str = "";

	    	if($container != null)
	    		$str .= $this->sTag('div',array('class'=>$container['class']));
			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'password';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null){
						$str .= $this->sTag('div',array('class'=>'input-group-btn'));
							$str .= $icon1;
						$str .= $this->eTag('div');
					}
				}

				$params = $this->classitize($params,"form-control");
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null){
						$str .= $this->sTag('div',array('class'=>'input-group-btn'));
							$str .= $icon2;
						$str .= $this->eTag('div');
					}
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');
			if($container != null)
				$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function number($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1=null,$icon2=null,$container=null){
	    	$str = "";

	    	if($container != null)
	    		$str .= $this->sTag('div',array('class'=>$container['class']));
			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'text';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null)
						$str .= $this->tag('span',$icon1,array('class'=>'input-group-addon'));
				}

				$params = $this->classitize($params,"form-control no-decimal");
				// $params['decimal'] = $decimal;
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null)
						$str .= $this->tag('span',$icon2,array('class'=>'input-group-addon'));
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');
			if($container != null)
				$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function decimal($label=null,$nameID=null,$value=null,$placeholder=null,$decimal=2,$params=array(),$icon1=null,$icon2=null,$container=null){
	    	$str = "";

	    	if($container != null)
	    		$str .= $this->sTag('div',array('class'=>$container['class']));
			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'text';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null)
						$str .= $this->tag('span',$icon1,array('class'=>'input-group-addon'));
				}

				$params = $this->classitize($params,"form-control numbers-only");
				$params['decimal'] = $decimal;
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null)
						$str .= $this->tag('span',$icon2,array('class'=>'input-group-addon'));
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');
			if($container != null)
				$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function pwd($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1=null,$icon2=null,$container=null){
	    	$str = "";

	    	if($container != null)
	    		$str .= $this->sTag('div',array('class'=>$container['class']));
			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'password';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null)
						$str .= $this->tag('span',$icon1,array('class'=>'input-group-addon'));
				}

				$params = $this->classitize($params,"form-control");
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null)
						$str .= $this->tag('span',$icon2,array('class'=>'input-group-addon'));
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');
			if($container != null)
				$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function textbox($nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$str = "";
				if(!isset($params['type']))
					$params['type'] = 'text';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;
				$str .= $this->tag('input',null,$params,true);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function pwdbox($nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$str = "";
				if(!isset($params['type']))
					$params['type'] = 'password';
				if($nameID != null){
					if(!isset($params['id']))
						$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;
				$str .= $this->tag('input',null,$params,true);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function time($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1="<i class='fa fa-clock-o'></i>",$icon2=null){
	    	$str = "";

	    	// $str .= $this->sTag('div',array('class'=>'bootstrap-timepicker'));
		    	// $str .= $this->sTag('div',array('class'=>'form-group'));
		    	// 	$str .= $this->sTag('div',array('class'=>'input-group'));
		    // 			$params = $this->classitize(null,"timepicker form-control");
		    // 			$params['type'] = "text";
						// $str .= $this->tag('input',null,$params,true);
			   //  		if($label != null){
						// 	$labelParam = array();
						// 	if($nameID != null)
						// 		$labelParam['for'] = $nameID;
						// 	// $str .= $this->tag('label',$label,$labelParam);
						// }
						$str .= $this->input($label,$nameID,$value,$placeholder,array('class'=>'timepicker'),$icon1,$icon2,array('class'=>'bootstrap-timepicker'));
		    	// 	$str .= $this->eTag('div');
		    	// $str .= $this->eTag('div');
	    	// $str .= $this->eTag('div');

	    	if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function date($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon1="<i class='fa fa-fw fa-calendar'></i>",$icon2=null){
	    	$str = "";

			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['type']))
					$params['type'] = 'text';
				if($nameID != null){
					$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;
				if($value != null)
					$params['value'] = $value;

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null)
						$str .= $this->tag('span',$icon1,array('class'=>'input-group-addon'));
				}
				// $params['data-mask'] = "";
				// $params['data-inputmask'] = "'alias': 'mm/dd/yyyy'";

				$params = $this->classitize($params,"form-control pick-date");
				$str .= $this->tag('input',null,$params,true);

				if($icon1 != null || $icon2 != null){
					if($icon2 != null)
						$str .= $this->tag('span',$icon2,array('class'=>'input-group-addon'));
					$str .= $this->eTag('div');
				}
			$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function textarea($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$str = "";

			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if(!isset($params['rows']))
					$params['rows'] = '5';
				if($nameID != null){
					$params['id'] = $nameID;
					$params['name'] = $nameID;
				}
				if($placeholder != null)
					$params['placeholder'] = $placeholder;

				$params = $this->classitize($params,"form-control");
				$str .= $this->tag('textarea',$value,$params);

			$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }

	    function select($label=null,$nameID=null,$options=array(),$value=null,$params=array(),$icon1=null,$icon2=null){
	    	$str = "";

			$str .= $this->sTag('div',array('class'=>'form-group'));
				if($label != null){
					$labelParam = array();
					if($nameID != null)
						$labelParam['for'] = $nameID;
					$str .= $this->tag('label',$label,$labelParam);
				}

				if($nameID != null){
					$params['id'] = $nameID;
					$params['name'] = $nameID;
				}

				if($icon1 != null || $icon2 != null){
					$str .= $this->sTag('div',array('class'=>'input-group'));
					if($icon1 != null)
						$str .= $this->tag('span',$icon1,array('class'=>'input-group-addon'));
				}

				$params = $this->classitize($params,"form-control");
				$str .= $this->sTag('select',$params);
					if(count($options) > 0){
						foreach ($options as $text => $opt) {
							$optParam = array();
							if(is_array($opt)){
								$optParam = $opt;
								if(isset($optParam['value']) && $optParam['value'] == $value)
									$optParam['selected'] = "";
							}
							else{
								$optParam['value']=$opt;
								if($opt == $value)
									$optParam['selected'] = "";
							}

							$str .= $this->tag('option',$text,$optParam);
						}
					}
				$str .= $this->eTag('select');

				if($icon1 != null || $icon2 != null){
					if($icon2 != null)
						$str .= $this->tag('span',$icon2,array('class'=>'input-group-addon'));
					$str .= $this->eTag('div');
				}

			$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function checkbox($label=null,$nameID=null,$value=null,$params=array(),$checked=false){
	    	$str = "";
	    	if($label != null){
			$str .= $this->sTag('div',array('class'=>'form-group'));
				$str .= $this->sTag('div',array('class'=>'checkbox'));
					$str .= $this->sTag('label');
			}
						$params['type'] = 'checkbox';
						if($nameID != null){
							if(!isset($params['id']))
								$params['id'] = $nameID;
							$params['name'] = $nameID;
						}
						if($params != null)
	      	 				$params['value'] = $value;
	      	 			if($checked){
			            	$params['checked'] = "checked";
			            }
						$str .= $this->tag('input',$label,$params,true);

	    	if($label != null){
					$str .= $this->eTag('label');
				$str .= $this->eTag('div');
			$str .= $this->eTag('div');
			}
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function hidden($nameID=null,$value=null,$params=array()){
			if($nameID != null){
				$params['id'] = $nameID;
				$params['name'] = $nameID;
			}
			if($value != null)
				$params['value'] = $value;
			$params['type'] = 'hidden';
	    	$str = $this->tag('input',null,$params,true);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function file($nameID=null,$params=array()){
			if($nameID != null){
				$params['id'] = $nameID;
				$params['name'] = $nameID;
			}

			$params['type'] = 'file';
	    	$str = $this->tag('input',null,$params,true);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function button($text=null,$params=array(),$type='default'){
	    	$params = $this->classitize($params,"btn btn-".$type);
	    	$str = $this->tag('button',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function unbutton($text=null,$params=array()){

	    	$str = $this->tag('button',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function img($src=null,$params=array()){
			if($src != null)
				$params['src'] = $src;
	    	$str = $this->tag('img',null,$params,true);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	/////////////////////////////////////////////////////////////
    /////	MAKE HTML TEXT       ///////////////////////////////
    ///////////////////////////////////////////////////////////
	    function P($text=null,$params=array()){
			$str = "";
			$str .= $this->tag('p',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function span($text=null,$params=array()){
			$str = "";
			$str .= $this->tag('span',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function small($text=null,$params=array()){
			$str = "";
			$str .= $this->tag('small',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
	    function H($num=1,$text=null,$params=array()){
			$str = "";
			$str .= $this->tag('h'.$num,$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function A($text=null,$href=null,$params=array()){
			$str = "";
			if($href != null)
				$params['href'] = $href;
			$str .= $this->tag('a',$text,$params);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
	////////////////////////////////////////////////////////////
	/////	MAKE CUSTOM FUNCTIONS 	///////////////////////////
    //////////////////////////////////////////////////////////
		function carousel($id,$img=array(),$params=array()){
			$str = "";
			$params = $this->classitize($params,"carousel slide");
			$params['id'] = $id;
			$params['data-ride'] = 'carousel';

			$str .= $this->sTag('div',$params);
				$str .= $this->sTag('ol',array('class'=>'carousel-indicators'));
					$ctr = 0;
					foreach ($img as $url) {
						if($ctr == 0)
							$str .= $this->tag('li',null,array('data-target'=>'#'.$id,'data-slide-to'=>$ctr,'class'=>'active'));
						else
							$str .= $this->tag('li',null,array('data-target'=>'#'.$id,'data-slide-to'=>$ctr));
						$ctr++;
					}
				$str .= $this->eTag('ol');
				$str .= $this->sTag('div',array('class'=>'carousel-inner'));
					$ctr = 0;
					foreach ($img as $im) {
						$txt = '';
						if($ctr == 0)
							$txt = 'active';
						$str .= $this->sTag('div',array('class'=>'item '.$txt));
							$params['src'] = $im['url'];
							$params = array_merge($im['params'],$params);
							$str .= $this->tag('img',null,$params);
						$str .= $this->eTag('div');
						$ctr++;
					}
				$str .= $this->eTag('div');
				$str .= $this->tag('a','<span class="glyphicon glyphicon-chevron-left"></span>',array('class'=>'left carousel-control','href'=>'#'.$id,'data-slide'=>'prev'));
				$str .= $this->tag('a','<span class="glyphicon glyphicon-chevron-right"></span>',array('class'=>'right carousel-control','href'=>'#'.$id,'data-slide'=>'next'));
			$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function listLayout($thead=array(),$rows=array(),$params=array()){
			$str = "";

			$str .= $this->sTag('div',array('class'=>'table-responsive','style'=>'margin-top:10px;'));
				$params = $this->classitize($params,"table table-bordered table-striped data-table");
				$str .= $this->sTag('table',$params);
					$str .= $this->sTag('thead');
						$str .= $this->sTag('tr');
							foreach ($thead as $text => $opts) {
									$thParams = array();
									if(is_array($opts))
										$thParams = $opts;
									$str .= $this->tag('th',$text,$thParams);
							}
						$str .= $this->eTag('tr');
					$str .= $this->eTag('thead');
					$str .= $this->sTag('tbody');
						foreach($rows as $cells){
							$str .= $this->sTag('tr');
								foreach ($cells as $val) {
									$str .= $this->tag('th',$val);
								}
							$str .= $this->eTag('tr');
						}
					$str .= $this->eTag('tbody');
				$str .= $this->eTag('table');
			$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
		function progressBar($maxVal=100,$val=0,$percent=null,$minVal=0,$color="red",$params=array()){
			$str = "";
			$tagParam = $this->classitize($params,"progress");
			$str .= $this->sTag('div',$tagParam);

				// $params = $this->classitize($params,"progress-bar progress-bar-".$color);
				$params['class'] = "progress-bar progress-bar-".$color;
				$params['role'] = "progressbar";
				$params['aria-valuenow'] = $val;
				$params['aria-valuemin'] = $percent;
				$params['aria-valuemax'] = $maxVal;
				$per = getPercent($val,$maxVal);
				if(!is_null($percent))
					$per = $percent;
				$params['style'] = "width:".$per;

				$str .= $this->sTag('div',$params);
					$str .= $this->tag('span',$per,array('class'=>'sr-only'));
				$str .= $this->eTag('div');
			$str .= $this->eTag('div');

			if($this->returnitize($params)) return $str; else $this->code .= $str;
		}
	////////////////////////////////////////////////////////////
	/////	MAKE CUSTOM DROPDOWNS 	///////////////////////////
    //////////////////////////////////////////////////////////
		function yesOrNoDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$opts  = array();
				$opts['Yes'] = 'yes';
				$opts['No'] = 'no';
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function eachPackCaseoDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$opts  = array();
				$opts['Each'] = 'each';
				$opts['Pack'] = 'pack';
				$opts['Case'] = 'case';
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function suppliersDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array())
	    {
	    	$CI =& get_instance();
	    	$CI->load->model('site/site_model');
	    	$str = "";
	    		$selectParams = $params;
	    		if (!isset($selectParams['return']))
	    			$selectParams['return'] = true;

	    		$results = $CI->site_model->get_custom_val('suppliers',array('supplier_id,name'),null,null,true);

	    		$opts = array();
	    		foreach ($results as $val) {
	    			$opts[$val->name] = $val->supplier_id;
	    		}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
	    	if ($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function itemTypeDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array())
	    {
	    	$CI =& get_instance();
	    	$CI->load->model('site/site_model');
	    	$str = "";
	    		$selectParams = $params;
	    		if (!isset($selectParams['return']))
	    			$selectParams['return'] = true;

	    		$results = $CI->site_model->get_custom_val('item_types',array('id,type'),null,null,true);

	    		$opts = array();
	    		foreach ($results as $val) {
	    			$opts[$val->type] = $val->id;
	    		}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
	    	if ($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function itemSubcategoryDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array())
	    {
	    	$CI =& get_instance();
	    	$CI->load->model('site/site_model');
	    	$str = "";
	    		$selectParams = $params;
	    		if (!isset($selectParams['return']))
	    			$selectParams['return'] = true;

		    	$opts = array();
	    		if (!isset($selectParams['opts'])) {
	    			$results = $CI->site_model->get_custom_val('subcategories',array('sub_cat_id,code,name'),null,null,true);
		    		foreach ($results as $val) {
		    			$opts["[".$val->code."] ".$val->name] = $val->sub_cat_id;
		    		}
	    		}
	    		else
	    			$opts = $selectParams['opts'];
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
	    	if ($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function uomDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array())
	    {
	    	$CI =& get_instance();
	    	$CI->load->model('site/site_model');
	    	$str = "";
	    		$selectParams = $params;
	    		if (!isset($selectParams['return']))
	    			$selectParams['return'] = true;

	    		$results = $CI->site_model->get_custom_val('uom',array('id,code'),'inactive','0',true);

	    		$opts = array();
	    		foreach ($results as $val) {
	    			$opts[$val->code] = $val->code;
	    		}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
	    	if ($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
		function inactiveDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$opts  = array();
				$opts['Yes'] = 1;
				$opts['No'] = 0;
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function dayDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$opts  = array();
				$opts['Monday'] = 'mon';
				$opts['Tuesday'] = 'tue';
				$opts['Wednesday'] = 'wed';
				$opts['Thursday'] = 'thu';
				$opts['Friday'] = 'fri';
				$opts['Saturday'] = 'sat';
				$opts['Sunday'] = 'sun';
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
		function genderDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$opts  = array();
				$opts['Male'] = 'male';
				$opts['Female'] = 'female';
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function roleDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('user_roles',array('id,role'),null,null,true);
				$opts  = array();
				foreach ($results as $res) {
					$opts[$res->role] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function serviceTypeDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('service_types',array('id,service_type'),null,null,true);
				$opts  = array();
				foreach ($results as $res) {
					$opts[$res->service_type] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function menuSchedulesDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('menu_schedules',array('menu_sched_id,desc'),'inactive','0',true);
				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->desc] = array('value'=>$res->menu_sched_id);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function menuCategoriesDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('menu_categories',array('menu_cat_id,menu_cat_name'),'inactive','0',true);
				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->menu_cat_name] = array('value'=>$res->menu_cat_id);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function menuSubCategoriesDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('menu_subcategories',array('menu_sub_cat_id,menu_sub_cat_name'),'inactive','0',true);
				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->menu_sub_cat_name] = array('value'=>$res->menu_sub_cat_id);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function userDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array(),$icon=null,$icon2=null){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('users',array('id,fname,lname'),'inactive',0,true);
				$opts  = array();
				$opts['- Select an item -'] = 0;
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->fname.' '.$res->lname] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams,$icon=null,$icon2=null);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function monthsDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				// $results=$CI->site_model->get_custom_val('users',array('id,fname,lname'),null,null,true);
				$opts  = array();
				$opts['- Select an Month -'] = '';
				// if($placeholder != null)
				// 	$opts[$placeholder] = '';
				// foreach ($results as $res) {
				// 	$opts[$res->fname.' '.$res->lname] = $res->id;
				// }

				for ($m=1; $m<=12; $m++) {
			     	$month = date('F', mktime(0,0,0,$m, 1, date('Y')));
			     	$mon = date('m', mktime(0,0,0,$m, 1, date('Y')));
			     	$opts[$month] = $mon;
			    }
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function restoStaffDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('restaurant_staffs',array('staff_id,staff_name,access'),null,null,true);
				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->staff_name] = array('value'=>$res->staff_id,'access'=>$res->access);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function currenciesDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('currencies',array('currency,currency_desc'),'inactive','0',true);
				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->currency] = array('value'=>$res->currency);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function portionWholeDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$opts  = array();
				$opts['Whole'] = 'whole';
				$opts['Portion'] = 'portion';
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function restoTypeDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('restaurant_types',array('type_id,type_name'),null,null,true);
				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->type_name] = array('value'=>$res->type_id);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
		function categoriesDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('categories',array('cat_id, code, name'),'inactive',0,true);

				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts["[ ".$res->code." ] ".$res->name] = array('value'=>$res->cat_id);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function itemsDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('menus',array('menu_id, menu_code, menu_name'),'inactive',0,true);

				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts["[ ".$res->menu_code." ] ".$res->menu_name] = array('value'=>$res->menu_id);
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function locationsDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('locations',array('loc_id,loc_code,loc_name'),'inactive',0,true);

				$opts  = array();
				if($placeholder != null)
					$opts[$placeholder] = '';

				if (empty($selectParams['shownames'])) {
					foreach ($results as $res) {
						$opts["[ ".$res->loc_code." ] ".$res->loc_name] = array('value'=>$res->loc_id);
					}
				} else {
					foreach ($results as $res) {
						$opts["[ ".$res->loc_code." ] ".$res->loc_name] = array('value'=>$res->loc_id.'-'.$res->loc_name);
					}
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function userDrop2($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('users',array('id,fname,lname'),null,null,true);
				$opts  = array();
				$opts['Select User'] = 0;
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->fname.' '.$res->lname] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function scheduleDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('dtr_shifts',array('id,code,description'),null,null,true);
				$opts  = array();
				$opts['Select Schedule'] = 0;
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->code] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function terminalDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('terminals',array('terminal_id,terminal_code,terminal_name'),null,null,true);
				$opts  = array();
				$opts['All Terminal'] = 0;
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->terminal_code] = $res->terminal_id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function posTerminalsDrop($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('terminals',array('terminal_id,terminal_code,terminal_name'),null,null,true);
				$opts  = array();
				// $opts['All Terminal'] = 0;
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->terminal_code] = $res->terminal_id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function userDropSearch($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('users',array('id,fname,lname'),'role',3,true);
				$opts  = array();
				$opts['- Select Employee -'] = 0;
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->fname.' '.$res->lname] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
	    function userDropSearch2($label=null,$nameID=null,$value=null,$placeholder=null,$params=array()){
	    	$CI =& get_instance();
	 		$CI->load->model('site/site_model');
	    	$str = "";
				$selectParams = $params;
				if(!isset($selectParams['return']))
					$selectParams['return'] = true;

				$results=$CI->site_model->get_custom_val('users',array('id,fname,lname'),'role',3,true);
				$opts  = array();
				$opts['Select Employee'] = '';
				if($placeholder != null)
					$opts[$placeholder] = '';
				foreach ($results as $res) {
					$opts[$res->fname.' '.$res->lname] = $res->id;
				}
				$str .= $this->select($label,$nameID,$opts,$value,$selectParams);
			if($this->returnitize($params)) return $str; else $this->code .= $str;
	    }
}
