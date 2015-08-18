<?php
function makeLoginPage($error = null,$unclosed_shifts=array(),$rotten_shifts=array(),$end_shift=false,$splashes=array()){
	$CI =& get_instance();
		if($end_shift){
			$CI->make->hidden('shift_end',$end_shift);
		}
		$CI->make->sDiv(array('class'=>'pos-wrapper'));
			$CI->make->sDivRow();
			// ,'style'=>'background-color:#fff'
				$CI->make->sDivCol(7,'left',0,array('class'=>'no-spaces full-height bg-kinda-white'));
					$CI->make->sDiv(array('class'=>'nav-bar bg-darker-red no-spaces'));
						$CI->make->img(base_url().'img/clickLogo.png',array('class'=>'logo'));
						
						$CI->make->sDivRow();
							$CI->make->sDivCol();
							$CI->make->sDiv(array('style'=>'margin:30px;'));
							$CI->make->sBox('default',array('class'=>'box-solid'));
								$CI->make->sBoxBody();
									// $img = array(
									// 		array('url'=>base_url().'img/splashPages/splashPage1.png','params'=>array('style'=>'height:500px;width:750px;')),
									// 		array('url'=>base_url().'img/splashPages/splashPage2.png','params'=>array('style'=>'height:500px;width:750px;')),
									// 		array('url'=>base_url().'img/splashPages/splashPage3.png','params'=>array('style'=>'height:500px;width:750px;')),
									// 		array('url'=>base_url().'img/splashPages/splashPage4.png','params'=>array('style'=>'height:500px;width:750px;')),
									// 		// array('url'=>base_url().'img/splashPages/splashPage5.png','params'=>array('style'=>'height:500px;width:750px;')),
									// 		// array('url'=>base_url().'img/splashPages/splashPage6.png','params'=>array('style'=>'height:500px;width:750px;'))
									// );
									foreach ($splashes as $res) {
										$src ="data:image/jpeg;base64,".base64_encode($res->img_blob);
										$img[] = array(
											'url'=>$src,'params'=>array('style'=>'height:500px;width:750px;')
										);
									}
									if(count($splashes) > 0){
										$CI->make->carousel('carousel',$img);
									}
								$CI->make->eBoxBody();
							$CI->make->eBox();
							$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();

					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(5,'left',0,array('class'=>'bg-dark-red full-height'));
					$marg = 80;
					if($error != null){
						$marg=30;
						$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
							$CI->make->sDivCol();
								$CI->make->append('<div class="alert alert-warning alert-dismissable" style="margin:10px;">
		                                        <i class="fa fa-warning"></i>
		                                        <b>Warning!</b> '.$error.'
		                                    </div>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					$CI->make->sDivRow(array('style'=>'margin-top:'.$marg.'px;'));
						$CI->make->sDivCol(3);
							if(empty($rotten_shifts)){
								$CI->make->sDiv(array('act'=>'#loginPin','class'=>'login-by tsc_awb_large tsc_awb_silver tsc_flat'));
									$CI->make->img(base_url().'img/Passcode.png',array('style'=>'width:60px;'));
								$CI->make->eDiv();
							}


							
							// $CI->make->sDiv(array('act'=>'#loginUsPwd','class'=>'login-by tsc_awb_large tsc_awb_silver  tsc_flat'));
							// 	$CI->make->img(base_url().'img/pinCodeLogIn.png',array('style'=>'width:60px;'));
							// $CI->make->eDiv();

							if(!empty($rotten_shifts)){
								foreach ($rotten_shifts as $res) {
									$CI->make->sDiv(array('act'=>'#loginPin','user'=>$res->id,'name'=>ucwords($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix),'class'=>'login-by rot-login-by tsc_awb_large tsc_awb_orange tsc_flat'));
										$CI->make->img(base_url().'img/avatar.jpg',array('style'=>'height:40px;'));	
										$CI->make->H(5,substr($res->username,0,10).'...');								
									$CI->make->eDiv();
								}								
							}
							foreach ($unclosed_shifts as $res) {
								$CI->make->sDiv(array('act'=>'#loginPin','user'=>$res->id,'name'=>ucwords($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix),'class'=>'login-by tsc_awb_large tsc_awb_white tsc_flat'));
									$CI->make->img(base_url().'img/avatar.jpg',array('style'=>'height:40px;'));	
									$CI->make->H(5,substr($res->username,0,10).'...');								
								$CI->make->eDiv();
							}
							// $CI->make->sDiv(array('id'=>"training",'class'=>'login-by2 tsc_awb_large tsc_awb_silver  tsc_flat'));
							// 	$CI->make->img(base_url().'img/training-icon.png',array('style'=>'width:70px;'));
							// $CI->make->eDiv();
							// $CI->make->sDiv(array('id'=>'turn-off-btn','class'=>'tsc_awb_large tsc_awb_silver  tsc_flat'));
							// 	$CI->make->H(3,fa('fa-power-off fa-2x fa-fw'),array('style'=>'margin:0px;margin-top:10px;'));
							// $CI->make->eDiv();
							// $CI->make->sDiv(array('class'=>'login-by tsc_awb_large tsc_awb_silver  tsc_flat'));
							// $CI->make->eDiv();
							// $CI->make->sDiv(array('class'=>'login-by tsc_awb_large tsc_awb_silver  tsc_flat'));
							// $CI->make->eDiv();

						$CI->make->eDivCol();

						$CI->make->sDivCol(8,'left',0,array('id'=>'loginUsPwd','class'=>'logins','style'=>'margin-top:10px;display:none;'));
							$CI->make->sForm("site/go_login",array('id'=>'uname-login-form'));
								$CI->make->input(null,'username',null,'USERNAME',array('class'=>'rOkay login-input'));	
								$CI->make->pwd(null,'password',null,'PASSWORD',array('class'=>'rOkay login-input'));	
								$CI->make->unbutton('Enter',array('id'=>'uname-login','class'=>'login-btn tsc_awb_large tsc_flat tsc_awb_green'));
							$CI->make->eForm();
						$CI->make->eDivCol();
						
						$CI->make->sDivCol(9,'left',0,array('id'=>'loginPin','class'=>'logins'));
							$CI->make->H(3,"",array('id'=>'pin-user','style'=>'display:none;color:#fff;margin-left:40px;margin-top:0px;'));
							$CI->make->hidden('pin-id');
							$CI->make->append(onScrNumPwdPad('pin','pin-login'));
						$CI->make->eDivCol();

						$CI->make->eDivRow();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();

		$CI->make->sDiv(array('id'=>'wrap'));
		$CI->make->eDiv();
		
	return $CI->make->code();
}
function makeZreadAutoPage(){
	$CI =& get_instance();
		$CI->make->H(4,'Processing Recent Failed Sent files Please wait...'.fa('fa-spinner fa-spin'));
		$CI->make->H(4,null,array('class'=>'ztxt'));
	return $CI->make->code();
}
function robFiles($list = array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
								// 'Code'=>'',
								'File'=>'',
								'Date Created'=>'',
								'Date Last Sent'=>'',
								'Sent'=>'',
								''=>array('width'=>'10%','align'=>'right')
							);
							$rows = array();
							foreach ($list as $val) {
								$link = "";
								$link .= $CI->make->A(fa('fa-envelope fa-lg fa-fw'),base_url().'reads/send_to_rob_man/'.$val->id,array('return'=>'true'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								$rows[] = array(
									// $val->code,
									$val->code,
									sql2Date($val->date_created),
									sql2Date($val->last_update),
									($val->inactive == 0 ? 'Sent' : 'Not Sent'),
									$link
								);
							}
							$CI->make->listLayout($th,$rows);
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
?>