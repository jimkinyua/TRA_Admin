<?php
  require 'DB_PARAMS/connect.php';

   
    if(isset($_POST['search'])) {
        $searchitem = $_POST['search']; 
}
// print_r($_POST);exit();

  $sql= "select c.CustomerName, sum(cr.ParameterScore) as
    Rating, c.Website, c.PhysicalAddress, c.Email, c.Mobile1 from
  ServiceHeader sh join Inspections ins 
  on sh.ServiceHeaderID = ins.ServiceHeaderID 
  join ChecklistResults cr 
  on cr.InspectionID = ins.InspectionID 
  join Customer c on c.CustomerID = sh.CustomerID 
  where ServiceID = 2074 and ServiceStatusID = 4  and c.CustomerName like '%$searchitem%' Group By
 c.CustomerName, c.Website, c.PhysicalAddress, c.Email, c.Mobile1 order by NEWID()"; 


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
          $rows = sqlsrv_has_rows($result );
          if($rows ==false){
           echo 'The establisment was not found!'; 
          }else{
            ?>
            
            <table class="table table-striped" id="example">
               <thead>
                <tr>
                 <th>Hotel Name</th>
                 <th>Rating</th>
                 <th>Location</th>
                 <th>Email</th>
                 <th>Website</th>
                 </tr>
               </thead>
               <?php
            while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                            $CustomerName = $row['CustomerName'];
                            $Rating = $row['Rating'];
                            $Website = $row['Website'];
                            $Location = $row['Location'];
                            $Email = $row['Email'];
                            $Mobile1 = $row['Mobile1'];
                ?>
                <tr>
                  <td><?php echo $CustomerName; ?></td>
                  <td><?php echo $Rating; ?></td>
                  <td><?php echo $Location; ?></td>
                  <td><?php echo $Email; ?></td>
                  <td><?php echo $Website; ?></td>
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
    </body>
  </html>

        