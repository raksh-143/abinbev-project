<?php
    include('sqlreq.php');
    session_start();
    $user = $_SESSION['user'];
    $id = $user['id'];

    $stmt = $dbh->prepare("select iid,task,deadline from tasks");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $val = null;

    $stmt = $dbh->prepare("select iid,prob from pro_con where sol is :no");
    $stmt->execute([':no'=>$val]);
    $procon = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $dbh->prepare("select iid from mgin where mid = :id");
    $stmt->execute([':id' => $id]);
    $interns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Add Solution
    if(isset($_POST['add'])){
        if($_POST['con1'] == ""){
            echo '<script>alert("Please enter a solution");</script>';
        }
        else{
            $stmt = $dbh->prepare("update pro_con set sol=:solution where prob=:prob");
            $stmt->execute([':solution'=>$_POST['sol'],':prob'=>$_POST['con1']]);
            $procon = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    //Removing Tasks
    if(isset($_POST['remove'])){
        $stmt = $dbh->prepare("delete from tasks where task=:task");
        $stmt->execute([':task'=>$_POST['task1']]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Log Out
    if(isset($_POST['logout'])){
        header('location: http://localhost/aB%20inbev%20Project/index.html');
    }

    //Assigning Tasks
    if(isset($_POST['submit'])){
        $stmt = $dbh->prepare("insert into tasks values(:id,:task,:dl)");
        $stmt->execute([':id'=>$_POST['intern'],':task'=>$_POST['task'],':dl'=>$_POST['date']]);
        //$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <input class="btn btn-outline-primary shadow-none mt-5 mb-5" type="button" value="Concerns" id="con">
            </form>
        </div>
        <div id="profile" class="container fw-bold">
            <p class="lead fs-3 fw-bold">Profile</p>
            <p><?php echo 'ID: '.$user['id'] ?></p>
            <p><?php echo 'Name: '.$user['name'] ?></p>
            <p><?php echo 'Date Of Joining: '.$user['doj'] ?></p>
            <p><?php echo 'Designation: '.$user['desgn'] ?></p>
            <p class="lead fs-3 fw-bold">Interns Assigned:</p>
            <?php foreach ($interns as $intern){ ?>
            <p><?php echo 'Intern ID: '.$intern['iid']?></p>
            <?php } ?>
        </div>
        <div id="dashboard" class="container fw-bold">
            <p class="lead fs-3 fw-bold">Previously Assigned Tasks will be displayed here</p>
            <form method="POST" action="manager.php">
                <?php foreach($tasks as $task){?>
                <p><?php echo 'Intern ID: '.$task['iid'] ?></p>
                <input class="border-0 fw-bold text-center mb-3" type="text" value="<?php echo $task['task'] ?>" name="task1" readonly>
                <p><?php echo 'Deadline: '.$task['deadline'] ?></p>
                <input class="mb-3 btn btn-success shadow-none btns" type="submit" value="Task Completed" name="remove">
            </form>
            <br>
            <?php } ?>
            <p class="lead fs-3 fw-bold">Assign Tasks to Interns</p>
            <form method="POST" action="manager.php">
                <select name="intern" class="fw-bold p-2">
                    <option disabled selected>Select intern</option>
                    <?php foreach($interns as $intern){?>
                    <option><?php echo $intern['iid']?></option>
                    <?php } ?>
                </select><br/>
                <p class="fs-4 mt-3">Task</p>
                <textarea class="border-3 fw-bold" style="resize: none;" cols="100" rows="10" name="task"></textarea><br>                
                <p class="fs-4 mt-3">Deadline</p>
                <input class="fw-bold p-2" type="date" name='date'><br>
                <input class="mt-3 btn btn-success shadow-none btns" type="submit" name="submit" value="Submit">
            </form>
        </div>
        <div id="procon" class="container fw-bold">
            <p class="lead fs-3 fw-bold">Previous concerns will be displayed here</p>
            <?php foreach($procon as $pron){?>
            <form method="POST" action="manager.php">
                <p class="fs-5"><?php echo 'Raised By '.$pron['iid'] ?></p>
                <input class="border-0 fw-bold text-center mb-3 fs-4" type="text" value="<?php echo $pron['prob'] ?>" name="con1" readonly>
                <textarea class="border-3 fw-bold" style="resize: none;" cols="100" rows="10" name="sol"></textarea><br>                
                <input class="mb-3 btn btn-success shadow-none mt-3 btns" type="submit" value="Add Solution" name="add">
            </form>
            <br>
            <?php } ?>
        </div>
        <form method="POST" action="manager.php" class="container">
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
        $('.btns').on('click',setInterval(
            $('manager.php').load()
        ));
    </script>
</body>
</html>