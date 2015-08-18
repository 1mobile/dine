<?php
function paginate($url='#',$total_rows,$limit,$pagi){
	$CI =& get_instance();
	
		if($pagi != null){$page = (int)$pagi;}else{$page = 1;}
        if($pagi < 0 ){$page = 1;}elseif($pagi > $total_rows){$page = $total_rows;}
        $limits = array($limit,($page - 1) * $limit);


		$adjacents = 3;
		if ($page <= 0) $page = 1;
		$prev = $page - 1;	
		$next = $page + 1;
		$lastpage = ceil($total_rows/$limit);
		$lpm1 = $lastpage - 1;

		if($lastpage > 1){
			$CI->make->sUl(array('class'=>"pagination",'style'=>'margin:0;float:right'));
				if($page > 1){
					$CI->make->sLi();
						$CI->make->A('&laquo;',$url."/".$prev,array('class'=>'ragi','pagi'=>$prev));
					$CI->make->eLi();
				}
				else{
					$CI->make->sLi(array('class'=>'disabled'));
						$CI->make->A('&laquo;',$url."/".$prev,array('class'=>'ragi','pagi'=>$prev));
					$CI->make->eLi();
				}

				if ($lastpage < 7 + ($adjacents * 2)){
					for ($counter = 1; $counter <= $lastpage; $counter++){
						if ($counter == $page){
							$CI->make->sLi(array('class'=>'disabled'));
								$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
							$CI->make->eLi();
						}
						else{
							$CI->make->sLi();
								$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
							$CI->make->eLi();					
						}
					}
				}
				elseif($lastpage > 5 + ($adjacents * 2)){
					if($page < 1 + ($adjacents * 2)){
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
							if ($counter == $page){
								$CI->make->sLi(array('class'=>'disabled'));
									$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
								$CI->make->eLi();
							}
							else{
								$CI->make->sLi();
									$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
								$CI->make->eLi();					
							}
						}
						$CI->make->sLi(array('class'=>'disabled'));
							$CI->make->A('...','#',array());
						$CI->make->eLi();
						$CI->make->sLi();
							$CI->make->A($lastpage-1,$url."/".$lastpage-1,array('class'=>'ragi','pagi'=>$lastpage-1));
						$CI->make->eLi();
						$CI->make->sLi();
							$CI->make->A($lastpage,$url."/".$lastpage,array('class'=>'ragi','pagi'=>$lastpage));
						$CI->make->eLi();		
					}
					#
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
						$CI->make->sLi();
							$CI->make->A(1,$url."/1",array('class'=>'ragi','pagi'=>1));
						$CI->make->eLi();
						$CI->make->sLi();
							$CI->make->A(2,$url."/2",array('class'=>'ragi','pagi'=>2));
						$CI->make->eLi();
						$CI->make->sLi(array('class'=>'disabled'));
							$CI->make->A('...','#',array());
						$CI->make->eLi();
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
							if ($counter == $page){
								$CI->make->sLi(array('class'=>'disabled'));
									$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
								$CI->make->eLi();
							}
							else{
								$CI->make->sLi();
									$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
								$CI->make->eLi();					
							}					
						}
						$CI->make->sLi(array('class'=>'disabled'));
							$CI->make->A('...','#',array());
						$CI->make->eLi();
						$CI->make->sLi();
							$CI->make->A($lastpage-1,$url."/".$lastpage-1,array('class'=>'ragi','pagi'=>$lastpage-1));
						$CI->make->eLi();
						$CI->make->sLi();
							$CI->make->A($lastpage,$url."/".$lastpage,array('class'=>'ragi','pagi'=>$lastpage));
						$CI->make->eLi();
					}
					#
					else{
						$CI->make->sLi();
							$CI->make->A(1,$url."/1",array('class'=>'ragi','pagi'=>1));
						$CI->make->eLi();
						$CI->make->sLi();
							$CI->make->A(2,$url."/2",array('class'=>'ragi','pagi'=>2));
						$CI->make->eLi();
						$CI->make->sLi(array('class'=>'disabled'));
							$CI->make->A('...','#',array());
						$CI->make->eLi();
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
							if ($counter == $page){
								$CI->make->sLi(array('class'=>'disabled'));
									$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
								$CI->make->eLi();
							}
							else{
								$CI->make->sLi();
									$CI->make->A($counter,$url."/".$counter,array('class'=>'ragi','pagi'=>$counter));
								$CI->make->eLi();					
							}						
						}
					}
					#	
				}	

				if($page < $counter - 1){
					$CI->make->sLi();
						$CI->make->A('&raquo;',$url."/".$next,array('class'=>'ragi','pagi'=>$next));
					$CI->make->eLi();
				}
				else{
					$CI->make->sLi(array('class'=>'disabled'));
						$CI->make->A('&raquo;',$url."/".$next,array('class'=>'ragi','pagi'=>$next));
					$CI->make->eLi();
				}
			$CI->make->eUl();			
		}

	$code = $CI->make->code();
	return array('code'=>$code,'limit'=>$limits);
}

?>