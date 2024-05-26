<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
        }
        .advance-searchbar{
            display: flex;
            min-height: 80vh;
            
            align-items: center;
            justify-content: center;
        }

        .advance-searchbar input[type=text]{
            width: 50%;
            height: 40px;
            text-align: center;
            margin-right: 10px;
            border-radius: 5px;
            color: #fff;
            background-color: #333;
        }
        .advance-searchbar input[type=submit]{
            height: 45px;
            text-align: center;
            background-color: #4CAF50;
            border-radius: 5px;
        }
        .advance-searchbar input[type=submit]:hover{
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="advance-searchbar">
        <input type="text" placeholder="Search any song" required>
        <input type="submit" value="Search">
    </div>
    <div class="songlist-container">
        <table>
            <tr>
                <th>Name</th>
                <th>Artist</th>
            </tr>
        </table>
    </div>
</body>
</html>