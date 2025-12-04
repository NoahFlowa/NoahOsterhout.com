<div class="project-container mt-3">
    <div class="project-header">
        <span class="back-button fs-2">
            &#8592;
        </span>
        <h2 class="fs-2 mb-0">Star Citizen Cleaner (SCCleaner.com)</h2>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Overview</h3>
        <p>Star Citizen Cleaner (SCCleaner.com) is a simple easy to use tool to clean up user config files and shader cache files for the video game Star Citizen.</p>   
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Case Study</h3>
        <p>Star Citizen is a crowdfunded MMO first person universe simulator game developed by Cloud Imperium Games and published by Roberts Space Industries. Set in a sci-fi universe 930 years in the future, it features a sandbox with planetary systems to explore and careers like trading, combat, and mining.</p>
        <p>The SCCleaner app that I created aims to help users easily remove older shader cache files that build up over the different version of the game as well as deleting user config files except keybinds and characters.  These actions are recommended by CIG but arent built into their launcher yet.</p>
        <p>SCCleaner app features an easy to use UI/UX that asks the user for the main game install path then searches all installed game versions called channels as well as the local app data folder.  From there the user can view full paths, sizes and take actions.  SCCleaner also features an auto update function.</p>

        <img src="/projects/img/sccleaner/main-app.png" class="img-fluid" style="border-radius: 0.5rem;" alt="SCCleaner App Picture">
        <small class="text-muted">The <code>SC Cleaner</code> app.</small>
        
        <p class="mt-3">The UI is insanely simple but that simplicity makes it very user friendly and easy to navigate.  In that simplicity lies its strength.</p>
        
    </div>

    <div class="project-section">
        <h3 class="fs-3 mb-3">Future Features</h3>

        <p>The app is currently in a very usable state with auto updating and auto checking and strong fail protection in case of path errors.</p>
        <p>Features I'd like to add are:</p>
        <ul>
            <li>Copyright Data as well as the Star Citizen disclaimer that its not directly affiliated with Cloud Imperium Games</li>
            <li>More detailed logging of actions taken</li>
            <li>Ability to back up config files before deletion</li>
        </ul>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Technologies Used</h3>
        <ul>
            <li>Node JS</li>
            <li>Bootstrap 5.3</li>
            <li>Font Awesome 6.7</li>
            <li>Electron</li>
            <li>Electron Forge</li>
            <li>Coolify</li>
            <li>GitHub Releases</li>
            <li>Docker</li>
        </ul>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Links</h3>
        <a href="https://sccleaner.com" target="_tab">https://sccleaner.com</a>
    </div>
</div>