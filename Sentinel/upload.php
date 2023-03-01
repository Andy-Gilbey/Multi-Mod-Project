<style>
@import url('https://fonts.googleapis.com/css2?family=Golos+Text:wght@500&family=Roboto:wght@100&display=swap');
</style> 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Antigen Upload</title>
	<link rel="stylesheet" type="text/css" href="style\style.css">
</head>
<body>
<header>
  <h1>Welcome to Sentinel</h1>

  <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="closeContacts.php">Manage Close Contacts</a>
            <a href="upload.php">Manage Uploads</a>
            <a href="includes/logout.inc.php">Logout</a>
          </nav>s
</header>


	<div class="wrapper">
    <div class="form">
            <h1><img src="images/sent-logo.png" alt="Sentinel" width="200"></h1>
            <b><p> Welcome to the Sentinel Portal</p></b>

            <form action="includes/upload.inc.php" method="post" enctype="multipart/form-data">
            <label for="antigen">Antigen:</label>
                
            <input type="file" id="antigen" name="antigen" onchange="previewImage(event);">
            <br>
            <label for="antigen">Image Preview:</label>
            <img id="preview" alt="Antigen Preview will be displayed here" width="423px" height="237px" style="display: inline-block"><br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date">
            <div class=smallText> 
            <i>By uploading this image, you confirm that you possess the rights to share it, and give your consent for its 
                use in accordance with the provisions of the General Data Protection Regulation (GDPR). 
                If you do not have permission to distribute this image, you may face legal repercussions, 
                which includes penalties. This image should only display your own test.</i>
</div>
            <button type="submit" name="submit">Upload</button>
        </form>
        <h1>Current Antigens</h1>
		<table>
			<thead>
				<tr>
					<th>Antigen Photo</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php

                session_start();
                $user_ID = $_SESSION['user_ID'];
                
					require_once 'includes/db.inc.php';

                    $user_ID = $_SESSION['user_ID'];
                    $userID = $user_ID; // Define $userID variable

					$sql = "SELECT * FROM Antigens WHERE FK_UserID = $userID";
					$stmt = mysqli_stmt_init($conn);

					if (!mysqli_stmt_prepare($stmt, $sql)) {
						header("location: ../upload.php?error=stmtfailed");
						exit();
					}



					$result = mysqli_query($conn, $sql);

					while ($row = mysqli_fetch_assoc($result)) {
						echo "<tr>";
						echo "<td><img src='uploads/" . $row['AG_Image'] . "' alt='Antigen Preview' width='200'></td>";
						echo "<td>" . $row['DateOfTest'] . "</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
    </div>
        </div>
    </div>
	<footer>
		<div class="footer">
            <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
        </div>
	</footer>


	<script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
