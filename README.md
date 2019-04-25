# App Maintainer (Feb 2019)

A Web App for executing maintenance scripts to maintain an application for non technical users.  

# Overview 

If you have a massive web application for your customer and you need to maintain and provide daily support for this application like  :  

- correct corrupted records in the database
- correct records because of mistakes of users 
- make special modifications in the database
- relaunch backgroud job failed for unknown reason
- correct records 

and you can't repair bugs or update the application, so you need to write scripts to correct data and need to execute them with special parameters according to the bug your are trying to resolve... This can be pretty hard and boring..

So with this application, maintainers can write parametized scripts (SQL ad Bash) to repair known bug or automate special tasks, and customers, in the other side, can use the web app to launch thoses scripts with special parameters and see the execution result.

# Implementation

The frontend is an Angular 6 app (with ng-bootstrap) and the backend is a very basic PHP app (no framework). There is no database but a single config file.

The final app is located in the `dist` directory (created with the `npm run build` command).  
the npm scripts inside the client directory can be use to build the client angular app and copy the server to the `dist` directory for deployment.

# Installation

clone the repository

Client :  

1- In a terminal cd to the client directory : `cd client`

2- install dependencies : `npm install`  

3- build the application : `npm run build` (windows only)

Note that an angular proxy conf is forwarding api requests to the PHP backend.

Server :

1 - Add a local domain to your computer (for Windows add an entry to the C:\Windows\System32\drivers\etc\hosts file : `127.0.0.1 appmaintainer.local`)  so the app will be accessible at : `http://appmaintainer.local`

2 - In Apache, create a vhosts for the app like this and add the project in the directory, the DocumentRoot must point to the public directory.

```
<VirtualHost *:80>
    ServerAdmin webmaster@appmaintainer.com
    DocumentRoot "[path_to_the_app]/dist/public"
    ServerName appmaintainer.local
    ErrorLog "logs/appmaintainer-error.log"
    CustomLog "logs/appmaintainer-access.log" common
</VirtualHost>
```

for the production server replace `appmaintainer.local` by your hostname

3- For copying the server app only to the dist directory use : `npm run copy-server` (Windows only)

# Deployment

Build the client, copy the server to the `dist` directory and then copy the content of this directoy to your webserver

# Usage

Maintainer add scripts to the `server\scripts` directory (2 scripts are provided as examples)  
The script can be Bash script or SQL scripts to execute against a Postgres database, so the account running the PHP applications must have the correct permissions. For execution of Bash scripts an ssh configuration can be defined in the config.php file for executing the script throught ssh (If the web app is not installed on the production server and the ssh user is authenticated with a private key already configured) 
Users log to the Web app, search for a script to execute, fill the required parameters and can execute the script on the Preproduction environment or the Production environment.

# Licence

You can use this source for personal use

No Warranty: THE SUBJECT SOFTWARE IS PROVIDED "AS IS" WITHOUT ANY WARRANTY OF
ANY KIND, EITHER EXPRESSED, IMPLIED, OR STATUTORY, INCLUDING, BUT NOT LIMITED
TO, ANY WARRANTY THAT THE SUBJECT SOFTWARE WILL CONFORM TO SPECIFICATIONS,
ANY IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE,
OR FREEDOM FROM INFRINGEMENT, ANY WARRANTY THAT THE SUBJECT SOFTWARE WILL BE
ERROR FREE, OR ANY WARRANTY THAT DOCUMENTATION, IF PROVIDED, WILL CONFORM TO
THE SUBJECT SOFTWARE. THIS AGREEMENT DOES NOT, IN ANY MANNER, CONSTITUTE AN
ENDORSEMENT BY GOVERNMENT AGENCY OR ANY PRIOR RECIPIENT OF ANY RESULTS,
RESULTING DESIGNS, HARDWARE, SOFTWARE PRODUCTS OR ANY OTHER APPLICATIONS
RESULTING FROM USE OF THE SUBJECT SOFTWARE.  FURTHER, GOVERNMENT AGENCY
DISCLAIMS ALL WARRANTIES AND LIABILITIES REGARDING THIRD-PARTY SOFTWARE,
IF PRESENT IN THE ORIGINAL SOFTWARE, AND DISTRIBUTES IT "AS IS."
