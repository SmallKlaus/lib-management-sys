
<?php

include '../res/assets/globalfunctions.php';

if(!isadminlogged())
{
    header('location:../admin_login.php');
}

$success_message = '';

if(isset($_POST['apply']))
{
    $data = array(
        ':library_name' => $_POST['library_name'],
        ':building_number' =>$_POST['building_number'],
        ':library_address' => $_POST['library_address'],
        ':contact_number' => $_POST['contact_number'],
        ':email_contact' => $_POST['email_contact'],
        ':membership_fee' => $_POST['membership_fee'],
        ':loan_peruser' => $_POST['loan_peruser'],
        ':loan_daylimit' => $_POST['loan_daylimit'],
        ':fine' => $_POST['fine'],
        ':currency' => $_POST['currency'],
        ':open' => $_POST['open'],
        ':close' => $_POST['close'],
    );

    $query = "UPDATE admin_settings
                SET library_name = :library_name,
                building_number = :building_number,
                library_address = :library_address,
                contact_number = :contact_number,
                email_contact = :email_contact,
                membership_fee = :membership_fee,
                loan_peruser = :loan_peruser,
                loan_daylimit = :loan_daylimit,
                fine = :fine,
                currency = :currency,
                open = :open,
                close = :close";
    
    $stat = $connect->prepare($query);
    $stat->execute($data);
    $success_message = '<li>Changes applied successfully.'.date("h:i").'&#9;<a onclick="closemessage()" style="opacity: 100%;cursor:pointer">&#10006;</a></li>';

}


$query = "SELECT * FROM admin_settings
            LIMIT 1";

$row = $connect->query($query);

include 'navcomp.php';
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings . LibraryMS</title>
</head>
<body>
    <div class="content">
        <div class="head_title">
            <img src="../res/Icons/setting.png" alt="Settings" style="height: 4rem ;">
            | Settings
            
        </div>
        <form method="POST" class="card" style="width: 70%;">
            <?php 
                foreach($row as $info)
                {
            ?>
            <div>
                <label for="library_name" style="margin-right: auto;">Official Library name : </label>
                <input type="text" name="library_name" id="library_name" value="<?php echo $info['library_name']; ?>">
            </div>    
            <div>
                <label for="building_number" style="margin-right: auto;">Building number : </label>
                <input type="text" name="building_number" id="building_number" value="<?php echo $info['building_number']; ?>">
            </div>    
            <div>
                <label for="library_address" style="margin-right: auto;">Library address : </label>
                <input type="text" name="library_address" id="library_address" value="<?php echo $info['library_address']; ?>">
            </div>    
            <div>
                <label for="contact_number" style="margin-right: auto;">Phone number : </label>
                <input type="text" name="contact_number" id="contact_number" value="<?php echo $info['contact_number']; ?>">
            </div>    
            <div>
                <label for="email_contact" style="margin-right: auto;">Email address : </label>
                <input type="text" name="email_contact" id="email_contact" value="<?php echo $info['email_contact']; ?>">
            </div>    
            <div>
                <label for="membership_fee" style="margin-right: auto;">Weekly membership fee : </label>
                <input type="number" name="membership_fee" id="membership_fee" value="<?php echo $info['membership_fee']; ?>">
            </div>    
            <div>
                <label for="loan_peruser" style="margin-right: auto;">Loaned books limit per user : </label>
                <input type="number" name="loan_peruser" id="loan_peruser" value="<?php echo $info['loan_peruser']; ?>">
            </div>    
            <div>
                <label for="loan_daylimit" style="margin-right: auto;">Loan day limit : </label>
                <input type="number" name="loan_daylimit" id="loan_daylimit" value="<?php echo $info['loan_daylimit']; ?>">
            </div>    
            <div>
                <label for="fine" style="margin-right: auto;">Daily late return fee : </label>
                <input type="number" name="fine" id="fine" value="<?php echo $info['fine']; ?>">
            </div>    
            <div>
                <label for="currency" style="margin-right: auto;">Accepted currency : </label>
                <select type="number" name="currency" id="currency">
                    <option value="">Choose currency</option>
                    <option value="Dollars">Dollars</option>
                    <option value="Euros">Euros</option>
                    <option value="Pounds">Pounds</option>
                </select>
            </div>    
            <section style="display: flex;flex-direction: row; align-items:center; column-gap: 20px; justify-content: flex-start; width:100%; ">
                <div>
                    <label for="open" style="margin-right: auto;">Open at : </label>
                    <input type="time" style="width: fit-content ;" name="open" id="open" value="<?php $date = date("H:i", strtotime($info['open']));echo $date; ?>">
                </div>
                <div>
                    <label for="close" style="margin-right: auto;">Closes at : </label>
                    <input type="time" style="width: fit-content ;" name="close" id="close" value="<?php $date = date("H:i", strtotime($info['close']));echo $date; ?>">
                </div>
                <input type="submit" name="apply" value="Apply" style=" width: fit-content;">
            </section>    
            <?php
                }
            ?>   
        </form>
        <?php
            if($success_message != '')
            {
                echo '<div id= "succ" class="messageBox success" style=" margin-top: 20px;"><ul>'.$success_message.'</ul></div>';
            }
            ?>
    </div>

    <script>
        document.getElementById("currency").value="<?php echo $info['currency']; ?>"
    </script>
</body>
</html>