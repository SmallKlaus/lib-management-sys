<?php
$type = "book";

include '../res/assets/globalfunctions.php';


if(!isadminlogged())
{
    header('location:../admin_login.php');
}
$genres_query = "SELECT genre_id, genre_label FROM genres_table";
$authors_query = "SELECT author_id, author_name FROM authors_table";
$GLOBALS['all_genres'] = $connect->query($genres_query);
$GLOBALS['all_authors'] = $connect->query($authors_query);

//adding a book
$error_message = '';

if(isset($_POST['add_book']))
{
    //books_table data validation
    $data = array();
    if(empty($_POST['book_name']))
    {
        $error_message .= '<li>Book title field must be filled.</li>';
    }
    else
    {
        $stat = $connect->query("SELECT * FROM books_table WHERE book_name = '".$_POST['book_name']."'");
        if($stat->rowCount()>0) $error_message .= '<li>Book title already in database. </li>';
        else $data['book_name'] = $_POST['book_name'];
    }
    if(empty($_POST['book_isbn']))
    {
        $error_message .= '<li>Book ISBN field must be filled.</li>';
    }
    else
    {
        if(strlen($_POST['book_isbn'])!=13 && strlen($_POST['book_isbn'])!= 10)
        {
            $error_message .= '<li>Book ISBN must be thirteen or ten digits long.</li>';
        }
        else
        {
            $stat = $connect->query("SELECT * FROM books_table WHERE book_isbn = '".$_POST['book_isbn']."'");
            if($stat->rowCount()>0) $error_message .= '<li>Book ISBN already in database. </li>';
            else $data['book_isbn'] = $_POST['book_isbn'];
        }
    }
    if(empty($_POST['book_rack']))
    {
        $error_message .='<li>Book rack field must be selected.</li>';
    }
    else
    {
        $data['book_rack']=$_POST['book_rack'];
    }
    if(empty($_POST['book_price']))
    {
        $error_message .='<li>Book price field must be filled.</li>';
    }
    else
    {
        $data['book_price']=$_POST['book_price'];
    }
    if(empty($_POST['book_stock']))
    {
        $error_message .='<li>Book stock field must be filled.</li>';
    }
    else
    {
        $data['book_stock']=$_POST['book_stock'];
    }
    //genre and authors validation
    $genre_count = 0;
    $author_count = 0;
    if($GLOBALS['all_genres']->rowCount()>0)
    {
        foreach($GLOBALS['all_genres']->fetchAll() as $genre)
        {
            if(isset($_POST['genre'.$genre['genre_id']]))
            {
                $data['genre'.$genre_count]=$genre['genre_label'];
                $genre_count ++;
            }
        }
    }
    if($genre_count==0) {$error_message .= '<li>At least one genre must be selected.</li>';}
    if($GLOBALS['all_authors']->rowCount()>0)
    {
        foreach($GLOBALS['all_authors']->fetchAll() as $author)
        {
            if(isset($_POST['author'.$author['author_id']]))
            {
                $data['author'.$author_count]=$author['author_name'];
                $author_count ++;
            }
        }
    }
    if($author_count==0){ $error_message .= '<li>At least one author must be selected.</li>';}
    //inserting data
    if($error_message == '')
    {
        //inserting main book table data seperately
        $books_table_data = array(
            ':book_name' => $data['book_name'],
            ':book_rack' => $data['book_rack'],
            ':book_price' => $data['book_price'],
            ':book_stock' => $data['book_stock'],
            ':book_isbn'    => $data['book_isbn']
        );
        $query = "INSERT INTO books_table
                    (book_id, book_name, book_rack, book_price, book_stock, book_isbn)
                    VALUES (NULL, :book_name, :book_rack, :book_price, :book_stock, :book_isbn)";
        $stat = $connect->prepare($query);
        $stat->execute($books_table_data);
        //inserting genres and authors separately
        $stat = $connect->query("SELECT book_id FROM books_table WHERE book_name ='".$data['book_name']."'");
        $insert_data = array();
        foreach($stat->fetchAll() as $book_id) $insert_data[':book_id'] = $book_id['book_id'];
        $query = "INSERT INTO books_genres
                        VALUES (:book_id, :label)";
        $stat = $connect->prepare($query);
        for($x = 0; $x < $genre_count; $x++)
        {
            $insert_data[':label'] = $data['genre'.$x];
            $stat->execute($insert_data);
        }
        $query = "INSERT INTO books_authors
                        VALUES (:book_id, :label)";
        $stat = $connect->prepare($query);
        for($x = 0; $x < $author_count; $x++)
        {
            $insert_data[':label'] = $data['author'.$x];
            $stat->execute($insert_data);
        }
        header('location:book.php?msg=add');
    }
}

//editing a book
if(isset($_POST['edit_book']))
{
    //books_table data validation
    $data = array();
    if(empty($_POST['book_name']))
    {
        $error_message .= '<li>Book title field must be filled.</li>';
    }
    else
    {
        $stat = $connect->query("SELECT * FROM books_table WHERE( book_id != '".$_POST['book_id']."' AND book_name = '".$_POST['book_name']."')");
        if($stat->rowCount()>0) $error_message .= '<li>Book title already in database. </li>';
        else $data['book_name'] = $_POST['book_name'];
    }
    if(empty($_POST['book_rack']))
    {
        $error_message .='<li>Book rack field must be selected.</li>';
    }
    else
    {
        $data['book_rack']=$_POST['book_rack'];
    }
    if(empty($_POST['book_price']))
    {
        $error_message .='<li>Book price field must be filled.</li>';
    }
    else
    {
        $data['book_price']=$_POST['book_price'];
    }
    if(empty($_POST['book_stock']))
    {
        $error_message .='<li>Book stock field must be filled.</li>';
    }
    else
    {
        $data['book_stock']=$_POST['book_stock'];
    }
    //genre and authors validation
    $genre_count = 0;
    $author_count = 0;
    if($GLOBALS['all_genres']->rowCount()>0)
    {
        foreach($GLOBALS['all_genres']->fetchAll() as $genre)
        {
            if(isset($_POST['genre'.$genre['genre_id']]))
            {
                $data['genre'.$genre_count]=$genre['genre_label'];
                $genre_count ++;
            }
        }
    }
    if($genre_count==0) {$error_message .= '<li>At least one genre must be selected.</li>';}
    if($GLOBALS['all_authors']->rowCount()>0)
    {
        foreach($GLOBALS['all_authors']->fetchAll() as $author)
        {
            if(isset($_POST['author'.$author['author_id']]))
            {
                $data['author'.$author_count]=$author['author_name'];
                $author_count ++;
            }
        }
    }
    if($author_count==0){ $error_message .= '<li>At least one author must be selected.</li>';}
    if($error_message == '')
    {
        //updating main book table data seperately
        $books_table_data = array(
            ':book_name' => $data['book_name'],
            ':book_rack' => $data['book_rack'],
            ':book_price' => $data['book_price'],
            ':book_stock' => $data['book_stock'],
            ':book_id' => $_POST['book_id']
        );
        $query = "UPDATE books_table
                    SET book_name = :book_name,
                        book_rack = :book_rack,
                        book_price = :book_price,
                        book_stock = :book_stock
                    WHERE book_id = :book_id";
        $stat = $connect->prepare($query);
        $stat->execute($books_table_data);
        //deleting and re-inserting genres and authors data in their respective tables
        $connect->query("DELETE FROM books_genres WHERE book_id= '".$_POST['book_id']."'");
        $connect->query("DELETE FROM books_authors WHERE book_id= '".$_POST['book_id']."'");
        $insert_data = array(
            ':book_id' => $_POST['book_id']
        );
        $query = "INSERT INTO books_genres
                    VALUES (:book_id, :label)";
        $stat = $connect->prepare($query);
        for($x = 0; $x < $genre_count; $x++)
        {
            $insert_data[':label'] = $data['genre'.$x];
            $stat->execute($insert_data);
        }
        $query = "INSERT INTO books_authors
                    VALUES (:book_id, :label)";
        $stat = $connect->prepare($query);
        for($x = 0; $x < $author_count; $x++)
        {
            $insert_data[':label'] = $data['author'.$x];
            $stat->execute($insert_data);
        } 
    header('location:book.php?msg=edit');  
    }
}

//issuing books
if(isset($_POST['issue_book']))
{
    $data = array(
        ':book_isbn' => $_POST['book_isbn'],
        ':created_on'   => getdateandtime()
    );
    if(empty($_POST['user_id']))
    {
        $error_message .='<li>Member ID must be selected.</li>';
    }
    else
    {  
        $data[':user_id'] = $_POST['user_id'];
        $check_query = "SELECT * FROM issues_table WHERE user_id = '".$data[':user_id']."' AND book_isbn = '".$data[':book_isbn']."' ";
        $check_stat = $connect->query($check_query);
        if($check_stat->rowCount() > 0)
        {
            $error_message .='<li>Book already issued to the selected member.</li>';
        }
        else
        {
            $check_query = "SELECT * FROM user_accounts 
                            WHERE user_id = '".$data[':user_id']."' AND (contact_number='' OR user_address='' OR
                                                                        user_borrowed >= (SELECT loan_peruser 
                                                                                        FROM admin_settings LIMIT 1)                                                                                               
            )";
            $check_stat = $connect->query($check_query);
            if($check_stat->rowCount()>0)
            {
                $error_message .='<li>User must meet the requirements to borrow books.</li>';
            }
        }
    }
    if($error_message == '')
    {
        //inserting new issue
        $issue_query = "INSERT INTO issues_table (user_id, book_isbn, created_on) VALUES (:user_id, :book_isbn, :created_on)";
        $connect->prepare($issue_query)->execute($data);
        //changing book stock
        $issue_query = "UPDATE books_table SET book_stock = book_stock-1 WHERE book_isbn = '".$data[':book_isbn']."'";
        $connect->query($issue_query);
        //changing user borrowed books
        $issue_query = "UPDATE user_accounts SET user_borrowed = user_borrowed+1 WHERE user_id = '".$data[':user_id']."'";
        $connect->query($issue_query);
        header("location:book.php?msg=issue");
    }
}

//fetching data to fill the books table
$query = "SELECT * FROM books_table
            ORDER BY book_name ASC";
$stat = $connect->query($query);

include 'navcomp.php';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books . LibraryMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/book.png" alt="books" style="height:4rem ;">
            | Books
        </div>
        <?php
        if(isset($_GET['action']))
        {
            if($_GET['action']=='add')
            {
                $GLOBALS['all_genres'] = $connect->query($genres_query);
                $GLOBALS['all_authors'] = $connect->query($authors_query);
        ?>
                <form spellcheck="false" method="POST" class="card" style="width: 50%;">
                    <label for="book_name" style="margin-right:auto;"><img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book Title :</label>
                    <input type="text" id="book_name" name="book_name" placeholder="Enter the book title...">
                    <label for="book_isbn" style="margin-right:auto;"><img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book ISBN :</label>
                    <input type="text" id="book_isbn" name="book_isbn" placeholder="Enter the book ISBN...">
                    <!--genres input-->
                    <label style="margin-right:auto;">
                        <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book Genres :
                        <input type="text" id="search_genres" onkeyup="findgenreintarget()" placeholder="Search the genres..." style="margin: 10px;">
                    </label>
                    <span class="grid_checkboxes">
                        <?php
                        if($GLOBALS['all_genres']->rowCount()>0)
                        {
                            foreach($GLOBALS['all_genres']->fetchAll() as $genre)
                            {
                                echo'<span class="grid_ele">
                                    <input id="'.$genre['genre_label'].'" name="genre'.$genre['genre_id'].'" type="checkbox" value="'.$genre['genre_label'].'" style="cursor:pointer;">
                                    <label class="genre_target" for="'.$genre['genre_label'].'" style="margin-right:auto; cursor:pointer;">'.$genre['genre_label'].'</label>
                                </span>';
                                
                            }
                        }
                        ?>
                    </span>
                    <!--authors input-->
                    <label style="margin-right:auto;">
                        <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book Authors :
                        <input type="text" id="search_authors" onkeyup="findauthorintarget()" placeholder="Search the authors..." style="margin: 10px;">
                    </label>
                    <span class="grid_checkboxes">
                        <?php
                        if($GLOBALS['all_authors']->rowCount()>0)
                        {
                            foreach($GLOBALS['all_authors']->fetchAll() as $author)
                            {
                                echo'<span class="grid_ele">
                                    <input id="'.$author['author_name'].'" name="author'.$author['author_id'].'" type="checkbox" value="'.$author['author_name'].'" style="cursor:pointer;">
                                    <label class="author_target" for="'.$author['author_name'].'" style="margin-right:auto; cursor:pointer;">'.$author['author_name'].'</label>
                                </span>';
                                
                            }
                        }
                        ?>
                    </span>
                    <!--rack input-->
                    <label for="book_rack" style="margin-right:auto;">
                        <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book Rack :
                    </label>
                    <select type="number" name="book_rack" id="book_rack">
                        <option value="">Choose the corresponding book rack</option>
                        <?php
                        $query = "SELECT location_name FROM locations_table";
                        $stat= $connect->query($query);
                        if($stat->rowCount()>0){
                            foreach($stat->fetchAll() as $location)
                            {
                                echo '<option value="'.$location['location_name'].'">'.$location['location_name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                    <!--price input-->
                    <label for="book_price" style="margin-right:auto;">
                        <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book Price :
                    </label>
                    <input type="number" step="0.01" id="book_price" name="book_price">
                    <!--stock input-->
                    <label for="book_stock" style="margin-right:auto;">
                        <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Book Stock :
                    </label>
                    <input type="number" id="book_stock" name="book_stock">
                    <input type="submit" value="Add Book" name="add_book" style="width: fit-content;">
                </form>
        <?php
                if($error_message != '')
                {
                    echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
                }
            }   
            else if($_GET['action']=='edit')
            {
                $GLOBALS['all_genres'] = $connect->query($genres_query);
                $GLOBALS['all_authors'] = $connect->query($authors_query);
                $edited_book = $connect->query("SELECT * FROM books_table WHERE book_id = '".$_GET['code']."'");
                if($edited_book->rowCount()>0)
                {
                    foreach($edited_book->fetchAll() as $book)
                    {
        ?>
                    <form spellcheck="false" method="POST" class="card" style="width: 50%;">
                    <label for="book_name" style="margin-right:auto;"><img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Book Title :</label>
                    <input type="text" id="book_name" name="book_name" value="<?php echo $book['book_name'] ;?>">
                    <!--genres input-->
                    <label style="margin-right:auto;">
                        <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Book Genres :
                        <input type="text" id="search_genres" onkeyup="findgenreintarget()" placeholder="Search the genres..." style="margin: 10px;">
                    </label>
                    <span class="grid_checkboxes">
                        <?php
                        if($GLOBALS['all_genres']->rowCount()>0)
                        {
                            foreach($GLOBALS['all_genres']->fetchAll() as $genre)
                            {
                                echo'<span class="grid_ele">
                                    <input id="'.$genre['genre_label'].'" name="genre'.$genre['genre_id'].'" type="checkbox" value="'.$genre['genre_label'].'" style="cursor:pointer;" '.isgenrechecked($_GET['code'], $genre['genre_label']).'>
                                    <label class="genre_target" for="'.$genre['genre_label'].'" style="margin-right:auto; cursor:pointer;">'.$genre['genre_label'].'</label>
                                </span>';
                                
                            }
                        }
                        ?>
                    </span>
                    <!--authors input-->
                    <label style="margin-right:auto;">
                        <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Book Authors :
                        <input type="text" id="search_authors" onkeyup="findauthorintarget()" placeholder="Search the authors..." style="margin: 10px;">
                    </label>
                    <span class="grid_checkboxes">
                        <?php
                        if($GLOBALS['all_authors']->rowCount()>0)
                        {
                            foreach($GLOBALS['all_authors']->fetchAll() as $author)
                            {
                                echo'<span class="grid_ele">
                                    <input id="'.$author['author_name'].'" name="author'.$author['author_id'].'" type="checkbox" value="'.$author['author_name'].'" style="cursor:pointer;" '.isauthorchecked($_GET['code'], $author['author_name']).'>
                                    <label class="author_target" for="'.$author['author_name'].'" style="margin-right:auto; cursor:pointer;">'.$author['author_name'].'</label>
                                </span>';
                                
                            }
                        }
                        ?>
                    </span>
                    <!--rack input-->
                    <label for="book_rack" style="margin-right:auto;">
                        <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Book Rack :
                    </label>
                    <select type="number" name="book_rack" id="book_rack">
                        <option value="">Choose the corresponding book rack</option>
                        <?php
                        $query = "SELECT location_name FROM locations_table";
                        $stat= $connect->query($query);
                        if($stat->rowCount()>0){
                            foreach($stat->fetchAll() as $location)
                            {
                                echo '<option value="'.$location['location_name'].'">'.$location['location_name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                    <!--price input-->
                    <label for="book_price" style="margin-right:auto;">
                        <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Book Price :
                    </label>
                    <input type="number" step="0.01" id="book_price" name="book_price" value="<?php echo $book['book_price'];?>">
                    <!--stock input-->
                    <label for="book_stock" style="margin-right:auto;">
                        <img src="../res/Icons/edit.png" alt="edit" style="height: 1rem;"> Edit Book Stock :
                    </label>
                    <input type="number" id="book_stock" name="book_stock" value="<?php echo $book['book_stock'];?>">
                    <input type="hidden" name="book_id" value="<?php echo $_GET['code']?>">
                    <input type="submit" value="Edit Book" name="edit_book" style="width: fit-content;">
                </form>
                <script> document.getElementById("book_rack").value="<?php echo $book['book_rack']; ?>"</script>
        <?php
                if($error_message != '')
                {
                    echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
                }
                    }
                }
            }
            else if($_GET['action']=='delete')
            {
                $delete_data = $connect->query("SELECT book_id FROM books_table WHERE book_name = '".$_GET['code']."'");
                $old_book_id= $delete_data->fetch(PDO::FETCH_ASSOC)['book_id'];
                $book_isbn = $connect->query("SELECT book_isbn FROM books_table WHERE book_id = $old_book_id")->fetch(PDO::FETCH_ASSOC)['book_isbn'];
                $exists_stat = $connect->query("SELECT * FROM issues_table WHERE book_isbn = '$book_isbn'");
                if($exists_stat->rowCount()>0)
                {
                    header('location:book.php?msg=no_delete');
                }
                else
                {
                    $connect->query("DELETE FROM books_table WHERE book_id = $old_book_id");
                    $connect->query("DELETE FROM books_genres WHERE book_id = $old_book_id");
                    $connect->query("DELETE FROM books_authors WHERE book_id = $old_book_id");
                    header("location:book.php?msg=delete");
                }
            }
            else if($_GET['action']=='issue')
            {
                $book_isbn = $_GET['code'];
                $stock_stat = $connect->query("SELECT * FROM books_table WHERE book_isbn = '$book_isbn' AND book_stock > 0");
                if($stock_stat->rowCount()>0)
                {
                $user_ids_query = "SELECT user_id FROM user_accounts
                                    WHERE contact_number != '' AND user_address != '' 
                                    AND user_borrowed <(
                                        SELECT loan_peruser FROM admin_settings LIMIT 1
                                    )
                                    AND user_id NOT IN (
                                        SELECT user_id FROM issues_table
                                        WHERE book_isbn = '$book_isbn'
                                    ) ";
                $user_ids = $connect->query($user_ids_query);
        ?>
                <form method="POST" style="width: 50%;" class="card" spellcheck="false">
                    <label for="user_id" style="margin-right: auto;">
                        <img src="../res/Icons/add.png" alt="add" style="height: 1rem;"> Input the member's ID: 
                    </label>
                    <input autocomplete="off" type="search" list="user_ids"  id="user_id" name="user_id">
                    <datalist id="user_ids">
        <?php
                        if($user_ids->rowCount()>0)
                        {
                            foreach($user_ids->fetchAll() as $user_id)
                            {
                                echo '<option value="'.$user_id['user_id'].'">';
                            }
                        }
        ?>
                    </datalist>
                    <input type="hidden" value="<?php echo $book_isbn; ?>" name="book_isbn">
                    <input type="submit" value="Issue Book" name="issue_book" style="width: fit-content;">
                </form>
                <div class="messageBox">Please only input the suggested member IDs. If a member ID isn't available, please check the members page to find the regulations breach.</div>
        <?php
                if($error_message != '')
                {
                    echo '<div class="warning messageBox"><ul>'.$error_message.'</ul></div>';
                }
            }
            else
            {
                header("location:book.php?msg=stock");
            }
            }
        }
        else
        {
        ?>
            <div class="card" style="width: 70%;">
            <table id="datatable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="column_gapping">Book ISBN</th>
                        <th class="column_gapping">Book Title</th>
                        <th class="column_gapping">Author Name</th>
                        <th class="column_gapping">Book Genres</th>
                        <th class="column_gapping">In Stock</th>
                        <th class="column_gapping">Book Rack</th>
                        <th class="column_gapping">Price ($)</th>
                        <th class="column_gapping" style="width: 155px;">Operations</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if($stat->rowCount()>0)
                    {
                        foreach($stat->fetchAll() as $book)
                        {
                            $genres = "";
                            $authors = "";
                            //fetching genre data
                            $query = "SELECT * FROM books_genres
                                        WHERE book_id = '".$book['book_id']."'";
                            $genrestat = $connect->query($query);
                           
                            if($genrestat->rowCount()>0)
                            {
                                foreach($genrestat->fetchAll() as $book_genre)
                                {
                                    $genres .= $book_genre['genre_label'].", ";
                                }
                            }
                            //fetching author data
                            $query = "SELECT * FROM books_authors
                                        WHERE book_id = '".$book['book_id']."'";
                            $authorstat = $connect->query($query);
                            
                            if($authorstat->rowCount()>0)
                            {
                                foreach($authorstat->fetchAll() as $book_author)
                                {
                                    $authors .= $book_author['author_name'].", ";
                                }
                            }

                            echo'
                            <tr title="Make an Issue" class="clickable_row" data-href="#">
                                <td class="column_gapping clickable_row" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.$book['book_isbn'].'</td>
                                <td class="column_gapping clickable_row" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.$book['book_name'].'</td>
                                <td class="column_gapping clickable_row" style="width: 200px;" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.trim($authors, " ,").'</td>
                                <td class="column_gapping clickable_row" style="width: 200px;" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.trim($genres, " ,").'</td>
                                <td class="column_gapping clickable_row" style="text-align: center;" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.$book['book_stock'].'</td>
                                <td class="column_gapping clickable_row" style="text-align: center;" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.$book['book_rack'].'</td>
                                <td class="column_gapping clickable_row" style="text-align: center;" data-href="book.php?action=issue&code='.$book['book_isbn'].'">'.$book['book_price'].'</td>
                                <td class="column_gapping" style="width: 155px; text-align:center;"><a  class="edit_a" href="book.php?action=edit&code='.$book['book_id'].'">Edit</a>
                                    <button class="delete_a" onclick="deleteAlert(`'.$book["book_name"].'`, `'.$type.'`)" >Delete</button>
                                </td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <button onclick="location.href='book.php?action=add'">Add book</button>
            </div>
            <div class="messageBox">Click on the book's ISBN to create an issue for a member.</div>
        <?php
        }
        if(isset($_GET['msg']))
        {
        if($_GET['msg']=='add')
        {
            $success_message = '<li>Book has been added to the database. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='edit')
        {
            $success_message = '<li>Book has been edited. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='delete')
        {
            $success_message = '<li>Book has been deleted. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='stock')
        {
            $error_message = '<li>Book not in stock to be issued.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox warning " ><ul>'.$error_message.'</ul></div>';
        }
        if($_GET['msg']=='issue')
        {
            $success_message = '<li>Book has been issued. '.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox success " ><ul>'.$success_message.'</ul></div>';
        }
        if($_GET['msg']=='no_delete')
        {
            $error_message = '<li>All book issues must be cleared before deleting the book.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';
            echo '<div id="succ" class="messageBox warning " ><ul>'.$error_message.'</ul></div>';
        }
        }
        ?>
        
    </div>
</body>
</html>