# Travel Tipia
Travel Tipia is a travel advice site with a dicussion board for the community to share and learn about travel. It was made for the comp 3340 class project.

The site can be viewed at: https://pardali1.myweb.cs.uwindsor.ca/comp3340-project/public_html/index.php

The site status can be viewed at: https://pardali1.myweb.cs.uwindsor.ca/comp3340-project/public_html/status.php

## Setup
To install the website on another server or locally do the following:
1. Setup AMPPS stack
2. Clone the git repo into the public_html folder on myweb or the main folder for code on another system
3. Make a database in mysql
4. Run the create_tables.sql script to setup tables in the 
5. Go into the private subfolder and edit dbConnection.php to put your database details into it
6. Go into the public_html subfolder and edit the database details in status.php

### Setup an Admin Account
1. Login to the site
2. Edit the user record in your database software to make your user an admin by setting isAdmin to 1

You will now have admin access.