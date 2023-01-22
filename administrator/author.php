<?php

$type = "author";

include '../res/assets/globalfunctions.php';

if(!isadminlogged())
{
    header('location:../admin_login.php');
}

$error_message = '';
$success_message='';

//adding authors
if(isset($_POST['add_author']))
{
    $author_name = '';
    if(empty($_POST['author_name']))
    {
        $error_message = '<li>Author name field must be filled.</li>';
    }
    else
    {
        $author_name = trim($_POST['author_name']);
    }
    if($error_message == '')
    {
        $exists_query = "SELECT * FROM authors_table
        WHERE author_name = '".$author_name."'";

        $stat = $connect->prepare($exists_query);
        $stat->execute();

        if($stat->rowCount() > 0)
        {
            $error_message = '<li>Author already exists in database.</li>';
        }
        else
        {
            $data = array(
                ':author_name' => $author_name,
                ':created_on' => getdateandtime(),
                ':updated_on' => getdateandtime()
            );

            $insert_query = "INSERT INTO authors_table
                            (author_name, created_on, updated_on)
                            VALUES (:author_name, :created_on, :updated_on)";
            $stat = $connect->prepare($insert_query);
            $stat->execute($data);

            
            header('location:author.php?msg=add');
        }
    }
    
}
//editing authors
if(isset($_POST['edit_author']))
{
    $author_name = '';
    if(empty($_POST['author_name']))
    {
        $error_message = '<li>Author name field must be filled.</li>';
    }
    else
    {
        $author_name = trim($_POST['author_name']);
    }
    if($error_message == '')
    {
        $exists_query = "SELECT * FROM authors_table
        WHERE author_name = '".$author_name."'
        AND author_name != '".$_POST['old_author_name']."'";

        $stat = $connect->prepare($exists_query);
        $stat->execute();

        if($stat->rowCount() > 0)
        {
            $error_message = '<li>Author already exists in database.</li>';
        }
        else
        {
            $data = array(
                ':author_name' => $author_name,
                ':updated_on' => getdateandtime(),
                ':old_author_name' => $_POST['old_author_name']
            );

            $edit_query = "UPDATE authors_table
                            SET author_name = :author_name,
                            updated_on = :updated_on
                            WHERE author_name = :old_author_name";
            $stat = $connect->prepare($edit_query);
            $stat->execute($data);

            header('location:author.php?msg=edit');
        }
    }
    
}

//displaying the authors table
$query = "SELECT * FROM authors_table
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
    <title>Authors . LibrayMS</title>
    
</head>
<body>

<div class="content">
<!--head title-->
    <div class="head_title">
        <img src="../res/Icons/author.png" alt="authors" style="height: 4rem;">
        | Authors
        <!--if author is added successfully-->
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
                <label for="author_name"> <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> New Author Name:</label>
                <input type="text" name="author_name" id="author_name" autocomplete="off">
                <input type="submit" value="Add" name="add_author" style="width: fit-content;">
            </div>
        </form>
        <div class="messageBox">Add an author name to your database for future assignment.</div>
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
            $old_author_name = $_GET['code'];
            $query = "SELECT * FROM authors_table
                        WHERE author_name = '$old_author_name'";
            
            $edited_author = $connect->query($query);
            foreach($edited_author as $author_row)
            {
    ?>
        <form spellcheck="false" method="POST" class="card" style="width: 50%;">
            <div>
                <label for="author_name"> <img src="../res/Icons/edit.png" alt="Edit" style="height: 1rem;"> Edit Author Name:</label>
                <input type="text" name="author_name" id="author_name" autocomplete="off" value="<?php echo $author_row['author_name']; ?>">
                <input type="hidden" name="old_author_name" id="old_author_name" value="<?php echo $_GET['code']; ?>">
                <input type="submit" value="Edit" name="edit_author" style="width: fit-content;">
            </div>
        </form>
        <div class="messageBox">Edit the selected author.</div>
    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
            }
        }
        else if($_GET['action']=='delete')
        {
                $old_author_name = $_GET['code'];
                $query = "DELETE FROM authors_table
                            WHERE author_name = '$old_author_name'";
                $connect->query($query);
                header('location:author.php?msg=delete');
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
                <th class="column_gapping">Author Name</th>
                <th class="column_gapping">Created On</th>
                <th class="column_gapping">Updated On</th>
                <th class="column_gapping">Operations</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
            if($stat->rowCount()>0)
            {
                foreach($stat->fetchAll() as $author)
                {
                    echo '<tr>
                    <td class="column_gapping">'.$author['author_name'].'</td>
                    <td class="column_gapping">'.$author['created_on'].'</td>
                    <td class="column_gapping">'.$author['updated_on'].'</td>
                    <td class="column_gapping"><a  class="edit_a" href="author.php?action=edit&code='.$author['author_name'].'">Edit</a>
                                               <button class="delete_a" onclick="deleteAlert(`'.$author["author_name"].'`, `'.$type.'`)" >Delete</button>
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
    <button onclick="location.href='author.php?action=add'">Add author</button>
    </div>
<!--else display the table code block-->    
    <?php
    }
    if(isset($_GET['msg']))
    {
        if($_GET['msg']=='add')
        {
            $success_message = '<li>Author has been added to the database '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='edit')
        {
            $success_message = '<li>Author has been edited '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='delete')
        {
            $success_message = '<li>Author has been deleted '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        
    }
    ?>
    
</div>


</body>
</html>