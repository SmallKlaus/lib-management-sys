<?php



include '../res/assets/globalfunctions.php';
if(!isuserlogged())
{
    header('location:../user_login.php');
}

$user_id = $_SESSION['user_id'];
$price_data = array(
    ':min_price' => 0,
    ':max_price' => 10000000000,
    ':in_stock' => -1,
);

if(isset($_POST['apply']))
{
    if(!empty($_POST['min_price']) && $_POST['min_price']>0 && $_POST['min_price']<=$price_data[':max_price'])
    {
        $price_data[':min_price'] = $_POST['min_price'];
    }
    if(!empty($_POST['max_price']) && $_POST['max_price']>= $price_data[':min_price'] && $_POST['max_price']<2147483647)
    {
        $price_data[':max_price'] = $_POST['max_price'];
    }
    if(isset($_POST['in_stock']))
    {
        $price_data[':in_stock'] = 0;
    }
}

$fetch_query = "SELECT * FROM books_table WHERE (book_price BETWEEN :min_price AND :max_price) AND (book_stock > :in_stock)";
$stat = $connect->prepare($fetch_query);
$stat->execute($price_data);

$borrow_query = "SELECT * FROM user_accounts WHERE user_id = '$user_id'";
$borrow_stat = $connect->query($borrow_query);


include 'navcomp.php';

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member . LibrayMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/book.png" alt="Books" style="height: 4rem ;"> | Book Collection
        </div>
        <?php
        if($borrow_stat->rowCount()>0)
        {
            foreach($borrow_stat->fetchAll() as $row)
            {
                if($row['user_address'] == '' || $row['contact_number'] == '')
                {
        ?>
                    <div class="messageBox">
                        Setting up your contact number and address information is mandatory to borrow books. Please visit the Setting page.
                    </div>
        <?php
                }
            }
        }
        ?>
        <div class="card" style="width: 70%;">
            <table id="datatable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="column_gapping">Book Title</th>
                        <th class="column_gapping">Author Name</th>
                        <th class="column_gapping">Book Genres</th>
                        <th class="column_gapping">In Stock</th>
                        <th class="column_gapping">Book Rack</th>
                        <th class="column_gapping">Price ($)</th>
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
                            <tr>
                                <td class="column_gapping"><a target="_blank" href="https://www.google.com/search?q='.$book['book_name'].' '.trim($authors, " ,").'">&#x2022; '.$book['book_name'].'</a></td>
                                <td class="column_gapping" style="width: 200px;">'.trim($authors, " ,").'</td>
                                <td class="column_gapping" style="width: 200px;">'.trim($genres, " ,").'</td>
                                <td class="column_gapping" style="text-align: center;">'.$book['book_stock'].'</td>
                                <td class="column_gapping" style="text-align: center;">'.$book['book_rack'].'</td>
                                <td class="column_gapping" style="text-align: center;">'.$book['book_price'].'</td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <form method="post" style=" width: 100%; display:flex; align-items: center; flex-direction: column; row-gap: 10px; margin-right: auto;">
                <div style="display: flex; flex-direction: row; column-gap: 20px;">
                    <span>
                        <label for="min_price">min. Price : </label>
                        <input type="number" id="min_price" name= "min_price" value="<?php echo $price_data[':min_price']?>" placeholder="Minimum price Filter..." style="width:fit-content;">
                    </span>
                    <span>
                        <label for="max_price">max. Price : </label>
                        <input type="number" id="max_price" name= "max_price" value="<?php echo $price_data[':max_price']?>" placeholder="Maximum price Filter..." style="width:fit-content;">
                    </span>
                    <span>
                        <label for="in_stock">In Stock : </label>
                        <input type="checkbox" id="in_stock" name= "in_stock"  style="width:fit-content;" checked>
                    </span>
                </div>
                <input type="submit" value="Apply" name="apply" style="width:fit-content;">
            </form> 
        </div>
        <div class="messageBox">
             Navigate through our book collection, for more information on books click on the titles to prompt a google search of the books.
        </div>
    </div>
</body>
</html>