<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="mystyle.css?v=<?php echo time(); ?>">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ThinkBox</title>
</head>
<body>


<div class="main-wrapper">
    <div class="fade-in-text">
        <div id="TitleLarge">ThinkBox</div>
        <div><button class="LoginButton Login"><p class="hover-underline-animation">Login</p></button></div>
        <div id="LoginWindowDiv">
        <form id="loginForm">
            <div class = "HiddenLoginDiv">
            <label for="uname"><i>Username</i></label>
            <input type="text" spellcheck="false" name="uname" required>
            <label id="PasswordLabel" for="psw"><i>Password</i></label>
            <input id="PasswordInput" spellcheck="false" type="password" name="psw" required>
            <p id="message"></p>
            <button id="LoginButtonOK"><img src="images/triangle.png" alt="" type=submit></button>
            </div>
        </form>
        </div>
        <div><button class="LoginButton Register"><p class="hover-underline-animation">Register</p></button></div>
        <div id="RegisterWindowDiv">
        <form id="registerForm">
            <div class = "HiddenLoginDiv">
                <label for="uname"><i>Username</i></label>
                <input type="text" spellcheck="false" name="uname" required>
                <label id="PasswordLabel" for="psw"><i>Password</i></label>
                <input id ="PasswordInput" spellcheck="false" type="password" name="psw" required>
                <p id="registerMessage"></p>
                <button id="RegisterButtonOK"><img src="images/triangle.png" alt=""></button>
            </div>
        </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="myscript.js?v=<?php echo time(); ?>"></script>
<script>  
$("#loginForm").submit(function(event) {
      event.preventDefault();
      $.ajax({
          type: "POST",
          url: "authenticate.php",
          data: $(this).serialize(),
          dataType: "json",
          success: function(response) {
              if (response.success) {
                SuccesfulLoginAnimation();
              } else {
                $("#message").html(response.message);
                setTimeout(() => {
                    $("#message").html("");;
                }, 2500);
                  
              }
          }
      });
  });
  </script>
  <script>
$("#registerForm").submit(function(event) {
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "register.php",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            const msgElement = $("#registerMessage");
            msgElement.html(response.message);
            msgElement.css("color", response.success ? "limegreen" : "red");

            if (response.success) {
                // Optionally clear form
                $("#registerForm")[0].reset();
            }

            setTimeout(() => {
                msgElement.html("");
            }, 3000);
        },
        error: function(xhr, status, error) {
            $("#registerMessage").html("Unexpected error occurred.").css("color", "red");

            setTimeout(() => {
                $("#registerMessage").html("");
            }, 3000);
        }
    });
});
</script>
</body>
</html>