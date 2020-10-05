<?php
require_once './html2pdf/html2pdf.class.php';
$my_html='<table id="permitPDF" border="2">
                <tr>
                    <td colspan="3" align="center">
                    <B><H2>SINGLE BUSINESS PERMIT</H2></B>                   
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="3">
                        <img src="images/CountyLogo.jpg" alt="County Logo">
                    </td>
                </tr>
		</table>';

		$target = 'C:\inetpub\wwwroot\RevenueAdmin\\test.pdf';		
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 50));		
        $html2pdf->WriteHTML($my_html);
        $html2pdf->Output($target, 'F');
?>
