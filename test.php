<?php 
define("PEAR_PATH","" );
ini_set('include_path', PEAR_PATH);
	require_once "Mail.php";

        $from = "<from.gmail.com>";
        $to = "<to.yahoo.com>";
        $subject = "Hi!";
        $body = "Hi,\n\nHow are you?";

        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "lincolnnus@gmail.com";  //<> give errors
        $password = "33455432";

        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject);
        $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
          echo("<p>" . $mail->getMessage() . "</p>");
         } else {
          echo("<p>Message successfully sent!</p>");
         }

    ?> 
