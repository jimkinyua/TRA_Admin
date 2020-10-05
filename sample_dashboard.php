

    <?php

    // What you will probably do is:
    /*

    if( isset( $_GET[‘id’] ) )
    {
    $user = intval( $_GET[‘id’] );
    $sql = ‘SELECT * FROM results WHERE fk_user=’.$user;
    }

    */

    // I don’t have any tables set up, so I
    // simulate getting data from the database.
    // This SQL will get the DB to produce a
    // nice sin wave:
    $t = array();
    for( $i=0; $i<(4*3.14); $i+=0.3)
    $t[] = ‘select sin(‘. $i .’)';

    $sql = implode( ‘ union ‘, $t );

    //
    // This opens the db connection as usual:
    //
    // $db = mysql_connect(“localhost”, “user”,”***”) or die(“Could not connect”);
    // mysql_select_db(“database”,$db) or die(“Could not select database”);
    //
    // Uncomment the above lines and fill in the db, user name and password, then
    // delete the following two lines:
    //
    include_once( ‘includes/db.php’ );
    $db = openDataBase( );
    //
    //

    $data = array();
    $res = mysql_query($sql,$db) or die(“Bad SQL 1″);
    while( $row = mysql_fetch_array($res) )
    {
    $data[] = floatval( $row[0] ) + 1.5;
    }


    // use the chart class to build the chart:
    include_once( ‘ofc-library/open-flash-chart.php’ );
    $g = new graph();
    $g->title( ‘Sin + 1.5′, ‘{font-size: 12px;}’ );

    $g->set_data( $data );
    $g->set_y_max( 3 );
    $g->y_label_steps( 3 );

    // display the data
    echo $g->render();
    ?>