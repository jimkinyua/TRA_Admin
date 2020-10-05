<?php
require 'DB_PARAMS/connect.php';
if (isset($_SESSION["ContactID"])) 
{ 
	$ContactID = $_SESSION["ContactID"];
}
?>
<div class="example">
<legend>Departments</legend>
 <table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
       <tr>
          <th width="27%" class="text-left">Department Name</th>
          <th width="43%" class="text-left">Description</th>
          <th width="43%" class="text-left">Created By</th>
          <th width="22%" class="text-left">Created Date</th>
          <th width="4%" class="text-left">&nbsp;</th>
          <th width="4%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
    <tbody>
       <tr>
          <td class="text-left">&nbsp;</td>
          <td class="text-left">&nbsp;</td>
          <td class="text-left">&nbsp;</td>
          <td class="text-left">&nbsp;</td>
          <td class="text-left">&nbsp;</td>
          <td class="text-center">&nbsp;</td>
       </tr>
    </tbody>
    <tfoot>
       <tr>
          <th class="text-left">Department Name</th>
          <th class="text-left">Description</th>
          <th class="text-left">Created By</th>
          <th class="text-left">Created Date</th>
          <th class="text-left">&nbsp;</th>
          <th class="text-left">&nbsp;</th>
       </tr>
    </tfoot>
  </table>
</div>
