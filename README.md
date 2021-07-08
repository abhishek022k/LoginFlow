# LoginFLow

LoginFlow is a basic implementation of a login flow consisting of login, signup and dashboard made using HTML, CSS & PHP.

## Description

This application has the following functionalities:
* There are 2 types of user - normal and admin.
* A signup flow where by default a user will signup as a normal user. You can change the type directly in DB for now to make someone admin.
* On the admin user’s dashboard user can see a list of all other users he can delete those, change the role, update the details.
* On the normal user’s dashboard, a read only list is present.
* A logout functionality.


## Installation

1. Clone the repository and place the LoginFlow folder inside your Document root to access it on localhost. 
2. Go to conn.php & change the $dbusername & $dbpassword for your MySQL server.
3. Navigate to LoginFlow folder on terminal & run the setup.php script which creates a DB and a table for the application and adds an admin user with username:'admin@admin.co' & password:'admin'.

```bash
php setup.php
```

## Usage

Access the application from your browser using localhost at '/LoginFlow/index.php' .



#### -Abhishek Sharma