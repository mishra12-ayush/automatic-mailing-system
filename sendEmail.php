<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require_once __DIR__ . '/vendor/autoload.php';

    if ( isset($_POST['submit'])) {

        
        //phpspreadsheet
        // Including all files from library
        require "vendor/autoload.php";

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("book.xlsx");        // the required excel file

        $worksheet = $spreadsheet->getActiveSheet();
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        $namearray = array(); // will store all the names
        $emailarray = array(); // will store all the corresponding emails

        
        for ($row = 1; $row <= $highestRow; ++$row) {
            
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                if($col==1){                                                       //only name  column will be selected
                    array_push( $namearray, $worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    }
                elseif( $col==2){                                                       //only email column will be selected
                    array_push( $emailarray, $worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    }
                else break;
                
            }
            
        }
        $x= count($namearray);             // number of recipients

for($i = 1; $i < $x; $i++)              // loop over all recipients
 {
    $name= $namearray[$i];
    $email= $emailarray[$i];
    
    
        // using mpdf
        $mpdf = new \Mpdf\Mpdf();
        $stylesheet = file_get_contents('letterStyle.css');
        $data=" <div class='mainbody'> 
        <div class='upperbody'>
        <img class='corner' src='corner.png'  alt=''>                                  
        <img src='s2.png' class='logo' alt='logo'>
        </div>

        <div class='body'>
        <center><p class='heading'>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;INTERNSHIP OFFER LETTER</p></center>
        <b>Dear $name ,</b><br>
        <p><b>Congratulations!</b> You have been selected for the<b> Campus Ambassador Program </b>for your college/institute campus </p>
        <p>As a campus ambassador for <b>Oyesters Training</b> you will be:</p>
        <ul>
            <li>Responsible for promotion of Oyesters Training in your campus online/offline.</li>
            <li>You will promote our events and services on your social media profiles and other platforms.</li>
            <li>You will not promote any unethical activity under the name of OYESTERS TRAINING.</li>
            <li> You will receive all other benefits/incentives based on your performance. As this internship is complete performance based, more revenue you bring more incentives you get as decided by the company</li>
            <li>The stipend decided as a campus ambassador is Rs.50/- per Conversion. (Base Stipend)</li>
            <li>You agree to enter into the Company's Proprietary Information and Invention Assignment Agreement (The Proprietary Information Agreement/NDA – NON- DISCLOSURE AGREEMENT) upon commencing employment hereunder.</li>
            <li>You acknowledge and agree that you are executing this letter voluntarily and without any duress or undue influence by the Company or anyone else</li>
            <li>No waiver, alteration, or modification of any of the provisions of this Agreement will be binding unless in writing and signed by duly authorized representatives of the parties hereto.</li>
            <li>Oyesters Training reserves the right to cancel the internship at any time with or without prior notice to the intern.</li>
            <li>The duration of this internship is at least one month and you can continue it as long as wish.</li>
        </ul>
            
            <center>&emsp;&emsp;&emsp;Wish you all the very best!</center> 
            <img class='everything' src='Everything.png' alt=''>
            
             <div style='width: 100%;'>
                <div id='left'>
                
                <p> Address: Oyesters Training</p>
                <p>339, M.G. Chowk,</p>
                <p>Raver, Jalgaon,</p>
                <p>Maharashtra,425508</p>
                
                </div>
                <div id='right'>
                
                <p>Website: www.oyesters.in</p>
                <p>Email: info.oyesters@gmail.com</p>
                
                </div>
             </div>
             </div>
        

    </div>
        
         ";




        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHtml($data);

        $pdf=$mpdf->output("","S");      // storing "S", the pdf in $pdf

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        $mail = new PHPMailer();

        //SMTP Settings
        $mail->isSMTP();                                       //one additional setting -google accounts -> security -> less secure apps access -> ON
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "xxxxxxxxxx@gmail.com"; //enter you email address
        $mail->Password = 'xxxxxxxxxx'; //enter you email password
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";

        $mail->addStringAttachment($pdf,"attachment.pdf");     //pdf is attached with 2nd parameter as its name

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom("xxxxxxxxxxx@gmail.com", "ayush");   //enter you email address and name
        $mail->addAddress("$email"); 
        $mail->Body = "hello";
    

        if ($mail->send()) {
            $status = "success";
            $response = "Email is sent!";
        } else {
            $status = "failed";
            $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }
        print_r($emailarray);
        exit(json_encode(array("status" => $status, "response" => $response)));

        unset($name);             // resets the value
        unset($email);            // resets the value
    }  
}
?>
