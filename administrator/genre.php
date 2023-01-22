<?php

$type = "genre";

include '../res/assets/globalfunctions.php';

if(!isadminlogged())
{
    header('location:../admin_login.php');
}

$error_message = '';
$success_message='';

//adding genres
if(isset($_POST['add_genre']))
{
    $genre_label = '';
    if(empty($_POST['genre_label']))
    {
        $error_message = '<li>Genre label field must be filled.</li>';
    }
    else
    {
        $genre_label = trim($_POST['genre_label']);
    }
    if($error_message == '')
    {
        $exists_query = "SELECT * FROM genres_table
        WHERE genre_label = '".$genre_label."'";

        $stat = $connect->prepare($exists_query);
        $stat->execute();

        if($stat->rowCount() > 0)
        {
            $error_message = '<li>Genre already exists in database.</li>';
        }
        else
        {
            $data = array(
                ':genre_label' => $genre_label,
                ':created_on' => getdateandtime(),
                ':updated_on' => getdateandtime()
            );

            $insert_query = "INSERT INTO genres_table
                            (genre_label, created_on, updated_on)
                            VALUES (:genre_label, :created_on, :updated_on)";
            $stat = $connect->prepare($insert_query);
            $stat->execute($data);

            
            header('location:genre.php?msg=add');
        }
    }
    
}

if(isset($_POST['edit_genre']))
{
    $genre_label = '';
    if(empty($_POST['genre_label']))
    {
        $error_message = '<li>Genre label field must be filled.</li>';
    }
    else
    {
        $genre_label = trim($_POST['genre_label']);
    }
    if($error_message == '')
    {
        $exists_query = "SELECT * FROM genres_table
        WHERE genre_label = '".$genre_label."'
        AND genre_label != '".$_POST['old_genre_label']."'";

        $stat = $connect->prepare($exists_query);
        $stat->execute();

        if($stat->rowCount() > 0)
        {
            $error_message = '<li>Genre already exists in database.</li>';
        }
        else
        {
            $data = array(
                ':genre_label' => $genre_label,
                ':updated_on' => getdateandtime(),
                ':old_genre_label' => $_POST['old_genre_label']
            );

            $edit_query = "UPDATE genres_table
                            SET genre_label = :genre_label,
                            updated_on = :updated_on
                            WHERE genre_label = :old_genre_label";
            $stat = $connect->prepare($edit_query);
            $stat->execute($data);

            header('location:genre.php?msg=edit');
        }
    }
    
}

//displaying the genres table
$query = "SELECT * FROM genres_table
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
    <title>Genres . LibrayMS</title>
    
</head>
<body>

<div class="content">
<!--head title-->
    <div class="head_title">
        <img src="../res/Icons/genre.png" alt="Genres" style="height: 4rem;">
        | Genres
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
                <label for="genre_label"> <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> New Genre Label:</label>
                <input type="text" name="genre_label" id="genre_label" autocomplete="off">
                <input type="submit" value="Add" name="add_genre" style="width: fit-content;">
            </div>
        </form>
        <div class="messageBox">Add a genre label to your database for future assignment.</div>
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
            $old_genre_label = $_GET['code'];
            $query = "SELECT * FROM genres_table
                        WHERE genre_label = '$old_genre_label'";
            
            $edited_genre = $connect->query($query);
            foreach($edited_genre as $genre_row)
            {
    ?>
        <form spellcheck="false" method="POST" class="card" style="width: 50%;">
            <div>
                <label for="genre_label"> <img src="../res/Icons/edit.png" alt="Edit" style="height: 1rem;"> Edit Genre Label:</label>
                <input type="text" name="genre_label" id="genre_label" autocomplete="off" value="<?php echo $genre_row['genre_label']; ?>">
                <input type="hidden" name="old_genre_label" id="old_genre_label" value="<?php echo $_GET['code']; ?>">
                <input type="submit" value="Edit" name="edit_genre" style="width: fit-content;">
            </div>
        </form>
        <div class="messageBox">Edit the selected genre.</div>
    <?php
        if($error_message != '')
        {
            echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
        }
            }
        }
        else if($_GET['action']=='delete')
        {
                $old_genre_label = $_GET['code'];
                $query = "DELETE FROM genres_table
                            WHERE genre_label = '$old_genre_label'";
                $connect->query($query);
                header('location:genre.php?msg=delete');
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
                <th class="column_gapping">Genre label</th>
                <th class="column_gapping">Created On</th>
                <th class="column_gapping">Updated On</th>
                <th class="column_gapping">Operations</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
            if($stat->rowCount()>0)
            {
                foreach($stat->fetchAll() as $genre)
                {
                    echo '<tr>
                    <td class="column_gapping">'.$genre['genre_label'].'</td>
                    <td class="column_gapping">'.$genre['created_on'].'</td>
                    <td class="column_gapping">'.$genre['updated_on'].'</td>
                    <td class="column_gapping"><a  class="edit_a" href="genre.php?action=edit&code='.$genre['genre_label'].'">Edit</a>
                                               <button class="delete_a" onclick="deleteAlert(`'.$genre["genre_label"].'`, `'.$type.'`)" >Delete</button>
                    </td>
                        </tr>';
                }    
            }
            ?>
        </tbody>
    </table>
    <button onclick="location.href='genre.php?action=add'">Add genre</button>
    </div>
<!--else display the table code block-->    
    <?php
    }
    if(isset($_GET['msg']))
    {
        if($_GET['msg']=='add')
        {
            $success_message = '<li>Genre has been added to the database '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='edit')
        {
            $success_message = '<li>Genre has been edited '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='delete')
        {
            $success_message = '<li>Genre has been deleted '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        
    }
    ?>
    
</div>


</body>
</html>