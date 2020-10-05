<?php

class Salamu{
	var $msg;
	
	function Hi($language){
		if($language=="kalenjin"){
			$$this->$msg="Chamgei Tugul";
		}else if($language=="kikuyu"){
			$this->$msg="uhoro waku";
		}else if($language=="meru"){
			$this->$msg="muuga";
		}else if($language=="kamba"){
			$this->$msg="wamuka ata";
		}else if($language=="kisii"){
			$this->$msg="bwakiire";
		}else if($language=="luo"){
			$this->$msg="ichiewo Nade";
		}else{
			$this->$msg="Foreigner";
		}
		
		return $this->$msg;
	}
}


?>