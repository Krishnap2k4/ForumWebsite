<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to iDiscuss - Coding Forums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <style>
    #qures {
        min-height: 433px;
    }
    </style>
</head>

<body>
    <?php include "partials/_dbconnect.php"; ?>
    <?php include "partials/_header.php"; ?>

    <?php
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` WHERE thread_id=$id"; 
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_user_id = $row['thread_user_id'];

        $sql2 = "SELECT user_email FROM `users` WHERE sno ='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $posted_by= $row2['user_email'];

    }
    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST'){
            $comment = $_POST['comment'];
            $comment = str_replace("<", "&lt", $comment); //XSS attack
            $comment = str_replace(">", "&gt", $comment); //
            $sno = $_POST['sno'];
            $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp())"; 
            $result = mysqli_query($conn, $sql);
            $showAlert = true;
            if($showAlert){
                echo'
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your Comment has been added successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
            }
        }
    ?>

    <!-- Category container starts here -->
    <div class="container my-4">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold"><?php echo $title;?></h1>
                <p class="col-md-8 fs-4"> <?php echo $desc;?></p>
                <hr class="my-4">
                <p>This is a peer to peer forum. No Spam / Advertising / Self-promote in the forums is not allowed. Do
                    not
                    post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross
                    post
                    questions. Remain respectful of other members at all times.</p>
                <a>Posted by:<b> <?php echo $posted_by; ?></b></a>
            </div>
            <a href="<?php echo "./getai.php?threadid=" . $id ?>">
                <button class="btn btn-outline-success my-0">Let's Ask AI</button>
            </a>
        </div>
    </div>

    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        echo '<div class="container my-5">
        <h1>Post a Comment</h1>
        <form class="my-4" action="'. $_SERVER['REQUEST_URI'] .'" method="post">
            <div class="form-group mb-3">
                <label for="exampleInputEmail1" class="form-label">Type your Comment</label>
                <textarea class="form-control" id="comment" name="comment" style="height: 100px"></textarea>
                <input type="hidden" name="sno" value="'.$_SESSION['sno'].'">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>';
    }
    else{
        echo '<div class="container">
                <h1>Post a Comment</h1>
                <p class="lead">You are Not Logged in, Please Login to be able to Post a Comment.</p>
            </div>';
    }
    ?>

    <div class="container my-5" id="qures">
        <h1>Discussions</h1>

        <?php
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `comments` WHERE thread_id = $id"; 
    $result = mysqli_query($conn, $sql);
    $noResult=true;
    while($row = mysqli_fetch_assoc($result)){
        $noResult=false;
        $id = $row['comment_id'];
        $content = $row['comment_content'];
        $comment_time = $row['comment_time'];
        $thread_user_id = $row['comment_by'];

        $sql2 = "SELECT user_email FROM `users` WHERE sno ='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);


       echo' <div class="d-flex my-5">
            <div class="flex-shrink-0">
                <img src="img/userdefault.jpg" width="54px" alt="...">
            </div>
            <div class="flex-grow-1 ms-3">
                <p class="my-0"><b>Answered by: '. $row2['user_email'].' at '.$comment_time.'</b></p>
                <p>'.$content.'</p>
            </div>
        </div>';
    }
    if($noResult){
        echo '
        <div class="container my-5">
    <div class="flex-grow-1 ms-3">
    <div class="h-100 p-5 bg-light border rounded-3">
    <p class="display-4">No Comments found</p>
    <p>Be the first person to Comment</p>
    </div>
    </div>
    </div>
    ';
}
    ?>
    </div>


    <?php include "partials/_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>
</body>

</html>