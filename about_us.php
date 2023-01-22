<?php
include 'res/assets/connect.php';

$fetch_settings = $connect->query("SELECT * FROM admin_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$number_books = $connect->query("SELECT SUM(book_stock) AS total FROM books_table")->fetch(PDO::FETCH_ASSOC)['total'];
$genres = $connect->query("SELECT genre_label FROM genres_table");
$authors = $connect->query("SELECT author_name FROM authors_table");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us . LibraryMS</title>
    <link rel="icon" href="res/Icons/LMSicon.png">
    <link rel="stylesheet" href="res/assets/styles.css">
    <style>
        body
        {
            overflow-x: hidden;
        }
    </style>
    <script defer src="res/assets/global.js"></script>
    <script>
        //history.scrollRestoration = 'manual';
    </script>
</head>
<body>
<script>
    history.scrollRestoration = 'manual';
    let low_res = document.createElement('div');
    low_res.id = "low_res";
    low_res.classList.add('low_res');

    let low_res_text = document.createElement('h2');
    low_res_text.innerText = 'Please Use a Device with a Resolution width higher than 1600px for a pleasant experience!\nTry zooming out in your browser or putting it to full screen.';
    low_res_text.style.textShadow = '0 0 20px black';

    low_res.appendChild(low_res_text);
    document.body.append(low_res);
    if(window.innerWidth< 1600)
    { 
        low_res.style.visibility = 'visible';
        document.body.classList.remove('flex');

    }
    window.addEventListener("resize", function(){
        if(window.innerWidth < 1600)
        {
            low_res.style.visibility = 'visible';
            document.body.classList.remove('flex');
        }
        else
        {
            low_res.style.visibility = 'hidden';
            document.body.classList.add('flex');
        }
    })
</script>
    <div class="alternative_content flex" style="row-gap: 0px; padding: 100px 0;">
<!--intro-->
        <section class="flex hidden_bot" style="width: 100%;">
            <a href="index.php"><img src="res/Icons/LMSicon.png" alt="LibraryMS" style="height: 10rem;"></a>
            <h1>.About <?php echo $fetch_settings['library_name'].' Library.'; ?></h1>
            <hr style="margin-top: 150px; margin-bottom:50px; width: 30%; border-color: antiquewhite;">
            
            <div class="mouse_scroll" onclick="window.scrollTo(0,1000)" style="cursor: pointer;">
                <div class="mouse">
                <div class="wheel"></div>
                </div>
                <div>
                    <span class="m_scroll_arrows unu"></span>
                    <span class="m_scroll_arrows doi"></span>
                    <span class="m_scroll_arrows trei"></span>
                </div>
            </div>

        </section>
        <!--Become a member-->
        <section class="flex" style="margin-top: 300px; row-gap: 80px; width: 100%;">
            <section class="flex hidden_right" style=" flex-direction: row; width: 100%; position:relative; right:0;">
                <div class="card" style="flex-direction: row; justify-content: space-around; align-items:center; width:55%; margin-left: 200px;">
                    <div style="width: 50%; text-align: center;">
                        <h2 style="font-size: 50px;">Access <?php echo '&#183;'.$fetch_settings['library_name'].'&#183;'?> library's collection</h1>
                        <h2>Query for books, manage your account.</h2>
                        <h2>It's free, don't miss out on our service.</h2>
                    </div>
                    <div class="signup" style="width: fit-content;">
                        <button  onclick="window.location.href='signup.php'">Sign up</button>
                    </div>   
                </div>
                <hr style="width: 40%;">
            </section>
            <section class="flex hidden_left" style="flex-direction:row; width: 100%;">
                <hr style="width: 40%;">
                <h2 style=" text-shadow: 0 0 20px black; text-align: center; color:white; font-size:40px; border-left: 5px solid grey; padding-left: 10px;">
                    Become a new member of <?php echo $fetch_settings['library_name'] ?> library by signing up on our main page.
                </h2>
            </section>
        </section>
        
        

        <!--<ul class="flex">

                        <li style="font-size: 18px ;">Clerks ready to serve and help you find your books and resources.</li><br>
        
                        <li style="font-size: 18px ;">Forever updated collection of books, with updated editions and new release monthly.</li><br>
        </ul>-->
<!--Genres-->
        
        <div class="flex" style="width: 100%; margin-top: 300px;">

            <section class="flex section_card hidden_left" style="text-align:center;position: absolute; right: 20px; width: 300px; ">
                <h2 style="color: antiquewhite; border-left:3px solid antiquewhite; border-right:3px solid antiquewhite;">
                    Explore the different genres of books we offer
                </h2><br>
                <div    style="display:flex; flex-direction:column; width: 100%;row-gap: 10px;
                         align-content: center;
                         height: 400px; overflow-y:auto; overflow-x:hidden;">
<?php
                    if($genres->rowCount()>0)
                    {
                        foreach($genres->fetchAll() as $genre)
                        {
                            echo '<a target="blank" href="https://www.google.com/search?q='.$genre['genre_label'].' Genre, Books">'.$genre['genre_label'].'</a>';
                        }
                    }
?>
                </div>
                <h1>...</h1>    
            </section>
            <section class="hidden_left section_card" style="border: 5px solid grey;">
                <h2 style="color:antiquewhite;">| Explore a wide range of genres ... |</h2>
                <div class="" style="padding:50px 40px; display:flex; flex-direction: row; column-gap: 70px;">
                    <div class="flex icon hidden_left" style="row-gap: 10px;">
                        <div class="card" style="min-width:fit-content ;">
                            <img src="res/Images/thriller.png" alt="Thriller" style="height: 8rem;">
                        </div>
                        <h2>Thriller</h2>
                    </div>
                    <div class="flex icon hidden_left" style="row-gap: 10px;">
                        <div class="card" style="min-width:fit-content ;">
                            <img src="res/Images/romance.png" alt="Thriller" style="height: 8rem;">
                        </div>
                        <h2>Romance</h2>
                    </div>
                    <div class="flex icon hidden_left" style="row-gap: 10px;">
                        <div class="card" style="min-width:fit-content ;">
                            <img src="res/Images/scifi.png" alt="Thriller" style="height: 8rem;">
                        </div>
                        <h2>Science Fiction</h2>
                    </div>
                    <div class="flex icon hidden_left" style="row-gap: 10px;">
                        <div class="card" style="min-width:fit-content ;">
                            <img src="res/Images/horror.png" alt="Thriller" style="height: 8rem;">
                        </div>
                        <h2>Horror</h2>
                    </div>
                </div>
                <h1>...</h1>
            </section>

        </div>
    
<!--Books-->
        <section class="flex" style="row-gap: 80px; width: 100%;">
            <div class="flex hidden_right" style="width: 100%;">
                <div class="vl"></div>
                <hr style="width: 50%; margin-left:auto;">
            </div>
            <h2 class="hidden_right" style=" text-shadow: 0 0 20px black; text-align: center; color:antiquewhite; font-size:40px;">
                | &#8226; Browse a Large Collection of Books with over <?php echo $number_books; ?> Books Ready to Sell and Issue. |
            </h2>
            <section class="flex hidden_right" style=" flex-direction: row; width: 100%;">
                <img src="res/Images/book_page.jpg" alt="books" style="margin-left: 100px; border: 5px solid grey; border-radius: 10px; width: 60%;">
                <hr>
            </section>
        </section>
<!--Issues-->
        <section class="flex" style="row-gap: 80px; width: 100%;">
            <div class="flex hidden_left" style="width: 100%;">
                <div class="vl"></div>
                <hr style="width: 50%; margin-right:auto;">
            </div>
            <h2 class="hidden_left" style=" text-shadow: 0 0 20px black; text-align: center; color:antiquewhite; font-size:40px;">
                | &#8226; Check Your Issues, Your Bills and Manage Your Returns. |
            </h2>
            <section class="flex hidden_left" style=" flex-direction: row; width: 100%;">
                <hr>
                <img src="res/Images/issues.png" alt="issues" style="margin-right: 100px; border: 5px solid grey; border-radius: 10px; width: 60%;">
            </section>
        </section>
<!--Authors-->
    <div class="flex" style="width: 100%; position:relative;" >
        <section class="flex section_card hidden_left" style="text-align:center;position: absolute; left: 20px; width: 300px;">
            <h2 style="color: antiquewhite; border-left:3px solid antiquewhite; border-right:3px solid antiquewhite;">
                Find Your Favourite Authors
            </h2><br>
            <div    style="display:flex; flex-direction:column; width: 100%; row-gap:10px;
                            align-content: center;
                            height: 400px; overflow-y:visible; overflow-x:hidden;">
<?php
                if($authors->rowCount()>0)
                {
                    foreach($authors->fetchAll() as $author)
                    {
                        echo '<a target="blank" href="https://www.google.com/search?q='.$author['author_name'].' Author, Books">'.$author['author_name'].'</a>';
                    }
                }
?>
            </div>
            <h1>...</h1>    
        </section>
        <div class="flex hidden_left">
        <div class="vl"></div>
        <section class=" section_card" style="border: 5px solid grey;">            
            <h2 style="color:antiquewhite;">Get access to a variety of authors ...</h2>
            <div class="" style="padding:50px 40px; display:flex; flex-direction: row; column-gap: 70px;">
                <div class="flex hidden_left" style=" row-gap: 10px;">
                    <div class="card" style="min-width:fit-content ;">
                        <img src="res/Images/shakespear.png" alt="Thriller" style="height: 8rem;">
                    </div>
                    <h2>Shakespeare William</h2>
                </div>
                <div class="flex icon hidden_left" style="row-gap: 10px;">
                    <div class="card" style="min-width:fit-content ;">
                        <img src="res/Images/stephenking.png" alt="Thriller" style="height: 8rem;">
                    </div>
                    <h2>Stephen King</h2>
                </div>
                <div class="flex icon hidden_left" style="row-gap: 10px;">
                    <div class="card" style="min-width:fit-content ;">
                        <img src="res/Images/martinluther.png" alt="Thriller" style="height: 8rem;">
                    </div>
                    <h2>Martin Luther King Jr.</h2>
                </div>
            </div>
            <h1>...</h1>
        </section>
        </div>
    </div>
<!--Outro-->
        <div class="flex hidden_bot" style="width: 100%;">
            <div class="vl"></div>    
            <section class=" section_card" style="width: 70%; border: 5px solid grey;">
                <h2 style=" text-shadow: 0 0 20px black; text-align: center; color:antiquewhite; font-size:40px;">
                    | &#8226; <?php echo $fetch_settings['library_name'] ?> Library's Rules & Regulations |
                </h2>
                <ul class="flex" style="margin: 80px 0; border-right: 4px solid grey; row-gap: 40px; ">
                    <li class="flex hidden_bot " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; Members must sign up to the management system to be able to operate within the library.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; Members are allowed to concurrently borrow a maximum of <?php echo $fetch_settings['loan_peruser']; ?> books.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; Members are allocated a maximum issue duration of <?php echo $fetch_settings['loan_daylimit']; ?> days, after which members will be fined daily.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; After the given issue duration elapses, members with overdue returns will be fined <?php echo $fetch_settings['fine'].' '.$fetch_settings['currency']; ?> daily per overdue book.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; The standard weekly membership fee is: <?php echo $fetch_settings['membership_fee'].' '.$fetch_settings['currency']; ?>.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; Members are to successfully return books only after overdue bills are paid (includes return fines and membership fees).</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; Members failing to adhere to the above rules after subscribing will be banned from library ground and legally followed accordingly.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; <?php echo $fetch_settings['library_name']?> library is open 7 days of the week from <?php echo $fetch_settings['open']?> to <?php echo $fetch_settings['close']?>.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; <?php echo $fetch_settings['library_name']?> library can be found at : <?php echo $fetch_settings['library_address']?>.</p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; <?php echo $fetch_settings['library_name']?> library's phone number: <?php echo $fetch_settings['contact_number']?></p>
                        <hr style="width: 80px;">
                    </li>
                    <li class="flex hidden_bot  " style="flex-direction: row; width: 100%;">
                        <p style="font-size: 20px; border-right: 4px solid grey; padding-right: 20px;">&#8226; The above rules are not exhaustive, they're the basic rules to follow while a member of the library. Please adhere to them.</p>
                        <hr style="width: 80px;">
                    </li>
                </ul>
            </section>
            <div class="vl" style="margin-top: 0px;"></div>
            <hr width="100%">
        </div>

    </div>
</body>
</html>