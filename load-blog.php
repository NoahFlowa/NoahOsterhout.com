<?php
if (isset($_GET['blog'])) {
    $blog = $_GET['blog'];
    $file = "blogs/" . basename($blog) . ".php";
    
    if (file_exists($file)) {
        include($file);
    } else {
        echo "<p class='text-danger'>Blog post not found.</p>";
    }
}
?>