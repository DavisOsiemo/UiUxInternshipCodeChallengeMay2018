<?php
// Be sure to include the file you've just downloaded
require_once('AfricasTalkingGateway.php');
require ('dbConnector.php');
// Specify your authentication credentials
$username   = "sandbox";
$apikey     = "4af6b04901fb133e4c07ac6a1d98d66ecd5ea5163e7b5c8db4ffbf87eeaaf4fd";
// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)

//A message is sent to the phone number supplied on the form
$recipients = '';
if (isset($_POST['phonenumber']) && !empty($_POST['phonenumber'])) {
  $recipients = $_POST['phonenumber'];
}

//Just like the phone number, a post request is made to the form so as to retrieve the message to be sent
$message = '';
if (isset($_POST['message']) && !empty($_POST['message'])) {
  $message = $_POST['message'];
}

//Do the same for all other fields
if (isset($_POST['email']) && !empty($_POST['email'])) {
  $email = $_POST['email'];
}
if (isset($_POST['password']) && !empty($_POST['password'])) {
  $password = $_POST['password'];
}
if (isset($_POST['name']) && !empty($_POST['name'])) {
  $name = $_POST['name'];
}



// Create a new instance of our awesome gateway class
$gateway    = new AfricasTalkingGateway($username, $apikey);
/*************************************************************************************
  NOTE: If connecting to the sandbox:
  1. Use "sandbox" as the username
  2. Use the apiKey generated from your sandbox application
     https://account.africastalking.com/apps/sandbox/settings/key
  3. Add the "sandbox" flag to the constructor
  $gateway  = new AfricasTalkingGateway($username, $apiKey, "sandbox");
**************************************************************************************/
// Any gateway error will be captured by our custom Exception class below, 
// so wrap the call in a try-catch block
try 
{ 
  // Thats it, hit send and we'll take care of the rest. 
  $results = $gateway->sendMessage($recipients, $message);
            
  foreach($results as $result) {
    // status is either "Success" or "error message"
    echo "Information captured successfully to our DB".'</br>';
    echo " Number: " .$result->number.'</br>';
    echo " Status: " .$result->status.'</br>';
    echo " MessageId: " .$result->messageId.'</br>';
    echo " Cost: "   .$result->cost."\n";
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Oops! We encountered an error sending your message ".$e->getMessage();
}

// define variables and set to empty values
$nameErr = $emailErr = $passErr= "";
$name = $email = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $sql1 = "INSERT INTO `names`(`name`) VALUES ($name)";
    mysqli_query($db, $sql1);
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $sql2 = "INSERT INTO `emails`(`email`) VALUES ($email)";
    mysqli_query($db, $sql2);
  }

  if (empty($_POST["password"])) {
    $passErr = " Password required";
  } else {
    $sql3 = "INSERT INTO `passwords`(`password`) VALUES ($password)";
    mysqli_query($db, $sql3);
  }

  if (empty($_POST["message"])) {
    $msgErr = "Required field";
  } else {
    $sql4 = "INSERT INTO `messages`(`message`) VALUES ($message)";
    mysqli_query($db, $sql4);
  }
    if (empty($_POST["phonenumber"])){
    $phoneErr = "Required field";
  } else{
    $sql = "INSERT INTO `phonenumbers`( `phonenumber`) VALUES ($recipients)";
    mysqli_query($db, $sql);
  }

}


?>
<!DOCTYPE html>
<html>
<head>
  <title>SMSApp</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <style type="text/css">
    body {
      background-image: url(hands.png);
      background-size: cover;
      color: white;
      background-repeat: no-repeat;
      background-position: center;
      background-attachment: fixed;
    }
    form{
      background-color: #da01fd29;
      color: white;
      padding: 40px;
      margin-top: 130px;
      margin-bottom: 120px;
      padding-bottom: 60px;
      box-shadow: 10px 10px 5px rgba(6,1,1,0.43);
    }
    h1{
      text-align: center;
    }
    .btn{
      margin-top: 20px;
    }
  </style>

</head>

<body>
      <div class="container">
        <div class="row">
          <div class="col-sm-offset-2 col-sm-10">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <h1> Ongea na wasee! </h1>
              <div class="form-group"> 
                      <label>Name</label>
                      <input type="text" name="name" class="form-control">
                      <span class="error">* <?php echo $nameErr;?></span>
                  </div>

                  <div class="form-group"> 
                    <label>Email</label>
                  <input type="text" name="email" class="form-control">
                    <span class="error">* <?php echo $emailErr;?></span>
                  </div>
                  
                  <div class="form-group"> 
                    <label>Password</label>
                      <input type="password" name="password" class="form-control">
                    <span class="error">* <?php echo $passErr;?></span>
                  </div>

                  <div class="form-group"> 
                    <label>Recipient's Phone number </label>
                      <input type="text" name="phonenumber" class="form-control" placeholder="07">
                    <span class="error">* <?php echo $emailErr;?></span>
                  </div>
                
                  <div class="form-group"> 
                    <label>Tuma message!</label> 
                      <textarea name="message" rows="5" cols="40" class="form-control"></textarea>
                </div>

            <input type="submit" name="submit" value="SEND" class="btn btn-default"> 
          </form>
    </div>
    </div>
    </div>
  </body>
</html>
