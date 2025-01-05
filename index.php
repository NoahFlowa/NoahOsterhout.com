<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Noah Osterhout</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
        <link rel="manifest" href="/favicons/site.webmanifest">
        
        <style>
            body {
                font-family: 'Montserrat', sans-serif;
            }

            a {
                color: black;
                text-decoration: none;
            }

            code {
                background-color: #333;
                color: white;
                border-radius: 3px;
                padding: 2px 4px;
            }
            
            .project-link:hover {
                color: #0d6efd;
                cursor: pointer;
            }
            
            .project-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: white;
                z-index: 1000;
                overflow-y: auto;
            }

            .project-container {
                max-width: 700px;
                margin: 0 auto;
                padding: 40px 20px;
            }
            
            .back-button {
                cursor: pointer;
                display: inline-block;
                margin-right: 15px;
            }
            
            .project-header {
                display: flex;
                align-items: center;
                margin-bottom: 40px;
            }

            /* Ensuring consistent content spacing */
            .project-section {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="mt-5">
                <p class="fs-1 mb-0">Noah Osterhout</p>
                <p class="fs-4">Professional Code Wrangler Extraordinaire</p>
            </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Experience</p>
                <p class="fs-5 mt-2">Systems Programmer @ SunFrog Solutions, Inc. (2022 - Present)</p>
            </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Education</p>
                <p class="fs-5 mt-2">Bachelors of Science - Computer Information Systems @ Ferris State University <a href="/docs/Noah_Osterhout_CIS_eDiploma.pdf" download>(Diploma PDF)</a></p>
            </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Projects</p>
                <div class="projects-list">
                    <?php
                    // Get list of project files
                    $projects = glob('projects/*.php');
                    foreach($projects as $project) {
                        $name = basename($project, '.php');
                        echo "<p class='fs-5 mt-2'><a class='project-link' data-project='$name'>$name</a></p>";
                    }
                    ?>
                </div>
            </div>
            
            <div id="project-overlay" class="project-overlay">
                <div id="project-content"></div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.project-link').click(function(e) {
                    e.preventDefault();
                    const project = $(this).data('project');
                    
                    // Load project content
                    $.ajax({
                        url: 'load-project.php',
                        method: 'GET',
                        data: { project: project },
                        success: function(response) {
                            $('#project-content').html(response);
                            $('#project-overlay').fadeIn(300);
                            
                            // Handle back button click
                            $('.back-button').click(function() {
                                $('#project-overlay').fadeOut(300);
                            });
                        },
                        error: function() {
                            $('#project-content').html('<p class="text-danger">Error loading project details.</p>');
                        }
                    });
                });
            });
        </script>
    </body>
</html>