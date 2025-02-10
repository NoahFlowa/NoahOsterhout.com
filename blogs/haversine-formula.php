<div class="project-container mt-3">
    <div class="project-header">
        <span class="back-button fs-2">
            &#8592;
        </span>
        <h2 class="fs-2 mb-0">Haversine Formula & User Location</h2>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3">Overview</h3>
        <p>Learn how to find saved locations around the user using the Haversine Formula.</p>
    </div>

    <div class="project-section">
        <h3 class="fs-3 mb-3">Table of Contents</h3>
        <ul>
            <li><a href="#haversine-formula">What Is The Haversine Formula</a></li>
            <li><a href="#why">Why It Is Useful</a></li>
            <li><a href="#how">How To Use The Haversine Formula Practically</a></li>
            <li><a href="#code">Code Example</a></li>
        </ul>
    </div>
    
    <div class="project-section">
        <h3 class="fs-3 mb-3"><a id="haversine-formula">What Is The Haversine Formula</a></h3>
        <p>The Haversine Formula has a kind of weird but fascinating history. It deals with finding the distance between two points on a sphere. Initially, the formula written in English was by James Andrew in 1805, however, historians and mathematicians give José de Mendoza y Ríos credit who used it first in 1801.</p>
        <p>The Haversine Formula is written as:</p>
        <pre class="bg-light p-3 rounded">
            a = sin²(Δφ/2) + cos(φ1) × cos(φ2) × sin²(Δλ/2)
            c = 2 × atan2(√a, √(1−a))
            d = r × c
        </pre>
        <p>Where:</p>
        <ul>
            <li>φ is latitude, λ is longitude, r is earth’s radius (mean radius = 6,371km)</li>
            <li>note that angles need to be in radians to pass to trig functions</li>
        </ul>
    </div>

    <div class="project-section">
        <h3 class="fs-3 mb-3"><a id="why">Why It Is Useful</a></h3>
        <p>The Haversine Formula is a perfect tool (to my limited knowledge of trigonometry) for finding the distance between two points on a sphere. Just like I described above. However, we can also modify it to find a list of Longitude and Latitude coordinates that are surrounding a user.</p>
        <p>Let’s say we have a user who logins into our app that shows them all the restaurants around them in a 10-mile radius. The Haversine Formula would allow us to grab the user's Longitude and Latitude coordinates and find all restaurants around said, user.</p>
    </div>

    <div class="project-section">
        <h3 class="fs-3 mb-3"><a id="how">How To Use The Haversine Formula Practically</a></h3>
        <p>As I showed above, the Haversine Formula can be written and used in Trigonometry, but because we want to find all locations from a single point within a 10-mile radius (or however far) we have to modify it slightly…</p>
        <p>Here is how we can use the Haversine Function in JavaScript via a Function that takes in the Longitude and Latitude arguments</p>

        <pre class="bg-light p-3 rounded">
            function haversineFormula(userLat, userLong, locationLat, locationLong, reqDist) {
            // Convert degrees to radians
            function toRadians(degrees) {
                return degrees * (Math.PI / 180);
            }

            // Haversine formula
            const R = 3958.8; // Earth's radius in miles

            const dLat = toRadians(locationLat - userLat);
            const dLong = toRadians(locationLong - userLong);

            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(toRadians(userLat)) * Math.cos(toRadians(locationLat)) *
                        Math.sin(dLong / 2) * Math.sin(dLong / 2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            const distance = R * c;

            return distance <= reqDist;
            }

            // Example Usage:
            console.log(haversineFormula(40.730610, -73.935242, 40.730700, -73.935242, 10)); // Very close points in New York
        </pre>

        <p>And here is how we can use it in a SQL Query or SQL Function, again passing in the Longitude and Latitude parameters</p>

        <pre class="bg-light p-3 rounded">
            CREATE FUNCTION dbo.fnHaversineFormula(
                @userLat FLOAT, 
                @userLong FLOAT, 
                @locationLat FLOAT, 
                @locationLong FLOAT,
                @reqDistance INT
            )
            RETURNS BIT
            AS
            BEGIN
                -- Declare result variable
                DECLARE @Result BIT
                DECLARE @Distance FLOAT

                -- Constants for Earth's radius in miles and conversion from degrees to radians
                DECLARE @R FLOAT = 3958.8
                DECLARE @DegreeToRadians FLOAT = PI() / 180.0

                -- Haversine formula calculations
                DECLARE @dLat FLOAT = (@locationLat - @userLat) * @DegreeToRadians
                DECLARE @dLong FLOAT = (@locationLong - @userLong) * @DegreeToRadians

                DECLARE @a FLOAT = SIN(@dLat / 2) * SIN(@dLat / 2) + 
                                COS(@userLat * @DegreeToRadians) * COS(@locationLat * @DegreeToRadians) * 
                                SIN(@dLong / 2) * SIN(@dLong / 2)

                DECLARE @c FLOAT = 2 * ATN2(SQRT(@a), SQRT(1 - @a))
                SET @Distance = @R * @c

                -- Check if distance is within requested distance
                IF @Distance <= @reqDistance
                    SET @Result = 1
                ELSE
                    SET @Result = 0

                -- Return result
                RETURN @Result
            END
        </pre>

        <p>With both of these examples, you can feed in or loop over the execution call with the user location, potential location and the distance you want. If the potential location is within the requested distance then it will return true, else false.</p>
    </div>

    <div class="project-section">
        <h3 class="fs-3 mb-3"><a id="how">Code Example</a></h3>
        <p>So how can we realistically use this in an application? Currently, I use this in an upcoming app where I have a table that stores locations, that include longitude and latitude as well as some City, State, Zip and Country values as well so we don't overload the server with thousands or hundreds of thousands of requests. I take the users location and feed it to an API that calls the <code>haversineFunction</code> in SQL and then returns all locations that are within the distance.</p>
        <p>Of course, thre are some other checks I have in place as well, but here is the implementation of it in a <code>NodeJS ExpressJS and Sequelize</code> API call.</p>

        <pre class="bg-light p-3 rounded">
            // Find nearest locations
            router.post("/find", async (req, res) => {
                const { reqDistance, Longitude, Latitude } = req.body;
                try {
                    const locations = await Locations.findAll({
                        attributes: {
                            include: [
                                [
                                    sequelize.literal(`(
                                3958.8 * acos (
                                cos ( radians(${Latitude}) )
                                * cos( radians( Latitude ) )
                                * cos( radians( Longitude ) - radians(${Longitude}) )
                                + sin ( radians(${Latitude}) )
                                * sin( radians( Latitude ) )
                                )
                            )`),
                                        "distance",
                                ],
                            ],
                        },
                        having: sequelize.literal(`distance <= reqDistance `),
                        order: sequelize.col("distance"),
                        limit: 10,
                    });

                    // If there are no listings nearby return an empty array
                    if (!listings) {
                        return res.status(200).json({ locations: [] });
                    }

                    return res.status(200).json({ locations });
                } catch (err) {
                    console.log(err);
                    return res.status(500).json({ message: "Internal server error" });
                }
            });

        </pre>

        <p>This is a very crude and basic example of an API call but I believe I convey the logic and intent well enough. We make a POST request to the API that contains <code>reqDistance, Longitude, Latitude</code> and then it executes the Haversine Formula checking each location in the <code>locations</code> table and verifying that it is within the distance we wanted. This isn't calling the SQL function I showed earlier as I wanted to show how we could do without it, if you would (and you should) use it as a function, all you need to do is just call it and return the response.</p>
    
        <p>That's about it. Just like with all entries in my B.L.O.G. series, I post this to not only share with everyone else who might want to use it but also as a backup and a way for myself to step through solutions I've come up with for different types of problems.</p>

        <p>If you enjoyed it, let me know on any of my socials or even just a comment below! Thanks!</p>
    
    </div>
</div>