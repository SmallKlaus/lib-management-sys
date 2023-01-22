<?php

$type = "location";

include '../res/assets/globalfunctions.php';

if(!isadminlogged())
{
    header('location:../admin_login.php');
}

$error_message = '';
$success_message='';

//adding locations
if(isset($_POST['add_location']))
{
    $location_name = '';
    if(empty($_POST['location_name']))
    {
        $error_message = '<li>Book rack field must be filled.</li>';
    }
    else
    {
        $location_name = trim($_POST['location_name']);
    }
    if($error_message == '')
    {
        $exists_query = "SELECT * FROM locations_table
        WHERE location_name = '".$location_name."'";

        $stat = $connect->prepare($exists_query);
        $stat->execute();

        if($stat->rowCount() > 0)
        {
            $error_message = '<li>Rack already exists in database.</li>';
        }
        else
        {
            $data = array(
                ':location_name' => $location_name,
                ':created_on' => getdateandtime(),
                ':updated_on' => getdateandtime()
            );

            $insert_query = "INSERT INTO locations_table
                            (location_name, created_on, updated_on)
                            VALUES (:location_name, :created_on, :updated_on)";
            $stat = $connect->prepare($insert_query);
            $stat->execute($data);

            
            header('location:location.php?msg=add');
        }
    }
    
}
//editing locations
if(isset($_POST['edit_location']))
{
    $location_name = '';
    if(empty($_POST['location_name']))
    {
        $error_message = '<li>Book rack field must be filled.</li>';
    }
    else
    {
        $location_name = trim($_POST['location_name']);
    }
    if($error_message == '')
    {
        $exists_query = "SELECT * FROM locations_table
        WHERE location_name = '".$location_name."'
        AND location_name != '".$_POST['old_location_name']."'";

        $stat = $connect->prepare($exists_query);
        $stat->execute();

        if($stat->rowCount() > 0)
        {
            $error_message = '<li>Rackk already exists in database.</li>';
        }
        else
        {
            $data = array(
                ':location_name' => $location_name,
                ':updated_on' => getdateandtime(),
                ':old_location_name' => $_POST['old_location_name']
            );

            $edit_query = "UPDATE locations_table
                            SET location_name = :location_name,
                            updated_on = :updated_on
                            WHERE location_name = :old_location_name";
            $stat = $connect->prepare($edit_query);
            $stat->execute($data);

            header('location:location.php?msg=edit');
        }
    }
    
}

//displaying the locations table
$query = "SELECT * FROM locations_table
            ORDER BY created_on ASC";

$stat = $connect->prepare($query);
$stat->execute();



include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Racks . LibrayMS</title>
    
</head>
<body>

<div class="content">
<!--head title-->
    <div class="head_title">
        <img src="../res/Icons/location.png" alt="locations" style="height: 4rem;">
        | Book Racks
    </div>
<!--if add button clicked, take me to adding page-->
    <?php
    
    if(isset($_GET['action']))
    {
        if($_GET['action']=='add')
        {
    ?>
    <!--code block-->
        <form spellcheck="false" method="POST" class="card" style="width: 50%;">
            <div>
                <label for="location_name"> <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> New Book Rack:</label>
                <input type="text" name="location_name" id="location_name" autocomplete="off">
                <input type="submit" value="Add" name="add_location" style="width: fit-content;">
            </div>
        </form>
        <div class="messageBox">Add a book rack to your database for future assignment.</div>
        <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
        ?>
    <!--code block-->
    <?php
        }
        else if($_GET['action']=='edit')
        {
            $old_location_name = $_GET['code'];
            $query = "SELECT * FROM locations_table
                        WHERE location_name = '$old_location_name'";
            
            $edited_location = $connect->query($query);
            foreach($edited_location as $location_row)
            {
    ?>
        <form spellcheck="false" method="POST" class="card" style="width: 50%;">
            <div>
                <label for="location_name"> <img src="../res/Icons/edit.png" alt="Edit" style="height: 1rem;"> Edit Book Rack:</label>
                <input type="text" name="location_name" id="location_name" autocomplete="off" value="<?php echo $location_row['location_name']; ?>">
                <input type="hidden" name="old_location_name" id="old_location_name" value="<?php echo $_GET['code']; ?>">
                <input type="submit" value="Edit" name="edit_location" style="width: fit-content;">
            </div>
        </form>
        <div class="messageBox">Edit the selected rack.</div>
    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
            }
        }
        else if($_GET['action']=='delete')
        {
                $old_location_name = $_GET['code'];
                $query = "DELETE FROM locations_table
                            WHERE location_name = '$old_location_name'";
                $connect->query($query);
                header('location:location.php?msg=delete');
        }
    }
    else
    {
    ?>
<!--else display the table code block-->
    <div class="card" style=" width: 70%;">
    <table id="datatable" class="table" style="width: 100%;">
        <thead>
            <tr>
                <th class="column_gapping">Book Rack</th>
                <th class="column_gapping">Created On</th>
                <th class="column_gapping">Updated On</th>
                <th class="column_gapping">Operations</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
            if($stat->rowCount()>0)
            {
                foreach($stat->fetchAll() as $location)
                {
                    echo '<tr>
                    <td class="column_gapping">'.$location['location_name'].'</td>
                    <td class="column_gapping">'.$location['created_on'].'</td>
                    <td class="column_gapping">'.$location['updated_on'].'</td>
                    <td class="column_gapping"><a  class="edit_a" href="location.php?action=edit&code='.$location['location_name'].'">Edit</a>
                                               <button class="delete_a" onclick="deleteAlert(`'.$location["location_name"].'`, `'.$type.'`)" >Delete</button>
                    </td>
                        </tr>';
                }    
            }
            else
            {
                echo '<tr>
                <td colspan="4">No available data.</td>
                    </tr>';
            }
            ?>
        </tbody>
    </table>
    <button onclick="location.href='location.php?action=add'">Add Rack</button>
    </div>
<!--else display the table code block-->    
    <?php
    }
    if(isset($_GET['msg']))
    {
        if($_GET['msg']=='add')
        {
            $success_message = '<li>Rack has been added to the database '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='edit')
        {
            $success_message = '<li>Rack has been edited '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='delete')
        {
            $success_message = '<li>Rack has been deleted '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        
    }
    ?>
    
</div>


</body>
</html>