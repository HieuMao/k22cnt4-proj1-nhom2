<http>
    <head>
        <title>Connect php</title>
    </head>
    <body>

     <?php
    $dbhost = "localhost";
    $dbuser = "root"; 
    $dbpass = ""; // 
    $dbname = "Project1";
    // taoj keets noi
    $mysqli  = new mysqli($dbhost,$dbuser,$dbpass,$dbname); 
 
    // Check connection
    if ($mysqli->connect_error) {
        printf("Connection failed: " . $mysqli->connect_error);
        exit();
    }

    printf ("Connected successfully") ;
    ?>
    </body>
</http>