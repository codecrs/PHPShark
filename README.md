# PHPShark
PHP Shark - An Intensive PHP MVC framework for Advanced Developers. It has basic functionality of putting up complex portals in minutes.
However it is much as RAW as it is an Deliverable Framework. Not to be compare with Competents - CAKEPHP & CODEIGNITER or Even LARAVEL,
as it was built to meet its own challenges. 

# Purpose of PHPShark.
PHP SHARK is built basically for its ORM Query Library for Modeling. 
A powerful query library build with PDO architecture. 

**PS** : the software is well tested with MySql Database. Will be ready for other databases soon. 

## Lets us create a country table with PHPShark Query. 
```
if(!\orm\Query::is_table('country')){
    \orm\Table::create()
    ->table('country')
    ->field('countries_id')->type('i(11)')->constraint('UNSIGNED NOT NULL')->auto_increment()
    ->field('country_name')->type('varchar(50)')->constraint('NOT NULL')
    ->field('country_code')->type('varchar(50)')->constraint('NOT NULL')
    ->primary('type_id')
    ->execute();
}
```

## Oops! I Just missed two field I would require to collect data of the data 'creation' and 'modification'. 
Adding a field **modified** to the *country* table. 
```
  if (!orm\Query::is_field('country', 'modified')) {
      $this->alter([
        'table' => 'country',
        'field' => 'modified',
        'operation' => 'add',
        'type' => 'datetime',
        'null' => false,
      ]);
   }
```
Adding a field **created** to the *country* table. 
```
  if (!orm\Query::is_field('country', 'created')) {
    $this->alter([
      'table' => 'country',
      'field' => 'created',
      'operation' => 'add',
      'type' => 'datetime',
      'null' => false,
    ]);
  }
```
## CRUD Queries in PHPShark
OK! Now that our table is ready - let us push a data into it with an INSERT Query . 

### INSERT Query 
```
$ins = $this->db->insert("countries")
                ->values(["country_name", "country_code"])
                ->x();

$this->db->parameter("country_name",'India');
$this->db->parameter("country_code",'IN');
$ins   = $this->db->fetch();
```
**$ins** now holds the last inserted id into the table

### SELECT Query 
```
  $this->db->select(["countries_id", "country_name", "country_code"])
           ->from("countries")
           ->where("countries_id = id")
           ->x();
           
  $this->db->parameter("id",'1');
  $sel   = $this->db->fetch();
  $count = $this->db->dbCount();
  
  //Expose the result as JSON Object 
  echo $this->json->encode($sel);
```
oh! Well I believe i should maintain my country with its native Hindi name. 
### UPDATE Query 
```
$upd = $this->db->update("countries")
            ->set(["country_name"])
            ->where("countries_id = id")
            ->x();

  $this->db->parameter("country_name",'Bharat']);
  $this->db->parameter("id",'1');
  $upd = $this->db->fetch();
```
**$udp** will hold the value of true and false now.

AAh!! I just found one user fiddling with my databases and I am very much annoyed. 
Let me just delete this guy from here. 

### DELETE Query 
```
$this->db->delete("users")->where("user_id = id")->x();
$this->db->parameter("id",'1');
$this->db->fetch();
```

### The X() stands for execute. Please note, the query will not be performed unless this X() function executes it. 

### PHPShark Document Structure. 
PHPShark Document Structre is divided by folders & united by application structure - hahaha! just kidding. 
Inside the folder here you will see three basic folders and some files laying inside the root folder. 

* app 
* project 
* public
* src 

## App Folder 
In order, not to break the application - I would recommend not to touch this folder and files inside of it. 
reason being this is the application folder and there will be a lot of changes and upgrades to this folder as the application 
moves on in its course of deployement. If you feel like debugging and dig this application, please be my guest, but that doesnt
**guarentee** an expected upgrade. you can just open a a pull request to make ammendments or even contribute to a big fix. 

## Project Folder 
This folder is your version of APP folder. This is where you can create you customer copies and libraries for your PHP project. 
All the changes are recommended to reside inside the project folder. This is the folder that contains you files *Router* and 
*configuration* files as well. The two files are mandatory files required by the application. 

## Public Folder
This is public repository of you project. When the folder is deployed, this is where you public accessable files like - CSS/JS
and other public assets are placed. 

## Src Folder 
This is were all the magic happens - all links, models, controllers and you view files resides inside this folder. 
This folder is the driver of you portal. 

### Installing PHPShark. 

Well, when you first deploy this software into your XAMP or any other Server, get ready to get Overflooded with errors. 
aarrrrrh! 

![m'lady](https://media.giphy.com/media/TqiwHbFBaZ4ti/giphy.gif)

Well! Dont just start pulling your hair now, because this application has some pre-requisites and configurations.

These are the steps you need to take care before you make you hands dirty with PHPShark. 

1. Make sure you database is setup - This application points to a database with proper Username & Password. 
2. Setup a configuration file in the *project folder*. 
3. You need to set up a **router.php** placed inside the *project folder*. 
4. Be sure to place and error page for your typical - 404 Page not found. 

# Config.xml
The XML file holds all the basic configurations to get you started. 
It has pritty much a lot of things inside, but for now we will only take up tags that will help us get started. 

> **PROJECT** tag: This the description of you project. 

> **ENVIRONMENT** tag: It takes up the values < development | quality| production >

> **DATASOURCE** tag:  Specifies the name of your Database. (Example - MySql). 

> **PORT** tag:  Specifies the port of your Database (Default Example - 3306). 

> **DATABASE** tag:  Specifies the database of your Database. 

> **LOGIN** tag:  Specifies the login user of your Database. 

> **PASSWORD** tag:  Specifies the password of your Database.

> **SECURITY_SALT** tag:  Specifies the Security Hasing Key for your password. 
This will be used for your password hasing when you create the login system of your application. 

AAA! well I could has done all of these for you, but like I said when I started - ITS RAW PHP and 
you may want to do stuffs here if you want - Say via application design or 3rd party connections. 

# The Scenerio
One of my clients had a requirement of setting up pages in the following formats. 
they wanted 5 Sections 

* Public - Pages where guests can access wihout a login. 
* Admin - The area for Administrator's login. 
  * **Super** - The super admin has the access to all the database
  * **Agent** - Where partial-database is accessed via roles. 
* Member - Where is client/vender can register themeselves. 
  * **Users** - All Generals users or say the clients. 
  * **Dealers** - Were the people with raw material can register via administrator's approval. 
  
# By Design
The time I stared building this application, I had first designed my links. 

# Router.php
### Router Setup: Rest API Links. 
PS - This application supports the **REST** and **CLEAN** URLs 

```
\\Members access: <base>/member/
Router::route("member",["path" => "customers".DS."members"]);

\\Dealers access: <base>/dealer/
Router::route("dealer",["path" => "customers".DS."dealers"]);

\\Admin access: <base>/admin/
Router::route("admin",["path" => "admin".DS."admin"]);

\\Agent access: <base>/agent/
 Router::route("agent",["path" => "admin".DS."agents"]);

\\404 Not Found: <base>/404/
Router::route("404",["path" => "public".DS."error"]);

\\Access Denied: <base>/denied/
Router::route("denied",["path" => "public".DS."denied"]);

\\Index: <base>/
Router::route("/",["path" => "public"]);
```

For the above links the src (source) structure folder is places as below. 

* admin
  * admin
  * agent
* customers
  * dealers
  * members
* public 
  * error 
  * denied
  * index
  
### Explanation 
**Router** file is a pointer file. It points the direction of the links to the files avaiable inside the source folder. 
if the pointed file does not exist, or is not pointed, the router is automatically directed to the error. 

### Router Class Syntax. 
```
Router::route("<base extenstion from the link>",["path" => < Path to the root folder of the linking file inside the src folder >]);
```

The folder and root folder hierarchy specifically contribute to the links as 
**Root -> Namespace ->Link Controller**. 

# PHPShark: MVC Structure
PHPShark follows the same MVC structure as all other framerworks do. However, here it is placed inside its namespace folder 
and then all its - Model-View-Controller resides in the same place. 

#Resuming the developement of my country. 
in continuation of the query example, lets create an admin link to create a new country, so we will now create a 
page which will be called from the following actions 

1. < base >/admin/country
2. < base >/admin/country/add
3. < base >/admin/country/edit/1

# The Controller 
required: 
1. namespace to be accessed from admin area. 
2. class that extends \AppController. 
3. class constructor with its parent construct - A Must.
4. A default index method. 

```
<?php
namespace admin{
  class Country extends \AppController{
       public function __construct(){
          parent::__construct();
       }
       
       public function index(){
          $this->view->render("index");
       }
  }
}
```

# The Model 
required: 
1. namespace to be accessed from admin area. 
2. class renamed with "_Model" that extends \AppModel. 
3. class constructor with its parent construct - A Must.

```
<?php
namespace admin{
  class Country_Model extends \AppModel{
       public function __construct(){
          parent::__construct();
       }
  }
}
```

# The View
Being the front-end file, the view closely works with HTML5/CSS and Javascripts. 

required: 
1. First convention of a view folder is that it is places inside the folder of its controller. 
2. Second convention is that it has to be named with '_view'. example index_view.php

the folder structure will be placed as 
* country
  * view
    * add_view.php
    * edit_view.php
    * index_view.php
  * country.php 
  * country_model.php
  
Well, This documentation is just get you a kick start. Full-Documentation of this application will be ready 
and will be published soon. 

However - I will just lay down the source code of country creation below. 
This will give you a fair Idea of how the MVC Source code of a page looks like when designed with PHPShark framework. 

# country.php 

```
<?php 
namespace admin{
    class Country extends \AppController{
        public function __construct(){
            parent::__construct();
        }
        
        public function index(){
            $this->view->title("Admin - Country Configuration");
            $this->view->render("index");
        }

        public function add(){
            $this->view->title("Admin - Country Configuration | Add");
            $this->view->render("add");
        }

        public function edit(int $id){
            if($id !== null){
                $this->view->title("Admin - Country Configuration | Edit");
                $this->view->set("id", $id);
                $this->view->render("edit");
            }else{
                request_page("error");
            }
        }

        public function xhrGetList(){
           if(action_check("post")){
                $list = $this->model->listCountry();
            }
        }

        public function xhrGetIndexByID($id){
            if(action_check("get")){
                $this->model->getCountryById($id);
            }
        }

        public function xhrAdd(){
          if(action_check("post")){
               $this->model->addCountry($_POST);
           }
        }

        public function xhrUpdate($id){
           if(action_check("post")){
                $this->model->updateCountry($_POST,$id);
           }
        }

        public function xhrDelete($id){
            if(action_check("post")){
                $this->model->deleteCountry($id);
            }
        }
    }
}
```
    
# country_model.php 

```
<?php 

namespace admin{
    class Country_Model extends \AppModel{
        public function __construct(){
            parent::__construct();
        }

        public function listCountry(){
            $this->db->select(["countries_id", "country_name", "country_code"])
                     ->from("countries")
                     ->x();

            $sel   = $this->db->fetch();
            $count = $this->db->dbCount();

            echo $this->json->encode($sel);
        }

        public function getCountryById(int $id){
            $this->db->select(["countries_id", "country_name", "country_code"])
                     ->from("countries")
                     ->where("countries_id = id")
                     ->x();

            $this->db->parameter("id",$id);

            $sel   = $this->db->fetch();
            $count = $this->db->dbCount();
            echo $this->json->encode($sel);
        }

        public function addCountry(array $data){
            $ins = $this->db->insert("countries")
                     ->values(["country_name", "country_code"])
                     ->x();

            $this->db->parameter("country_name",$data["country_name"]);
            $this->db->parameter("country_code",$data["country_code"]);
            $ins   = $this->db->fetch();
        }

        public function updateCountry(array $data, int $id){
            $upd = $this->db->update("countries")
                            ->set(["country_name", "country_code"])
                            ->where("countries_id = id")
                            ->x();

            $this->db->parameter("country_name",$data["country_name"]);
            $this->db->parameter("country_code",$data["country_code"]);
            $this->db->parameter("id",$id);
            $this->db->fetch();
        }

        public function deleteCountry(int $id){
            $this->db->delete("countries")->where("countries_id = id")->x();
            $this->db->parameter("id",$id);
            $this->db->fetch();
        }
    }
}
```


# add_view.php
```
<?php $this->inc('themes/pages/page_start'); ?>
<?php $this->inc('themes/navigation/admin') ?>

<section id="page" class="section-padding mx-4">
    <div class="container">
        <div class="card padding-card">
            <div class="card-body">
                <h5 class="card-title mb-4">Country Configuration</h5>
                <form id="countryEditForm" method="post">
                    <div class="form-row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Country Name:</label>
                                <input type="text" name="country_name" id="country_name"  class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Country Code:</label>
                                <input type="text" name="country_code" id="country_code" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <a href="#" class="btn btn-primary" name="save" data-action="save">Save</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php $this->inc('themes/pages/page_end'); ?>
<script>
    $(document).ready(function(){
        $("a").click(function(){
            switch($(this).attr("name")){
                case "save":
                    $.ajax({
                        "url": base_url + 'webadmin/country/xhrAdd/',
                        "type": "POST",
                        "data": $("#countryEditForm").serialize(),
                        "success":function(result){
                        },
                    });
                break;
            }
        });
    });
</script>
```

# edit_view.php

```
<?php $this->inc('themes/pages/page_start'); ?>
<?php $this->inc('themes/navigation/webadmin') ?>
<?php $id = $this->get("id"); ?>

<section id="page" class="section-padding mx-4">
    <div class="container">
        <div class="card padding-card">
            <div class="card-body">
                <h5 class="card-title mb-4">Country Configuration</h5>
                <form id="countryEditForm" method="post">
                    <div class="form-row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Country ID:</label>
                                <input type="text" name="countries_id" id="countries_id" value="<?php echo $id; ?>" class="form-control" disabled />
                            </div>
                            <div class="form-group">
                                <label>Country Name:</label>
                                <input type="text" name="country_name" id="country_name"  class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>Country Code:</label>
                                <input type="text" name="country_code" id="country_code"  class="form-control"/>
                            </div>
                            <div class="form-group">
                                <a href="#" class="btn btn-primary" name="back">Back</a>
                                <a href="#" class="btn btn-primary" name="save" data-action="save">Save</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php $this->inc('themes/pages/page_end'); ?>
<script>
    $(document).ready(function(){
        var id; 
        id = $("#countries_id").val();
        $.ajax({
            "url": base_url + 'webadmin/country/xhrGetIndexByID/' + id,
            "type": "GET",
            "success":function(result){
                $("#country_name").val(result[0]["country_name"]);
                $("#country_code").val(result[0]["country_code"]);
            },
        });

        $("a").on("click",function(e){
            e.preventDefault();
            switch($(this).attr("name")){
                case "save":
                    $.ajax({
                        "url": base_url + 'webadmin/country/xhrUpdate/' + id,
                        "type": "POST",
                        "data": $("#countryEditForm").serialize(),
                        "success":function(result){
                        },
                    });
                break;
            }
        });
    });
</script>
```

# index_view.php

```
<?php $this->inc('themes/pages/page_start'); ?>
<?php $this->inc('themes/navigation/webadmin') ?>
<?php $this->inc('themes/style.fix') ?>

<!-- Overlay Navigation -->
<div class="slider-overlay"></div>
<section id="page" class="section-padding mx-4">
    <div class="grid-container">
        <div class="grid-row">
            <div class="grid-item item-xs-12">
                <div >
                </div>
                <div class="tables-widget tables-widget-default tables-widget-blue tables-widget-striped">
                <div class="tables-widget-header"></div>
                    <table width="100%" id="master-list" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Country ID
                                    <i class="icomoon-menu-open"></i>
                                </th>
                                <th>Country Name
                                    <i class="icomoon-menu-open"></i>
                                </th>
                                <th>Country Code
                                    <i class="icomoon-menu-open"></i>
                                </th>
                                <th>Action
                                    <i class="icomoon-menu-open"></i>
                                </th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->inc('themes/pages/page_end'); ?>
<?php $this->js("static","pages/webadmin/country")?>
```

# I Know! I Know!! - DRY Coding technique is here. 

You must be wondering about these functions popin up all of a sudden. Yes! they are all will be documented soon. 
But since this project has already been put up, I will not leave this part un-explored and let you die with axiety. 

So here it is, 
Your project file has a template Section. Well, sometimes there are few sections which are repeatedly occuring on all the pages. 
They can be Header, footer, menus or anything. These templates can be created and stored inside this template folder and called 
via *inc* function. oh! dont bother about the name, its simply the folder creation and their names. 

 ```
 $this->inc(themes/pages/page_start);
 ```
 
 # JS & CSS includes 
 Well since all these public accessable files reside inside the public asset folder, they cannot be relative accessed from other file 
 folder. So let us take a look at a typical .htaccess file below. 
 
 
 # .htaccess 
 
 ```
Options +FollowSymLinks +ExecCGI
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-l
	RewriteCond %{REQUEST_URI}  !(\.png|\.jpg|\.gif|\.jpeg|\.bmp)$ [NC]
  
	RewriteRule   (.*) index.php?url=$1	[QSA,L]
	
	Options -Indexes
</IfModule>
 ```
 
 therefore, we can only access these files via url links. However we have some build-in functions to reduce your efforts 
 ```
 $this->css('< namespace >','< hierarchy within the namespace >');
 $this->js('< namespace >','< hierarchy within the namespace >');
 ```

PS - Do not include the .js and .css at the end, the function will itself do that. 

now what is the use of the namespace here. Well, this Application for build for complex portals with file management system. 
you may want to keep you hierarchy as the downloaded modules. Example - **JQuery**, **Boostrap**
and you might want to keep the CSS/JS files developed by you separately. 

I had designed my structure as the following. 

* assets
  * common
    * boostrap
    * jquery
  * core
    * js/css designed for tables
    * js/css designed for forms
  * static
    * js/css designed for specific pages 1
    * js/css designed for specific pages 2
    
    
 ```
  <?php $this->css("common","bootstrap/css/bootstrap.min"); ?>
  <?php $this->js("common","jquery/jquery.min") ?>
  <?php $this->js("common","bootstrap/js/bootstrap.min") ?>
 ```

Well, PHPShark has met its challenges, 
The application will still continue to develop by Developer [Ankit Kumar](https://www.linkedin.com/in/ankitkumar85/),
via LinkedIn Netwrok. Feel free to use the application and contribute with pull requests. 


# COMMING SOON
The complete Documentation of this application will be release soon with a Demonstration. 
Bye Bye, Take care. :)
