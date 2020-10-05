<?php
// Require the RazorFlow php wrapper
require('razorflow_php/razorflow.php');
// You can rename the "MyDashboard" class to anything you want

class MyDashboard extends StandaloneDashboard {
    public function buildDashboard () {
        // Build your dashboard here.

    }
}

$dashboard = new MyDashboard ();
$dashboard->renderStandalone ();
?>