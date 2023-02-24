<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sign Up</title>
	<link rel="stylesheet" type="text/css" href="style\style.css">
</head>
<body>
	<header>

        <center><h1>Sign-up Form</h1></center>
    </div>
	</header>
	<div class="container"  >
		<div class="form">
            <img src="images/sent-logo.png" alt="Sentinel" width="200">
			<h2>Registration</h2>
      <center>
      <i>Please ensure you read the <a href="terms.html" target="_blank">terms and conditions</a> before submiting this form</i>
      </center>
			<form method="post" action="includes/signup.inc.php">

				<label for="username">Username:</label> 
        <!-- Max length for username = 30, there is no encryption so no ammends need to be taken -->
				<input type="text" id="username" name="username" placeholder="Enter your username"  maxlength="30" required>

				<label for="firstname">First Name:</label>
				<input type="text" id="firstname" name="firstname" placeholder="Enter your first name" maxlength="50" required>

				<label for="lastname">Last Name:</label>
				<input type="text" id="lastname" name="lastname" placeholder="Enter your last name" maxlength="50" required>

        <label for="dob">Date of Birth:</label>
				<input type="date" id="dob" name="dob" required>


				<label for="address">Address Line 1:</label>
				<input type="text" id="address1" name="address1" placeholder="Enter your House Number" maxlength="50" required>
                <label for="address">Address Line 2:</label>
				<input type="text" id="address2" name="address2" placeholder="Enter your Street Address" maxlength="50" required>

                <label for="county">County:</label>
                <select name="county" id="county">
                    <option value="Carlow">Carlow</option>
                    <option value="Cavan">Cavan</option>
                    <option value="Clare">Clare</option>
                    <option value="Cork">Cork</option>
                    <option value="Donegal">Donegal</option>
                    <option value="Dublin">Dublin</option>
                    <option value="Galway">Galway</option>
                    <option value="Kerry">Kerry</option>
                    <option value="Kildare">Kildare</option>
                    <option value="Kilkenny">Kilkenny</option>
                    <option value="Laois">Laois</option>
                    <option value="Leitrim">Leitrim</option>
                    <option value="Limerick">Limerick</option>
                    <option value="Longford">Longford</option>
                    <option value="Louth">Louth</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Meath">Meath</option>
                    <option value="Monaghan">Monaghan</option>
                    <option value="Offaly">Offaly</option>
                    <option value="Roscommon">Roscommon</option>
                    <option value="Sligo">Sligo</option>
                    <option value="Tipperary">Tipperary</option>
                    <option value="Waterford">Waterford</option>
                    <option value="Westmeath">Westmeath</option>
                    <option value="Wexford">Wexford</option>
                    <option value="Wicklow">Wicklow</option>
                </select>


                <label for="address">Eircode:</label>
				<input type="text" id="eircode" name="eircode" placeholder="Enter Eircode" maxlength="9" required>
                <label for="phone">Phone:</label>
				<input type="text" id="phone" name="phone" placeholder="Enter Phone Number" maxlength="20" required>

                <div class="password-rules">
  <ul>
    <li>Passwords should be a minimum of 8 characters long.</li>
    <li>Passwords must contain at least one uppercase and lowercase letter, one number, and one special character e.g (!,@,<,>).</li>
  </ul>
</div>

				<label for="password">Password:</label>
				<input type="password" id="password" name="password" placeholder="Enter your password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
				<label for="confirm-password">Confirm Password:</label>
				<input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
				<div class="button-container">
                <div class="terms-and-conditions">
    <input type="checkbox" id="agree-checkbox">
    <label for="agree-checkbox">I agree to the <a href="terms.html" target="_blank">terms and conditions</a></label>
  </div>
					<button type="submit" id="submit-button" disabled>Submit</button>
                    <a href="index.html">	<button type="button">Quit</button></a>
				</div>
			</form>
		</div>
	</div>
	<footer>
		<div class="footer">
            <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
        </div>
	</footer>
    <script>
        //Check Password Function for User Validation
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirm-password");
      
        function validatePassword() {
          if (password.value != confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match");
          } else {
            confirmPassword.setCustomValidity("");
          }
        }
      
        password.addEventListener("change", validatePassword);
        confirmPassword.addEventListener("keyup", validatePassword);
      </script>
      <script>
  const agreeCheckbox = document.querySelector('#agree-checkbox');
  const submitButton = document.querySelector('#submit-button');
  
  agreeCheckbox.addEventListener('change', () => {
    submitButton.disabled = !agreeCheckbox.checked;
  });
</script>
</body>
</html>
