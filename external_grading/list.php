<?php
  require 'DB_PARAMS/connect.php';

   
    if(isset($_POST['search'])) {
        $searchitem = $_POST['search']; 
    }
// print_r($_POST);exit();

  $sql= "select c.CustomerName,c.CustomerID, c.Website, c.BusinessZone, c.Email, c.Mobile1, sh.ServiceID, s.ServiceName 
          from ServiceHeader sh 
          join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
          join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
          join Customer c on c.CustomerID = sh.CustomerID 
          left join InspectionComments ic on ic.InspectionID = ins.InspectionID 
          join SubSystems sbs on sbs.SubSystemID = c.BusinessZone
          join Services s on s.ServiceID = sh.ServiceID 
          join ServiceCategory sc on sc.ServiceCategoryID = sh.ServiceCategoryID
          where sc.ServiceGroupID = 11 and ServiceStatusID = 4 Group By c.CustomerName,c.CustomerID,
          c.Website,c.BusinessZone,c.Email,c.Mobile1,sh.ServiceID,s.ServiceName order by NEWID()
          "; 
// exit($sql);


       $result = sqlsrv_query($db, $sql);
                        
            ?>
            <!DOCTYPE html>
            <html>
            <head>

              <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
              <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  
              <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script> -->
              <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
              <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

              <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
              <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
              <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
              <title>List of Classified Hotels - Kenya</title>
            </head>

          <div class="container">
            <nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
            <div class="container"><a class="navbar-brand js-scroll-trigger" href="index.php"><img src="assets/img/logo1.png" alt="TRA logo"></a>
                <button class="navbar-toggler navbar-toggler-right font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">Menu <i class="fas fa-bars"></i></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#Grades">#</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#portfolio">#</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#about">#</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">#</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <header class="masthead bg-primary text-white text-center">
            
        </header>
                  <br><br><br><br><br><br>
                  <div class="bg-info clearfix">
                      <button type="button" class="btn btn-secondary float-left">TRA</button>
                      <h3 style="text-align: center;">Graded Establishments</h3>
                      <button type="button" class="btn btn-secondary float-right">TRA</button>
                    </div>
                    <br><br>
            
<!-- 
             <form class="form-inline" name="search" method="post" action="">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
             </form> -->
             
             
            <?php
          $rows = sqlsrv_has_rows($result);
          if($rows ==false){
           echo 'No Graded Establishments Found!'; 
          }else{
            ?>
 
 

 <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">All</a>
  </li>
<!--   <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">5 Star</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">4 Star</a>
  </li> -->
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

  
  <table class="table table-striped" id="example" style="border: 4px solid green; border-radius:25px; -moz-border-radius:6px;">
               <thead>
                <tr>
                 <th>Hotel Name</th>
                 <th>Establishment Type</th>
                 <th>Rating</th>
                 <th>Location</th>
                 <th>Mobile</th>
                 <th>Website</th>
                 </tr>
               </thead>
               <?php
           

            while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                            $CustomerName = $row['CustomerName'];
                            $Website = $row['Website'];
                            $BusinessZone = $row['BusinessZone'];
                            $Email = $row['Email'];
                            $Mobile1 = $row['Mobile1'];
                            $ServiceName = $row['ServiceName'];
                            $ServiceID = $row['ServiceID'];
                            $CustomerID = $row['CustomerID'];
                ?>
                <tr>
                  <td><?php echo $CustomerName; ?></td>
                  <td><?php echo $ServiceName?></td>
                    <?php
                    $ratingsql = " select distinct top 1 ic.AverageScore,ins.InspectionID
                      from ServiceHeader sh 
                      join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
                      join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
                      join Customer c on c.CustomerID = sh.CustomerID 
                      join InspectionComments ic on ic.InspectionID = ins.InspectionID 
                      left join Services s on s.ServiceID = sh.ServiceID
                      join ServiceCategory sc on sc.ServiceCategoryID = sh.ServiceCategoryID
                      where sc.ServiceGroupID = 11 and ServiceStatusID = 4 and c.CustomerID = $CustomerID order by InspectionID desc";
                      // exit($ratingsql);
                      $rating_result = sqlsrv_query($db, $ratingsql);

                      while($ratingrow=sqlsrv_fetch_array($rating_result,SQLSRV_FETCH_ASSOC)){
                        $Rating = $ratingrow['AverageScore'];
                      }

                if($Rating == ''){
                  ?><td>The Rating Has Not Been Set</td><?php
                }else{

              $tr_sql = "select * from Rating where ServiceID = $ServiceID";
             // exit($tr_sql);
             $tr_result = sqlsrv_query($db, $tr_sql);

            while($omrow=sqlsrv_fetch_array($tr_result,SQLSRV_FETCH_ASSOC)){
              $trServiceID = $omrow['ServiceID'];
            }
                    if($ServiceID == $trServiceID){
              
             $r_sql = "select * from Rating where ServiceID=$trServiceID and MinRatingScore<=$Rating and MaxRatingScore>=$Rating";
             // exit($r_sql);
             $r_result = sqlsrv_query($db, $r_sql);

            while($omrow=sqlsrv_fetch_array($r_result,SQLSRV_FETCH_ASSOC)){
              $rServiceID = $omrow['ServiceID'];
              $MinRatingScore = $omrow['MinRatingScore'];
              $MaxRatingScore = $omrow['MaxRatingScore'];
              $RatingName = $omrow['RatingName'];

            }
            $omrow=sqlsrv_has_rows($r_result);
            if($omrow == false){
                        ?><td>Technical Issue</td><?php
            }else{

                        ?><td>

                           <?php
                                $StarRate1 = '1 Star'; 
                                $StarRate2 = '2 Star';
                                $StarRate3 = '3 Star';
                                $StarRate4 = '4 Star';
                                $StarRate5 = '5 Star';

                                if($StarRate1 == trim($RatingName)){
                                    ?>
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <?php
                                }elseif($StarRate2 == trim($RatingName)){
                                   ?>
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <?php
                                }elseif($StarRate3 == trim($RatingName)){
                                   ?>
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <?php
                                }elseif($StarRate4 == trim($RatingName)){
                                   ?>
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <?php
                                }elseif($StarRate5 == trim($RatingName)){
                                   ?>
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <img src="assets/img/star.png" width="20" height="15">
                                    <?php
                                }
                                ?>
                                <br><br><p style="font-size: 10px;"><strong>(<?php echo $RatingName; ?>)</strong></p>
                          </td><?php
                   } 
                  }else{
                    ?><td style="color:red;">Rating Has Not Been Set</td><?php
                  } 
                  }                
                  ?>
                  
                  <?php 
                  $locationSql = "select * from SubSystems where SubSystemID = $BusinessZone";
                  $locationresult = sqlsrv_query($db, $locationSql);
                  while($row=sqlsrv_fetch_array($locationresult,SQLSRV_FETCH_ASSOC)){
                    $SubSystemName = $row['SubSystemName'];
                  }
                  
                  ?>
                  <td><?php echo $SubSystemName; ?></td>
                  <td><?php echo $Mobile1; ?></td>
                  <td>
                    <?php 
                    if($Website != NULL){
                      ?>
                      <a href="http://<?php echo $Website; ?>" target="_blank"><?php echo $Website; ?></a>
                      <?php
                    }else{
                      ?>
                      <p>Website will be updated!</p>
                      <?php
                    }
                    ?>
                  </td>
                </tr>
                <?php
              }
            }
        ?>
        </table>
        <script type="text/javascript">
          $(document).ready(function() {
          $('#example').DataTable( {
              "pagingType": "full_numbers"
              } );
          } );
        </script>


  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Is </div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Emmanuel</div>
</div>

            
      </div>
    </body>
  </html>

        