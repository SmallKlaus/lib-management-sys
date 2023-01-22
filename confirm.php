<?php
    include 'res/assets/globalfunctions.php';

    if(isset($_GET['code']))
    {
        $data = array (
            ':confirmation_code' => trim($_GET['code'])
        );

        $query = "SELECT confirmation_status FROM user_accounts WHERE confirmation_code = :confirmation_code";
        $stat = $connect->prepare($query);
        $stat->execute($data);
        if($stat->rowCount() > 0)
        {

            foreach($stat->fetchAll() as $row)
            {
                if($row['confirmation_status'] == 'N')
                {
                    $status = 'Y';
                    $connect->query("UPDATE user_accounts SET confirmation_status = '".$status."' WHERE confirmation_code = '".$data[':confirmation_code']."'");

                    header("location:user_login.php?msg=success");
                }
            }
        }
    }
?>