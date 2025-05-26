# Installation

Getting **Ndako** up and running on your local or remote server takes a few minutes.

The guide below will walk you through the process. The installation is a very simple process and takes less than five minutes to complete.


## 1. Download and Extract

Visit https://ndako.koverae.com/download to download the Ndako package. Once donwloaded, unzip the downloaded package to the desired location on your web server or computer.

- If you want to integrate Ndako into the root of your domain (e.g., https://example.com/) Move or upload all the contents of the unzipped Ndako directory (excluding the Ndako directory itself) into the root directory of your web server.

- If you want to have your Ndako installation in its own subdirectory on your website (e.g., `https://example.com/ndako/`), create a directory named ndako on your server, and upload the contents of the unzipped Ndako package to that directory via FTP. Note: If your FTP client has an option to convert file names to lowercase, make sure it’s disabled.


## 2. Database

Create a database for Ndako on your web server and a `MySQL` (or `MariaDB`) user with full privileges to access and modify it.

- If you only have one database and it is already in use, you can install Ndako in that database. Ndako uses a distinct prefix for each installation to avoid overwriting existing database tables.
### Create a new database
To create a new database, follow the instructions below for using phpMyAdmin. <br />
On the phpMyAdmin home page, click on the “Databases” tab in the left-hand panel.

![PhpMyAdmin](/screenshots/Ndako-phpMyAdmin.png)

Enter a name for your new database in the `Database name` field. Your database name should be short and meaningful, e.g., ndako or my_new_database. Select a `Collation` from the drop-down menu. Collation defines the character set your database will support. Generally, `utf8mb4_general_ci` or `utf8_general_ci` are good choices.
<br />
Click the “Create” button to create your database.

![PhpMyAdmin](/screenshots/Ndako-phpMyAdmin-create.png)


## 3. Set up Env

You can either create and edit the `.env` file yourself, or you can skip this step and let Ndako attempt to create and configure it automatically when you run the installation script (Step 4). (You will still need to provide Ndako with your database information.)
<br />
<br />
Return to where you extracted the Ndako zip in **Step 1**, rename the file `.env.example` to `.env`, and open it in a text editor.
<br />

- **APP_URL**: The base URL of the application used to generate URLs and links.
- **APP_KEY**: A random, secret key used for encrypting data and securing the application.
- **DB_CONNECTION**: Specifies the type of database connection (e.g., mysql, pgsql, sqlite).
- **DB_HOST**: The IP address or hostname of the database server.
- **DB_PORT**: The port number for connecting to the database server (e.g., 3306, 5432).
- **DB_DATABASE**: The name of the database to be used.
- **DB_USERNAME**: The username for connecting to the database.
- **DB_PASSWORD**: The password for the database user account.
- **DB_PREFIX**: A prefix to be added to database table names to prevent name collisions between multiple applications.

```bash
    APP_NAME="Ndako"
    APP_ENV=production
    APP_KEY=base64:N2ZqIo4Zh5ephVRhoTpHH32l1gYSwOnzD/c8Kqm+OIw=
    APP_INSTALLED=true
    APP_DEBUG=true
    APP_TIMEZONE=Africa/Nairobi
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=ndako-db
    DB_PORT=3306
    DB_DATABASE=ndako
    DB_USERNAME=arden_bouet
    DB_PASSWORD="@hks3Xr/GJ"
    DB_PREFIX="ndako_"
```

> **Note**: When you manually update the `.env` information, there must be a previous Ndako installation in the database. Otherwise, your installation will not work.

## 4. Run the Install wizard

If you placed the Ndako files in the root directory, you should visit: https://example.com.
<br>
If you placed the Ndako files in a subdirectory called blog, for example, you should visit: https://example.com/ndako/

### Setup configuration file
If you have manually filled the `.env` file, you will be redirected directly to the login page, bypassing the installation page.

#### Launching the Installer
Enter http://your-domain/ndako-directory in your favorite browser.
<br>
This will launch the installation wizard. The installation wizard takes you through the following steps.

- Set up your database & environment
- Add an admin
- Set up your company
- Login

![Ndako](/screenshots/ndako-install.png)

#### Setting up your database
Add the database connection details:
- Database Type: `mysql`, `pgsql`, `sqlite`
- Hostname
- Database Name
- Database Username
- Database Password
- App Environment
- App URL
- App Timezone

![Ndako](/screenshots/ndako-install-database.png)

#### Adding an Admin 
You can create a new Administration account.

![Ndako](/screenshots/ndako-install-admin.png)

#### Setting up your company 
Add your company details:
- Ndako App Key: The Ndako App Key holds details of your Ndao plan. To get your Ndako key, you need to download Ndako via our [website](https://ndako.koverae.com/?utm=docs). 
    - Go to [ndako.koverae.com](https://ndako.koverae.com/?utm=docs)
    - Click on **On-Premise** in the resource section on the website footer
    - Enter the required fields – Name, Email, Phone, and Password.
    - Check the terms and conditions box.
    - Click **Download** 
    <br />
    A email will be sent to your email address, containing your plan informations and **Ndako App Key**.

- Business Name
- Business Type: `Hotels`, `Motels`, `Hostels`, `BnBs`, `Guesthouses & Lodges`, `Serviced Apartments & Vacation Rentals`
- Language: `English`, `French`, `Swahili`, `Hindi`, `Japanese`, or `Arabic`.
- Business Currency
- City
- Country

![Ndako](/screenshots/ndako-install-company-2.png)

#### Login
After installing, you can log into the admin panel with your account details.

![Ndako](/screenshots/ndako-login.png)
