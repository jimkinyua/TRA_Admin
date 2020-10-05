<?php



?>
<script src="scripts.js"></script>
<style>
	body{
		margin: 0;
		padding: 0;
		padding-left: 200px
	}

	#reportslist
	{
		position: relative;
		background-color: rgb(235,235,235);
		top: 0;
		left: 0;
		height: 300%;
		width: 200px;
		border: 0;
		border-right: 1px solid gray;
	}

	#display
	{
		position: relative;
		background-color: rgb(235,235,235);
		top: 0;
		left: 0;
		border: 0;
		height: 100%;
		width: 80%;
		border: 0;
		border-right: 1px solid gray;
	}

</style>


	<div class="example">
			

		<iframe id="reportslist" src="reportslist.php">
			<?php
			echo '<script type="text/javascript">',
			     'testKitu();',
			'</script>';
			?>
			<h3>Menu Frame</h3>
		</iframe>
		<iframe id="display" name="reports" align="right">
			<h3>Menu Frame</h3>
		</iframe>
	</div>