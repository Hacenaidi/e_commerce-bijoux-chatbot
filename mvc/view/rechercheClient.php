<?php

session_start();

include_once('../controller/ClientController.php');
$cc = new ClientController();
//if the request is post and is admin insert find a user from a given name and display his full information except for the password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
    $nom = $_POST['nom'];
    $res = $cc->rechercheClientParNom($nom);
    if ($res->rowCount() == 0) {
        echo "<h1>No client found with the given name</h1>";
    }else{

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>Id</th>";
    echo "<th>Nom</th>";
    echo "<th>Prenom</th>";
    echo "<th>Email</th>";
    echo "</tr>";

    while ($l = $res->fetch()) {


        echo "<tr>";
        echo "<td>" . $l[0] . "</td>";
        echo "<td>" . $l[1] . "</td>";
        echo "<td>" . $l[2] . "</td>";
        echo "<td>" . $l[4] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

    //if the result is empty display a message
    
    echo "<a href='admin_dashboard.php'>Back</a>";
}

?>