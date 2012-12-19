

<html>


<head>
<title>Login form</title>

<script src="jquery.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	$("#login").click(function() {
	
		var action = $("#form1").attr('action');
		var form_data = {
			username: $("#username").val(),
			password: $("#password").val(),
			cookie:   $("#rem").prop('checked') ? 1 : 0,
			is_ajax: 1
		};
		
		$.ajax({
			type: "POST",
			url: action,
			data: form_data,
			success: function(response)
			{
			   $("#message").html("<p class='success'>" + response + "</p>");
			}
		});
		
		//window.location.replace('welcome.php');
		return false;
	});
	
});
</script>


</head>



<body>
<div id="content">
  <h1>Login Form</h1>
  <form id="form1" name="form1" action="doLogin.php" method="post">
    <p>
      <label for="username">Username: </label>
      <input type="text" name="username" id="username" />
    </p>
    <p>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" />
    </p>
		<p>
	<input type="checkbox" id="rem" name="rem" />
	</p>
    <p>
      <input type="submit" id="login" name="login" />
    </p>

  </form>
    <div id="message"></div>
</div>

<a href="Welcome.php">Welcome!</a> 


</body>

</html>