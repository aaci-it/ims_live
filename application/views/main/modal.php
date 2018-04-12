<?php 

$this->load->view('bootstrap_files');

?>

<form method="POST" action="">
    Email: <input type="text" name="email"><br/>
    Pass: <input type="password" name="pass"><br/>
    <input type="submit" name="login"><br/>
</form>

<?php

validation_errors() !="" ? $this->error_modal->alert("Error",validation_errors()) : ""; // throw validation errors
$msg !="" ? $this->error_modal->alert("Success",$msg) : ""; // throw success message

?>