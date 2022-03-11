<?php
    include('sqlreq.php');
    session_start();
    $user = $_SESSION['user'];
    $id = $user['id'];
    $stmt = $dbh->prepare("select task,deadline from tasks where iid = :id");
    $stmt->execute([':id' => $id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $dbh->prepare("select prob,sol from pro_con where iid = :id");
    $stmt->execute([':id' => $id]);
    $procon = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $dbh->prepare("select mid from mgin where iid = :id");
    $stmt->execute([':id' => $id]);
    $mid = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(isset($_POST['submit'])){
        $data = $_POST['concern'];
        if($data === ""){
            echo '<script language="javscript">alert("Please enter a valid concern");</script>';   
        }
        else{
            $stmt = $dbh->prepare("insert into pro_con(iid,prob) values(:id,:data)");
            $stmt->execute([':id' => $id,':data' => $data]);
            echo '<script>alert("Concern Raised")</script>';
        }
    }
    if(isset($_POST['logout'])){
        header('location: http://localhost/aB%20inbev%20Project/index.html');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudTracker</title>
    <!--Bootstrap CDN-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
    crossorigin="anonymous">
    <!--jQuery CDN-->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" 
    integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="text-center">
        <div class="container">
            <form>
                <input class="btn btn-outline-primary shadow-none mt-5 mb-5" type="button" value="Profile" id="pro">
                <input class="btn btn-primary shadow-none mt-5 mb-5" type="button" value="Dashboard" id="dash">
                <input class="btn btn-outline-primary shadow-none mt-5 mb-5" type="button" value="Raise Concerns" id="con">
            </form>
        </div>
        <div id="profile" class="container fw-bold">
            <p class="lead fs-3 fw-bold">Profile</p>
            <p><?php echo 'ID: '.$user['id'] ?></p>
            <p><?php echo 'Name: '.$user['name'] ?></p>
            <p><?php echo 'Date Of Joining: '.$user['doj'] ?></p>
            <p><?php echo 'Designation: '.$user['desgn'] ?></p>
            <p><?php echo 'Manager ID: '.$mid[0]['mid']?></p>
        </div>
        <div id="dashboard" class="container fw-bold">
            <p class="lead fs-3 fw-bold">Tasks in hand</p>
            <?php foreach($tasks as $task){?>
            <p><?php echo 'Task: '.$task['task'] ?></p>
            <p><?php echo 'Deadline: '.$task['deadline'] ?></p>
            <br>
            <?php } ?>
        </div>
        <div id="procon" class="container fw-bold">
            <p class="lead fs-3 fw-bold">Previously Raised concerns will be displayed here</p>
            <?php foreach($procon as $pron){?>
            <p><?php echo 'Concern: '.$pron['prob'] ?></p>
            <p><?php echo 'Solution: '.$pron['sol'] ?></p>
            <br>
            <?php } ?>
            <form method="POST" action="intern.php">
                <p class="lead fs-3 fw-bold">Raise concerns if any</p>
                <textarea class="border-3 fw-bold" style="resize: none;" cols="100" rows="10" name="concern"></textarea><br>
                <input class="mt-3 btn btn-success shadow-none" type="submit" name="submit" value="Submit">
            </form>
        </div>
        <form method="POST" action="intern.php" class="container">
            <input class="btn btn-default btn-primary mt-5 shadow-none mb-5" type="submit" name="logout" value="Log Out">
        </form>
    </div>
    <script>
        $('#dashboard').show();
        $('#procon').hide();
        $('#profile').hide();
        $('#pro').on('click',()=>{
            $('#pro').addClass('btn-primary');
            $('#pro').removeClass('btn-outline-primary');
            $('#dash').removeClass('btn-primary');
            $('#dash').addClass('btn-outline-primary');
            $('#con').removeClass('btn-primary');
            $('#con').addClass('btn-outline-primary');
            $('#dashboard').hide();
            $('#procon').hide();
            $('#profile').show();
        });
        $('#dash').on('click',()=>{
            $('#dash').addClass('btn-primary');
            $('#dash').removeClass('btn-outline-primary');
            $('#pro').removeClass('btn-primary');
            $('#pro').addClass('btn-outline-primary');
            $('#con').removeClass('btn-primary');
            $('#con').addClass('btn-outline-primary');
            $('#dashboard').show();
            $('#procon').hide();
            $('#profile').hide();
        });
        $('#con').on('click',()=>{
            $('#con').addClass('btn-primary');
            $('#con').removeClass('btn-outline-primary');
            $('#dash').removeClass('btn-primary');
            $('#dash').addClass('btn-outline-primary');
            $('#pro').removeClass('btn-primary');
            $('#pro').addClass('btn-outline-primary');
            $('#dashboard').hide();
            $('#procon').show();
            $('#profile').hide();
        });
    </script>
</body>
</html>