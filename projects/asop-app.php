<div class="project-container mt-3">
    <div class="project-header">
        <span class="back-button fs-2">
            &#8592;
        </span>
        <h2 class="fs-2 mb-0">ASOP App</h2>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Overview</h3>
        <p>ASOP App is a web-based fleet management tool that faithfully recreates Star Citizen's in-game ASOP Terminal interface while adding helpful features for organizing ships and vehicles outside of the game. The app aims to fill a crucial gap in the Star Citizen community by providing a user-friendly interface for managing fleets and ship data that players have come to know and love from the game itself.</p>   
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Case Study</h3>
        <p>The ASOP Terminal is a crucial interface in Star Citizen which allows the players to manage their ships and vehicles within the game.  While Cloud Imperium Games (CIG), the developers behind Star Citizen, offers the ASOP in game, players have to resort to 3rd party applications, websites and even Excel spreadsheets to manage their fleet.</p>
        <p>The ASOP App that I created is my official step into this space of insanely great talent to help players manage their fleets outside of the game.  The main inspiration was to faithfully recreate the main UI of the ASOP terminal in game and offer it as a web based application so all players can access it on all devices while also including some custom features.</p>
        <p>Pictured below is what CIG currently offers as a way to view your fleet, if you can even say that.  CIG has made strides recently to update their website UI and UX but unfortunately it seems that the player pages have not been graced with said updates.</p>

        <img src="/projects/img/asop/hangar-picture.png" class="img-fluid" style="border-radius: 0.5rem;" alt="ASOP App Hangar Picture">
        <small class="text-muted">The <code>My Hangar</code> tab on the official website.</small>
        
        <p class="mt-3">As you can see the UI and pretty simple and not very user friendly for a modern audience as it is stuck in the mid 2010's of web design.  Users cannot search, filter or sort their ships and vehicles in any way.  The only way to see what you have is to scroll through the list and hope you remember what you have.</p>
        
        <img src="/projects/img/asop/in-game-picture.png" class="img-fluid" style="border-radius: 0.5rem;" alt="ASOP App Picture">
        <small class="text-muted">The ASOP Terminal in game.</small>
        
        <p class="mt-3">Above is a picture of the ASOP Terminal in the game as of 4.0 (EOY 2024 / BOY 2025).  The UI is basic in a good way as it is easy to navigate and understand, whether you are a brand new player, a veteran player or a player who has taken a break from the game.</p>
        <p>The terminal shows the players all ships and vehicles, along with some important information divided up into columns such as Vehicle (Manufacturer and Name), current status of the ship (Delivered, Stored, Deliverable), Location, Status, Focus, Cargo and Crew.</p>
        <p>My goal was to recreate this UI as faithfully as possible while also adding some custom features that I thought would be useful to players.  However, some details like current status, location, and the deliver/retrieve/claim actions would be left behind as there would be no point to bring it over.</p>

        <img src="/projects/img/asop/web-app-picture.png" class="img-fluid" style="border-radius: 0.5rem;" alt="ASOP App Web App Picture">
        <small class="text-muted">The ASOP App web app.</small>

        <p class="mt-3">Pictured above is my faithful recreation of the app along with the new columns that would make more sense for a web based fleet manager such as an expanded category and sub category, ship component size, cargo, crew, price, and a edit / delete icon.</p>
        <p>The in game version has an X at the top left that allows you to close the terminal and return to your business, however, it now serves as a menu icon that allows players to manage settings, logout and see their profile.  To add a ship, I simply created a new icon to the left which opens up another overlay to add a ship.</p>

        <img src="/projects/img/asop/add-ship-picture.png" class="img-fluid" style="border-radius: 0.5rem;" alt="ASOP App Add Ship Picture">
        <small class="text-muted">The ASOP App add ship overlay.</small>

        <p class="mt-3">Using the popular Select2 library, I was able to add a search box to filter down manufacturers and ships as Star Citizen has over 100 ships and vehicles to choose from.  The only restriction currently in the list are ships that have not been released which I intend to talk about later on.</p>

        <h3 class="fs-4 mt-3">Conclusion</h3>
        <p>Overall, I am very happy with how the app turned out.  I was able to recreate the UI of the ASOP Terminal in game and add some custom features that I thought would be useful to players.  I have been using the app myself to manage my fleet and it has been a great experience to do so, coming from an Excel Spreadsheet.</p>
        <p>If you have any feedback or suggestions, please feel free to reach out to me on Discord or X, @NoahFlowa</p>
    </div>

    <div class="project-section">
        <h3 class="fs-3 mb-3">Future Features</h3>

        <p>While the app is currently in a usable state, there are some features that I would like to add in the future as well as some redesigns.</p>
        <p>Currently the UI is very basic outside of the ASOP Terminal screen seen in game.  My goal for this project was to get it built and get it shipped, as most Software Developers can relate to, doing so and not letting it sit is a very challening task.</p>
        <p>The UI I'd like to redesign are:</p>
        <ul>
            <li>Sign in and Sign up</li>
            <li>The overlays for both the main menu and the add a ship menu</li>
            <li>The ship/vehicle search fields</li>
        </ul>
        <p>Features I'd like to add are:</p>
        <ul>
            <li>Versioning Info and Last Updated Info</li>
            <li>Ship/Vehicle JSON Import from popular ship managers such as <a href="https://hangar.link/pledges/all" target="_blank">Hangar Link</a>, <a href="https://ccugame.app/" target="_blank">CCU Game App</a>, and <a href="https://hangar.link/fleet/canvas" target="_blank">StarJump Fleet Viewer.</a></li>
            <li>Ship/Vehicle JSON Export</li>
            <li>Account Delete</li>
        </ul>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Technologies Used</h3>
        <ul>
            <li>Adobe ColdFusion 2023 / Lucee 6</li>
            <li>Bootstrap 5.3</li>
            <li>Font Awesome 6.7</li>
            <li>Google Fonts</li>
            <li>MySQL</li>
            <li>Coolify</li>
            <li>Let's Encrypt</li>
            <li>Docker</li>
        </ul>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Links</h3>
        <a href="https://asop.app" target="_tab">https://asop.app</a>
    </div>
</div>