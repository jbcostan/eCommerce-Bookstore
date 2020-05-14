<?php

session_start();
$currentuser = $_SESSION["username"];

if($currentuser == "Guest" || $currentuser == "" || $currentuser == NULL){
  echo "<p>Signed in as: Guest</p>";
  echo "<h5 align='center'>Access Denied: Please sign in/register to access this page.</h5>";
  echo "<p align='center'>Already have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/4150-login/'>Sign in</a>.</p>";
  echo "<p align='center'>Don't have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/registration/'>Register</a>.</p>";
}
else{
  echo "<p>Signed in as: " . $currentuser . "</p><br>";

  echo "<h5 align='center'>Need to change your password? Fill out the form below ⬇︎</h5>";

  echo "<form method='post' action='http://www.costanj.myweb.cs.uwindsor.ca/password-change/'>
    <div class='container'>
      <p>Please enter your current password and your new password.</p>
      <hr>

      <label for='currentpwd'><b>Current Password</b></label>
      <input type='password' placeholder='Enter Current Password' name='currentpwd' required=''>

      <label for='newpwd'><b>New Password</b></label>
      <input type='password' placeholder='Enter a New Password' name='newpwd' required=''>

      <label for='newpwd2'><b>New Password (Repeat)</b></label>
      <input type='password' placeholder='Enter a New Password' name='newpwd2' required=''>

      <button type='submit' class='registerbtn'>Change Password</button>
    </div>

  </form>";




}

?>
