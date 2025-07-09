# Multiple Data Deletion PHP MYSQL (PDO)

## Hello there,
I will show you one click deletion of your multiple data with "Multiple Data Erase".

#### Our Project Content
* Delete Multiple Data, delete photo of related data (Optional, available in source code)
* Success and error alerts with SweatAlert

## İndex.php
* Listing Data
- Multiple data deletion with "select all"

![alt text](https://github.com/FRTYZ/Multiple-Data-Deletion-PHP-MYSQL-PDO/blob/main/img/ss/home.png?raw=true)

#### Alert with SweatAlert
![alt text](https://github.com/FRTYZ/Multiple-Data-Deletion-PHP-MYSQL-PDO/blob/main/img/ss/home-alert.png?raw=true)

## Source Codes
* Related explanations are in the source code

#### fonc.php (Database Settings)
```
<?php
$host = '127.0.0.1';
$dbname = 'pdocrudphoto';
$username = 'root';
$password = '';
$charset = 'utf8';
//$collate = 'utf8_unicode_ci';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_PERSISTENT => false,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //   PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
];
try {
    $connect = new PDO($dsn, $username, $password, $options);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection error: ' . $e->getMessage();
    exit;
}
?>
```


#### 1) We make our Selected Delete Button to give a Warning with Modal
```
                                <a class="btn btn-danger font-18" href="#" data-toggle="modal"
                        data-target="#deleteall"><i class="fa fa-trash"> Delete Selected Data</i></a>
                        <!-- Logout Modal-->
                        <div class="modal fade" id="deleteall" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete All Action</h5>
                                    <button class="close" type="button" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h1 class="text-center">Important Warning !</h1>
                                <h3 class="text-center">The selected data will be deleted. Do you approve</h3></div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger font-18 "><i class="fa fa-trash"> Delete selected ones</i></button>
                                    <button class="btn btn-secondary" type="button"
                                    data-dismiss="modal">Cancel
                                </button>

                            </div>
                        </div>
                    </div>
                </div>    
```

#### 2) We placed our checkbox named "Select All" in the "th"(<thead> part) of our table
```
                        <div class="checkbox">
                            <input type="checkbox" id="selectall" value=""> <!--We give an id to our Checkbox and select all of them with javascript codes.  -->    
                        </div>
```

#### 3) We are transferring the Data to our Table in the Database.
```
<?php
                $query = $connect->prepare("SELECT * FROM blog"); // Retrieving data from database
                $query->execute();  // Running Our Query

                while ($result=$query->fetch())  // Data from database is returned with While Loop.

                {  // With the start we have data

                    ?>

                    <tbody>
                        <td>
                             <!--We use Checkbox to identify deleted Dataz-->
                            <div class="checkbox">
                                <input class="chck" type="checkbox" name="delete[]" 
                                value="<?php echo $result['id']; ?>"><!--We specify a name for the "name" part of the checkbox to be recognized for our multiple deleteme query.-->
                                <label for="<?php echo $result['id']; ?>"></label>  <!--We are sending the ID from the database to Checkbox-->
                            </div>
                        </td>
                        <td><?= $result['id']?></td>
                        <th><img src="img/<?= $result["photo"] ?>" width="150px"/></th>
                        <td><?= $result['title']?></td>
                        <td><?= $result['content']?></td>                       
                    </tbody>
                    <?php 
                }  // With End of While, we pull our data and our query ends.

                ?>
```


#### 4) When we click on the "deleteall" checkbox, we add our javascript code so that all checkboxes are checked
```
<script type="text/javascript">
    $(document).ready(function () {
        $('#selectall').on('click', function () {
            if ($('#selectall:checked').length == $('#selectall').length) {
                $('input.chck:checkbox').prop('checked', true);
            } else {
                $('input.chck:checkbox').prop('checked', false);

            }
        });
    });
</script>
```

#### 5) Add "deleteall.js" inside the <head> tag to select all checkboxes.
```
<script src="deleteall.js"></script>
```

#### 6) PHP codes required for deletion of selected data. The necessary explanations are in the source code.
```
<?php
    include('fonc.php'); // We include our database on our page

    if (isset($_POST['delete'])) { // Checking if the deletion Checkbox is Checked      
        $selecteddata = implode(', ', $_POST['delete']);    // We pass it to the variable "$selecteddata" that comes with Checkbox
        $query = $connect->prepare('select * FROM `blog` WHERE `id` IN (' . $selecteddata . ')'); // We get the IDs from the database
        $query->execute(); //executing query and getting data

        while ($result = $query->fetch()) { // Incoming data is returned with a while loop

        @unlink('img/' . $result["photo"]);// Old files (photos) are deleted. optional, you can not use this code
    }

    $query = $connect->prepare('DELETE FROM `blog` WHERE `id` IN (' . $selecteddata . ')'); // Our deletion query according to incoming data IDs
    $query->execute(); // Running Query

    if ($query) { // If our query worked, we are redirecting to our index.php page
        header("location:index.php");
    }

}
?>
```

## index.php (All source codes)
```
   <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Çoklu SİL</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="deleteall.js"></script>

    <?php
    include('fonc.php'); // We include our database on our page

    if (isset($_POST['delete'])) { // Checking if the deletion Checkbox is Checked      
        $selecteddata = implode(', ', $_POST['delete']);    // We pass it to the variable "$selecteddata" that comes with Checkbox
        $query = $connect->prepare('select * FROM `blog` WHERE `id` IN (' . $selecteddata . ')'); // We get the IDs from the database
        $query->execute(); //executing query and getting data

        while ($result = $query->fetch()) { // Incoming data is returned with a while loop

        @unlink('img/' . $result["photo"]);// Old files (photos) are deleted. optional, you can not use this code
    }

    $query = $connect->prepare('DELETE FROM `blog` WHERE `id` IN (' . $selecteddata . ')'); // Our deletion query according to incoming data IDs
    $query->execute(); // Running Query

    if ($query) { // If our query worked, we are redirecting to our index.php page
        header("location:index.php");
    }

}
?>
</head> 

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- We add a Form to our table, it must be a Form, otherwise the data will not be posted, it will not work. -->
                <form action="" method="post">
                    <!-- Our table-->
                    <table class="table table-hover">

                        <!-- We make our Selected Delete Button to give a Warning with Modal.-->

                        <a class="btn btn-danger font-18" href="#" data-toggle="modal"
                        data-target="#deleteall"><i class="fa fa-trash"> Delete Selected Data</i></a>
                        <!-- Logout Modal-->
                        <div class="modal fade" id="deleteall" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete All Action</h5>
                                    <button class="close" type="button" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h1 class="text-center">Important Warning !</h1>
                                <h3 class="text-center">The selected data will be deleted. Do you approve</h3></div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger font-18 "><i class="fa fa-trash"> Delete selected ones</i></button>
                                    <button class="btn btn-secondary" type="button"
                                    data-dismiss="modal">Cancel
                                </button>

                            </div>
                        </div>
                    </div>
                </div>    <!-- End of our delete selected modal-->


                <thead class="thead-dark">
                    <th>     
                        <!--Select All at the Top of Our Table We place our checkbox in the thead part of our table-->
                        <div class="checkbox">
                            <input type="checkbox" id="selectall" value=""> <!--We give an id to our Checkbox and select all of them with javascript codes.  -->    
                        </div>
                    </th>
                    <th>ID</th>
                    <th>photo</th>
                    <th>Title</th>
                    <th>Content</th>
                </thead>
                <?php
                $query = $connect->prepare("SELECT * FROM blog"); // Retrieving data from database
                $query->execute();  // Running Our Query

                while ($result=$query->fetch())  // Data from database is returned with While Loop.

                {  // With the start we have data

                    ?>

                    <tbody>
                        <td>
                             <!--We use Checkbox to identify deleted Dataz-->
                            <div class="checkbox">
                                <input class="chck" type="checkbox" name="delete[]" 
                                value="<?php echo $result['id']; ?>"><!--We specify a name for the "name" part of the checkbox to be recognized for our multiple deleteme query.-->
                                <label for="<?php echo $result['id']; ?>"></label>  <!--We are sending the ID from the database to Checkbox-->
                            </div>
                        </td>
                        <td><?= $result['id']?></td>
                        <th><img src="img/<?= $result["photo"] ?>" width="150px"/></th>
                        <td><?= $result['title']?></td>
                        <td><?= $result['content']?></td>                       
                    </tbody>
                    <?php 
                }  // With End of While, we pull our data and our query ends.

                ?>

            </table>
        </form>
    </div>
</div>
</div>

 <!--When we click on the "deleteall" checkbox, we add our javascript code so that all checkboxes are checked-->
<script type="text/javascript">
    $(document).ready(function () {
        $('#selectall').on('click', function () {
            if ($('#selectall:checked').length == $('#selectall').length) {
                $('input.chck:checkbox').prop('checked', true);
            } else {
                $('input.chck:checkbox').prop('checked', false);

            }
        });
    });
</script>
<script src="js/jquery-3.4.1.min.js"></script>  
<script src="js/bootstrap.min.js"></script>
</body>
</html>
```


Good Encodings
