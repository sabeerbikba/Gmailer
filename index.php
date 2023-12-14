<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
</head>

<body>
    <?php
    function renderForm()
    {
    ?>
        <form action="index.php" method="post">
            <fieldset>
                <legend>
                    <h2>Send mail</h2>
                </legend>
                <label for="email">To email address:</label><br>
                <input type="email" name="email" required><br><br>
                <label for="subject">Subject:</label><br>
                <input type="text" name="subject" required><br><br>
                <label for="msg">Message: </label><br>
                <textarea name="msg" cols="30" rows="10" required></textarea><br><br>
                <button>Send</button>
            </fieldset>
        </form>
    <?php
    }

    function sendEmail($to, $subject, $msg)
    {
        require_once(__DIR__ . '/smtp/PHPMailerAutoload.php');

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        //$mail->SMTPDebug = 2; 
        $mail->Username = "sabeerbikba02@gmail.com"; // not to be change
        $mail->Password = "rekkgklhgbruvfmo"; // need to genrate password as shown in README.md
        $mail->SetFrom("sabeerbikba02@gmail.com"); // need to be change
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        ));

        if (!$mail->Send()) {
            throw new Exception($mail->ErrorInfo);
        }

        return true;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $to = htmlspecialchars($_POST['email']);
            $subject = htmlspecialchars($_POST['subject']);
            $msg = htmlspecialchars($_POST['msg']);

            if (sendEmail($to, $subject, $msg)) {
                echo '<b style="color: green;">Mail sent!</b>';
            } else {
                throw new Exception('Could not send email.');
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . '<br>Try again!';
        }

        renderForm();
    } else {
        renderForm();
    }
    ?>
</body>

</html>
