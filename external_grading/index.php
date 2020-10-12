<?php
require 'DB_PARAMS/connect.php';

    if(isset($_POST['search'])) {
        $searchitem = $_POST['search']; 
}

  $sql= "select top 6 c.CustomerName, sum(cr.ParameterScore) as
    Rating, c.Website, c.PhysicalAddress, c.Email, c.Mobile1 from
  ServiceHeader sh join Inspections ins 
  on sh.ServiceHeaderID = ins.ServiceHeaderID 
  join ChecklistResults cr 
  on cr.InspectionID = ins.InspectionID 
  join Customer c on c.CustomerID = sh.CustomerID 
  where ServiceID = 2074 and ServiceStatusID = 4  and c.CustomerName like '%$searchitem%' Group By
 c.CustomerName, c.Website, c.PhysicalAddress, c.Email, c.Mobile1 order by NEWID()"; 
    // echo $sql;exit;
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>TRA Portal</title>
        <!-- Font Awesome icons (free version)-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet">
        <!-- Fonts CSS-->
        <link  href="css/heading.css" rel="stylesheet">
        <link  href="css/body.css" rel="stylesheet">
        <link  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" rel="stylesheet" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>
    <body id="page-top">
        <nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
            <div class="container"><a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="assets/img/logo1.png" alt="TRA logo"></a>
                <button class="navbar-toggler navbar-toggler-right font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">MENU <i class="fas fa-bars"></i></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#Grades">GRADED ESTABLISHMENTS</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#portfolio">GET GRADED</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#about">ABOUT</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">CONTACT</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <header class="masthead bg-primary text-white text-center">
            <div class="container d-flex align-items-center flex-column">
                <!-- Masthead Avatar Image--><img class="masthead-avatar mb-5" src="assets/img/avataaars.svg" alt="">
                <!-- Masthead Heading-->
                <h1 class="masthead-heading mb-0">Tourism Regulatory Authority</h1>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Masthead Subheading-->
                <p class="pre-wrap masthead-subheading font-weight-light mb-0">Championing Quality and Excellence!</p>
            </div>
        </header>





<section class="page-section portfolio" id="Grades">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <div class="text-center">
                    <h2 class="page-section-heading text-secondary mb-0 d-inline-block">GRADED ESTABLISHMENT</h2>
                </div>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div></di
                    <div class="divider-custom-icon"><i class="fas fa-star"></i>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Portfolio Grid Items-->
                <div class="row justify-content-center">
                    

<!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Check Out!</strong> You should check some of the graded establishments below.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button> -->
</div>


<nav class="navbar navbar-light bg-light">
  <form class="form-inline" name="search" method="post" action="">
    <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
  </form>
  <button type="button" class="btn btn-info" onclick="location.href='list.php'">View All Classified Establishments</button>
</nav>


                    <?php 
                        $result = sqlsrv_query($db, $sql);
                        
                            $rows = sqlsrv_has_rows($result );
                            if($rows ==false){
                               echo 'The establisment was not found!'; 
                            }else{
                            ?>
                            <div class="bg-info clearfix">
                      <button type="button" class="btn btn-secondary float-left">TRA</button>
                      <h3 style="text-align: center;">Graded Establishments</h3>
                      <button type="button" class="btn btn-secondary float-right">TRA</button>
                    </div>
                    <br><br>
                    <div class="container">
                      <div class="row">     
                        <?php

                        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                            $CustomerName = $row['CustomerName'];
                            $Rating = $row['Rating'];
                            $Website = $row['Website'];
                            $Location = $row['Location'];
                            $Email = $row['Email'];
                            $Mobile1 = $row['Mobile1'];
                            
                            ?>

                        <div class="col-sm">
                          
                        <img src="assets/img/logo1.png">
                                <p><strong><?php echo $CustomerName; ?></strong>-<?php echo $Location; ?><br><br>
                                <?php
                                if($Rating>50){
                                   // echo "5 star"; 
                                   ?>
                                   <strong>Rating:</strong>
                                   <br>
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <?php
                                }else{
                                    // echo "4 star";
                                    ?>
                                    <strong>Rating:</strong>
                                   <br>
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <img src="assets/img/star.png" height="15" width="20">
                                   <?php
                                }
                                ?>
                                <br>
                                
                                <strong>Contacts & More Info...</strong><br>
                                Email: <?php echo $Email; ?><br>
                                Tel: <strong><?php echo $Mobile1; ?><br></strong>
                                <br>
                                <a href='<?php echo $Website; ?>'>More About the Hotel</a><br>
                                <?php 
                                if($Website == null){
                                   ?>
                                   <button onclick="document.location='#'">Website has not been added yet.</button>
                                   <?php
                                }else 
                                ?>
                                <button onclick="document.location='<?php echo $Website; ?>'"><?php echo $Website; ?></button>
                            </div>
                             <?php
                        }

                        ?>
                        </div>
                    </div>
                        

   
    
                            <?php
                        }
                        // }
                    ?>
             </div>
            </div>
        </div>







        <section class="page-section portfolio" id="portfolio">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <div class="text-center">
                    <h2 class="page-section-heading text-secondary mb-0 d-inline-block">GET GRADED</h2>
                </div>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Portfolio Grid Items-->
                <div class="row justify-content-center">
                    <!-- Portfolio Items-->
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal0">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div><img class="img-fluid" src="assets/img/portfolio/cabin.png" alt="Log Cabin"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal1">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div><img class="img-fluid" src="assets/img/portfolio/cake.png" alt="Tasty Cake"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal2">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div><img class="img-fluid" src="assets/img/portfolio/circus.png" alt="Circus Tent"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal3">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div><img class="img-fluid" src="assets/img/portfolio/game.png" alt="Controller"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal4">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div><img class="img-fluid" src="assets/img/portfolio/safe.png" alt="Locked Safe"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal5">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div><img class="img-fluid" src="assets/img/portfolio/submarine.png" alt="Submarine"/>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Portfolio Modal-->
        <div class="portfolio-modal modal fade" id="portfolioModal0" tabindex="-1" role="dialog" aria-labelledby="#portfolioModal0Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary mb-0">Grading & Classification Application</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image--><img class="img-fluid rounded mb-5" src="assets/img/portfolio/cabin.png" alt="Log Cabin"/>
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Do you want your facility to be graded and classified using the set guidelines and standards. Apply Here... <a href="localhost:8000" target="_blank">Apply Here</a></p>
                                    <button class="btn btn-primary" href="#" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close Window</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-labelledby="#portfolioModal1Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary mb-0">Licence Application</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image--><img class="img-fluid rounded mb-5" src="assets/img/portfolio/cake.png" alt="Tasty Cake"/>
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Apply for your facility licence from here!</p>
                                    <button class="btn btn-primary" href="#" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close Window</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-labelledby="#portfolioModal2Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary mb-0">Trade Facilitation</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image--><img class="img-fluid rounded mb-5" src="assets/img/portfolio/circus.png" alt="Circus Tent"/>
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Apply for TradeFacilitation from here!</p>
                                    <button class="btn btn-primary" href="#" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close Window</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-labelledby="#portfolioModal3Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary mb-0">Hotel Classification</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image--><img class="img-fluid rounded mb-5" src="assets/img/portfolio/game.png" alt="Controller"/>
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Apply for the classification of your hotel here!</p>
                                    <button class="btn btn-primary" href="#" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close Window</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-labelledby="#portfolioModal4Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary mb-0">Application</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image--><img class="img-fluid rounded mb-5" src="assets/img/portfolio/safe.png" alt="Locked Safe"/>
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Make this applocation from this point.</p>
                                    <button class="btn btn-primary" href="#" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close Window</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-labelledby="#portfolioModal5Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fas fa-times"></i></span></button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary mb-0">New Application</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image--><img class="img-fluid rounded mb-5" src="assets/img/portfolio/submarine.png" alt="Submarine"/>
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Make Application from this point.</p>
                                    <button class="btn btn-primary" href="#" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close Window</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="page-section bg-primary text-white mb-0" id="about">
            <div class="container">
                <!-- About Section Heading-->
                <div class="text-center">
                    <h2 class="page-section-heading d-inline-block text-white">ABOUT</h2>
                </div>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- About Section Content-->
                <div class="row">
                    <div class="col-lg-4 ml-auto">
                        <p class="pre-wrap lead">Tourism Regulatory Authority (TRA) is a corporate body established under section 4 of the Tourism Act No.28 of 2011 and is mandated to regulate the tourism sector in Kenya. This entails developing regulations, standards and guidelines that are necessary to ensure an all-round quality service delivery in the tourism sector.</p>
                    </div>
                    <div class="col-lg-4 mr-auto">
                        <p class="pre-wrap lead">Tourism licensing is premised on ensuring customer satisfaction and competitiveness of the country as a tourist destination. Thus, all activities and services as outlined in the 9th Schedule of Tourism Act, 2011 need to manage customer expectations by maintaining minimum standards. </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="page-section" id="contact">
            <div class="container">
                <!-- Contact Section Heading-->
                <div class="text-center">
                    <h2 class="page-section-heading text-secondary d-inline-block mb-0">CONTACT</h2>
                </div>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Contact Section Content-->
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-contact mb-3"><i class="fas fa-mobile-alt"></i></div>
                            <div class="text-muted">Phone</div>
                            <div class="lead font-weight-bold">+254 0701-444777</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-contact mb-3"><i class="far fa-envelope"></i></div>
                            <div class="text-muted">Email</div><a class="lead font-weight-bold" href="mailto:name@example.com">info@tourismauthority.go.ke</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Footer Location-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="mb-4">LOCATION</h4>
                        <p class="pre-wrap lead mb-0">Tourism Regulatory Authority,
                        Utalii House,5th Floor,
                        Utalii Lane, off Uhuru Highway.
                        P.O. Box 25357-00100, Nairobi, KENYA</p>
                    </div>
                    <!-- Footer Social Icons-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="mb-4">FIND US ON THE WEB</h4><a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-fw fa-facebook-f"></i></a><a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-fw fa-twitter"></i></a><a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-fw fa-linkedin-in"></i></a><a class="btn btn-outline-light btn-social mx-1" href="#"><i class="fab fa-fw fa-dribbble"></i></a>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-4">
                        <h4 class="mb-4">TOURISM</h4>
                        <p class="pre-wrap lead mb-0">The tourism industry operates within a developed legal and regulatory framework which the players in this industry need to adhere to in the course of offering their services. The Authority strives to deepen and broaden tourism by developing and implementing a regulatory framework that ensures fairness, orderliness and high quality</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <section class="copyright py-4 text-center text-white">
            <div class="container"><small class="pre-wrap">Copyright © <?php echo date('Y'); ?> Tourism Regulatory Authority. All rights reserved.</small></div>
        </section>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed"><a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a></div>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="assets/mail/jqBootstrapValidation.js"></script>
        <script src="assets/mail/contact_me.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>