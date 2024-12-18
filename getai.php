<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's ask AI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
    <?php include "partials/_dbconnect.php"; ?>

    <div class="container my-5">
        <div class="jumbotron">
            <?php
                $id = $_GET['threadid'];
                $sql = "SELECT * FROM `threads` WHERE thread_id=$id"; 
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $title = $row['thread_title'];
                }

                $sql = "SELECT * FROM `comments` WHERE thread_id = $id"; 
                $result = mysqli_query($conn, $sql);
                $noResult=true;
                $comcontent = "";
                while($row = mysqli_fetch_assoc($result)){
                    $noResult=false;
                    $content = $row['comment_content'];
                    $comcontent .= $content.". ";
                }

            ?>
            <h1>Let's ask our AI</h1>
            <p class="lead">Get instant answers from AI! Just click the button above to unlock the power of artificial intelligence and discover insightful responses to your queries.</p>
            <p class="lead">Thread: <?php echo $title?></p>
            <form method="post" action="">
                
                <input type="text" name="username" placeholder="Enter your name" value="<?php echo $title."You can take reference of following content & give all answers: ".$comcontent?>" required style="display:none">
                <button class="btn btn-lg btn-success" type="submit">Generate »</button>
            </form>
            <h2 class="my-5">Response:</h2>
            <div id="response"></div>
        </div>
    </div>

    <!-- Add a div element to display the response -->

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
        // Retrieve user input from form
        $username = $_POST["username"];

        // Encode user input for JavaScript
        $encodedUsername = json_encode($username);
    ?>
    <script type="importmap">
        {
        "imports": {
          "@google/generative-ai": "https://esm.run/@google/generative-ai"
        }
      }
    </script>
    <script type="module">
    // Import GoogleGenerativeAI class from the module
    import {
        GoogleGenerativeAI
    } from "@google/generative-ai";

    // Get user input passed from PHP
    var username = <?php echo json_encode($username); ?>;

    // Initialize GoogleGenerativeAI with your API key
    const genAI = new GoogleGenerativeAI("AIzaSyCetBxNpYl-jORv9BeukN1bwDRXbUSSPJI");
    // Function to generate response using the API
    async function run() {
        try {
            // Get the generative model for text generation
            const model = genAI.getGenerativeModel({
                model: "gemini-pro"
            });

            // Generate response based on user input
            const result = await model.generateContent(username);
            const response = await result.response;

            // Process the response
            const text = response.text();

            // Update the content of the div element with the response text
            document.getElementById("response").innerText = text;
        } catch (error) {
            console.error("Error:", error);
        }
    }

    // Call the run function to generate the response
    run();
    </script>

    <?php
    }

    // Check if the response is received from JavaScript
    if (isset($_POST["response"])) {
        // Display the response sent from JavaScript
        echo "<h2>Response:</h2>";
        echo "<pre>{$_POST['response']}</pre>";
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
</body>

</html>