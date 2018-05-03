<?php
function passScreen(){
?>
<!DOCTYPE html>
<html>
<title>**Project Login**</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

<body style= "background:url(img/waves.gif);">


<div class="w3-center">
	<img src="img/wizzit_logo2.png" class=" w3-center" style="width:40%">
</div>
</div>
	<div class="w3-modal-content w3-center w3-blue" style="max-width:600px">
		    <h2>Login Screen</h2>
	</div>

<div class="w3-container w3-center">


    <div class="w3-modal-content w3-card-8 w3-animate-zoom" style="max-width:600px">
      <div class="w3-center"><br>
      </div>

      <form class="w3-container" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
	  <input type="hidden" name="submitPass" value="temp">
        <div class="w3-section">
          <label><b>Username</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Username" name="uname" required>
          <label><b>Password</b></label>
          <input class="w3-input w3-border" type="password" placeholder="Enter Password" name="pword" required>
          <button class="w3-btn-block w3-green w3-section w3-large w3-padding" type="submit" name="submitPassV" value="login"><b>Login</b></button>
        </div>
      </form>

    </div>
</div>

</body>
</html>

<?php
}

function showPscreenFail(){
?>
<!DOCTYPE html>
<html>
<title>**Project Login**</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<style>

.unknownUser {
color: RED;
size: 10em;
}
</style>
<body style= "background:url(img/waves.gif);">

<div class="w3-center">
	<img src="img/wizzit_logo2.png" class=" w3-center" style="width:40%">
</div>

<div class="w3-container w3-center">
  <b class="unknownUser">Invalid Username and or Password</b>

  <div id="id01">
    <div class="w3-modal-content w3-card-8 w3-animate-zoom" style="max-width:600px">
  
      <div class="w3-center"><br>
      </div>

      <form class="w3-container" action="<?php $_SERVER['PHP_SELF']?>" method="post">
	  <input type="hidden" name="redirect" value="temp">
        <div class="w3-section">
          <label><b>Username</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Username" name="username" required>
          <label><b>Password</b></label>
          <input class="w3-input w3-border" type="text" placeholder="Enter Password" name="password" required>
          <button class="w3-btn-block w3-green w3-section w3-padding" type="submit" name="submitPass" value="login">Login</button>
        </div>
      </form>	  
	  
    </div>
  </div>
</div>

</body>
</html>
<?php
}

function passwordPhasedApproach()
{
    global $msg;
    {
        passScreen();
    }
}