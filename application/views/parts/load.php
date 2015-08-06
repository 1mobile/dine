
<?php 
	if(isset($add_css)){
		if(is_array($add_css)){
			foreach ($add_css as $val) {
				echo '<link href="'.base_url().$val.'" rel="stylesheet">';
			}
		}
		else
			echo '<link href="'.base_url().$add_css.'" rel="stylesheet">';
	}
	if(isset($add_js)){
		if(is_array($add_js)){
		  foreach ($add_js as $val) {
		    echo '<script src="'.base_url().$val.'" type="text/javascript"  language="JavaScript"></script>';
		  }
		}
		else
		  echo '<script src="'.base_url().$add_js.'" type="text/javascript"  language="JavaScript"></script>';
	}

	echo '<script src="'.base_url().'js/initial.js" type="text/javascript"  language="JavaScript"></script>';
	echo '<script src="'.base_url().'js/helper.js" type="text/javascript"  language="JavaScript"></script>';

	echo "<span id='test'></span>";
	echo $code; 
?>
