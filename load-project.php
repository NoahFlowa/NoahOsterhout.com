<?php
if (isset($_GET['project'])) {
    $project = $_GET['project'];
    $file = "projects/" . basename($project) . ".php";
    
    if (file_exists($file)) {
        include($file);
    } else {
        echo "<p class='text-danger'>Project not found.</p>";
    }
}
?>