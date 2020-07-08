<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require_once __DIR__ . '/vendor/autoload.php';

    if (isset($_POST['name']) && isset($_POST['email'])) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $body = $_POST['body'];

        
        // using mpdf
        $mpdf = new \Mpdf\Mpdf();
        $data = "";
        $data.="<h1>your details</h1>";
        $mpdf->WriteHtml($data);

        $pdf=$mpdf->output("","S");      // storing "S" the pdf in $pdf

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        $mail = new PHPMailer();

        //SMTP Settings
        $mail->isSMTP();                                       //google accounts -> security -> less secure apps access -> ON
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "yourrmail@gmail.com"; //enter you email address
        $mail->Password = 'yourpassword'; //enter you email password
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";

        $mail->addStringAttachment($pdf,"attachment.pdf");     //pdf is attached with 2nd parameter as its name

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom("youremail@gmail.com", "ayush");   //enter you email address and name
        $mail->addAddress("$email"); 
        $mail->Subject = ("$email ($subject)");
        $mail->Body = $body;

        if ($mail->send()) {
            $status = "success";
            $response = "Email is sent!";
        } else {
            $status = "failed";
            $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }

        exit(json_encode(array("status" => $status, "response" => $response)));
    }
?>
