<?php
	
?>
<div class="fluent-menu" data-role="fluentmenu">
	<ul class="tabs-holder">
		<li class="special"><a href="#" onClick="loadpage('login.php?i=1','content')">Home</a></li>
		<li class="active"><a href="#tab_support">Support</a></li>
	</ul>

	<div class="tabs-content">
		<div class="tab-panel" id="tab_support">
			<div class="tab-panel-group">
				<div class="tab-group-content">						
					<button class="fluent-big-button" onClick="loadpage('aboutus.php?i=1','content')">
						<span class="icon-accessibility fg-darkBlue"></span>
                        <span class="button-label fg-darkBlue">About Us</span>
					</button>
					<button class="fluent-big-button" onClick="loadpage('help.php?1=1','content')">
						<span class="icon-help-2 fg-darkBlue"></span>
                        <span class="button-label fg-darkBlue">Help</span>
					</button>  
				</div>                                                 
				<div class="tab-group-caption fg-darkBlue">Assistance</div>                       
			</div>                
		</div>                                                                
	</div>
</div>