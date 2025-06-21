<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="login.css">
  <title>Login Page</title>
</head>

<body>
  <section>
    <div class="container">
      <div class="login-container">
        <h2>Welcome. Please enter your username and password.</h2>
        <form action="findlogin.php" method="POST">
          <table>
            <tr>
              <th>Username:</th>
              <td><input type="text" name="username" required></td>
            </tr>
            <tr>
              <th>Password:</th>
              <td><input type="password" name="password" required></td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="submit" value="Submit" name="submit">
              </td>
            </tr>
          </table>
        </form>
        <p class="center"><b>New user? <a href="testoothease.php">Sign-up now..</a></b></p>
      </div>
    </div>
  </section>
</body>
</html>
