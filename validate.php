<?php 
    include('sqlreq.php');
    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $pword = $_POST['pword'];
        $stmt = $dbh->query('select * from members');
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $flag = 0;
        foreach($members as $member){
            if($member['id'] == $id){
                if($member['pword'] == $pword){
                    if($member['desgn'] == "Intern"){
                        session_start();
                        $_SESSION['user'] = $member;
                        header("location: http://localhost/aB%20inbev%20Project/intern.php");
                    }
                    else{
                        session_start();
                        $_SESSION['user'] = $member;
                        header("location: http://localhost/aB%20inbev%20Project/manager.php");
                    }
                    $flag = 1;
                }
                else{
                    echo '<script>alert("Login Failed")</script>';
                    $flag = 1;
                }
            }
        }
        if($flag != 1){
            echo '<script>alert("Login Failed")</script>';
        }
    }
?>