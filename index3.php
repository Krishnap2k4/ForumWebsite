<!DOCTYPE html>
<html>
<head>
    <title>ChatGPT Query</title>
</head>
<body>
    <h2>Ask ChatGPT</h2>
    <form method="post">
        <label for="query">Your Query:</label><br>
        <input type="text" id="query" name="query" required><br>
        <input type="submit" value="Ask">
    </form>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get query from the form
        $query = $_POST['query'];

        // ChatGPT API endpoint and your API key
        $endpoint = 'https://api.openai.com/v1/completions';
        $api_key = 'YOUR_API_KEY'; // Replace with your actual API key

        // Data to be sent in the request
        $data = array(
            'prompt' => $query,
            'max_tokens' => 150,
            'temperature' => 0.7
        );

        // Headers for the request
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        );

        // Initialize cURL session
        $ch = curl_init($endpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Decode the response
            $json_response = json_decode($response, true);

            // Output the generated text
            echo '<p>Response:</p>';
            echo '<p>' . $json_response['choices'][0]['text'] . '</p>';
        }

        // Close cURL session
        curl_close($ch);
    }
    ?>
</body>
</html>
