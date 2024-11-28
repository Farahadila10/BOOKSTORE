<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all contact records
$sql = "SELECT * FROM contact"; // Replace 'contact' with your actual table name
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Records</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your custom CSS -->
    <style>
        /* Global styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        /* Container for the page content */
        .container {
            width: 80%;
            max-width: 1200px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border-top: 5px solid #28a745; /* Changed blue to green */
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        th {
            background-color: #28a745; /* Changed blue to green */
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        tr:hover td {
            background-color: #e2e2e2;
        }

        /* Link styles */
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745; /* Changed blue to green */
            color: white;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #218838; /* Darker green for hover */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 20px;
            }

            table, th, td {
                font-size: 14px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Customer Contact Records</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Contact ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["contactID"] . "</td>";
                        echo "<td>" . $row["contactName"] . "</td>";
                        echo "<td>" . $row["contactEmail"] . "</td>";
                        echo "<td>" . $row["contactPhone"] . "</td>";
                        echo "<td>" . $row["comments"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No contact records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="adminPage.php">Back to Admin Dashboard</a>
    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
