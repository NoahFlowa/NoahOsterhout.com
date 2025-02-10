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
        
        <!--- Custom CSS --->
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
        <div class="container">
        <div class="mt-5">
            <p class="fs-1 mb-0">Noah Osterhout</p>
            <p class="fs-4">Professional Code Wrangler Extraordinaire</p>

            <div class="mt-2 mb-3">
                <a href="https://github.com/NoahFlowa" target="_blank" class="me-3">
                    <i class="fa-brands fa-square-github fa-lg"></i>
                </a>
                <a href="https://gitlab.com/NoahFlowa" target="_blank" class="me-3">
                    <i class="fa-brands fa-square-gitlab fa-lg"></i>
                </a>
                <a href="https://stackoverflow.com/users/9227487/noah-osterhout?tab=profile" target="_blank" class="me-3">
                    <i class="fa-brands fa-stack-overflow fa-lg"></i>
                </a>
                <a href="https://stackexchange.com/users/12700630/noah-osterhout" target="_blank" class="me-3">
                    <i class="fa-brands fa-stack-exchange fa-lg"></i>
                </a>
                <a href="https://www.linkedin.com/in/noah-osterhout-732171149" target="_blank" class="me-3">
                    <i class="fa-brands fa-linkedin fa-lg"></i>
                </a>
                <a href="https://bday.noahosterhout.com" target="_blank" class="me-3">
                    <i class="fa-solid fa-cake-candles"></i>
                </a>
            </div>
        </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Experience</p>
                <p class="fs-5 mt-2">Systems Programmer @ <a href="https://sunfrogsolutions.com" target="_blank">SunFrog Solutions</a> (2022 - Present)</p>
            </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Education</p>
                <p class="fs-5 mt-2">Bachelors of Science - Computer Information Systems @ Ferris State University <a href="/docs/Noah_Osterhout_CIS_eDiploma.pdf" download>(Diploma PDF)</a></p>
            </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Projects</p>
                <div class="projects-grid row g-4 mt-2">
                <?php
                $projectsJson = file_get_contents('projects/projects.json');
                $projects = json_decode($projectsJson, true)['projects'];

                foreach($projects as $project) {
                    echo '
                    <div class="col-md-6">
                        <div class="project-item">
                            <h3 class="project-title">' . htmlspecialchars($project['title']) . '</h3>
                            <p class="project-summary">' . htmlspecialchars($project['summary']) . '</p>
                            <div class="project-tags mb-3">';
                            foreach($project['technologies'] as $tech) {
                                echo '<span class="tech-tag">' . htmlspecialchars($tech) . '</span>';
                            }
                            echo '</div>
                            <a class="project-link" 
                            data-project="' . htmlspecialchars($project['id']) . '"
                            data-has-post="' . ($project['hasPost'] ? 'true' : 'false') . '"
                            ' . (!$project['hasPost'] ? 'data-external-url="' . htmlspecialchars($project['externalUrl']) . '"' : '') . '>
                                ' . ($project['hasPost'] ? 'View Details' : 'View on GitHub') . ' &#8594;
                            </a>
                        </div>
                    </div>';
                }
                ?>
                </div>
            </div>

            <div class="mt-5">
                <p class="fs-3 mb-0">Blog Posts</p>
                <div class="blogs-grid row g-4 mt-2">
                    <?php
                    $blogsJson = file_get_contents('blogs/blogs.json');
                    $blogs = json_decode($blogsJson, true)['blogs'];
                    
                    foreach($blogs as $blog) {
                        echo '
                            <div class="col-md-6">
                                <div class="blog-item">
                                    <h3 class="blog-title">' . htmlspecialchars($blog['title']) . '</h3>
                                    <div class="blog-metadata">
                                        <span>' . date('M j, Y', strtotime($blog['publishDate'])) . '</span>
                                        <span>' . htmlspecialchars($blog['readingTime']) . ' read</span>
                                    </div>
                                    <p class="blog-summary">' . htmlspecialchars($blog['summary']) . '</p>
                                    <button class="blog-link" type="button" data-blog="' . htmlspecialchars($blog['id']) . '">
                                        Read More &#8594;
                                    </button>
                                </div>
                            </div>';
                    }
                    ?>
                </div>
            </div>

            <div class="color-mode-div">
                <button type="button" class="btn btn-light" id="color-mode-button"><i class="fa-solid fa-moon"></i></button>
            </div>
            
            <div id="project-overlay" class="project-overlay">
                <div id="project-overlay-content"></div>
            </div>

            <div id="blog-overlay" class="blog-overlay">
                <div id="blog-overlay-content"></div>
            </div>

            <div class="footer mt-5 mb-5"></div>
        </div>

        <script src="/js/theme.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            $(document).ready(function() {
                // Project handling
                $(document).on('click touchstart', '.project-link', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const projectId = $(this).data('project');
                    const hasPost = $(this).data('has-post');
                    const externalUrl = $(this).data('external-url');

                    if (hasPost) {
                        loadContent('load-project.php', { project: projectId }, 'project-overlay');
                    } else if (externalUrl) {
                        window.open(externalUrl, '_blank');
                    }
                });

                // Blog handling
                $('.blog-link').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const blogId = $(this).data('blog');
                    loadContent('load-blog.php', { blog: blogId }, 'blog-overlay');
                });

                function loadContent(endpoint, data, overlayId) {
                    $.ajax({
                        url: endpoint,
                        method: 'GET',
                        data: data,
                        success: function(response) {
                            $(`#${overlayId}-content`).html(response);
                            $(`#${overlayId}`).fadeIn(300);
                            
                            // Prevent body scrolling when overlay is open
                            $('body').css('overflow', 'hidden');
                        },
                        error: function() {
                            $(`#${overlayId}-content`).html('<p class="text-danger">Error loading content.</p>');
                        }
                    });
                }

                // Handle overlay closing
                $('.back-button').on('click', function() {
                    $('.project-overlay, .blog-overlay').fadeOut(300);
                    // Restore body scrolling when overlay is closed
                    $('body').css('overflow', '');
                });
            });
        </script>
    </body>
</html>