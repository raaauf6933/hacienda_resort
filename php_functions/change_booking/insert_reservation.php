<?php
include "../dbconn.php";

date_default_timezone_set("Asia/Singapore");
$reservation_id = (int)$_POST['reservation_id'];
$billing_id = (int)$_POST['billing_id'];
$guest_id = (int)$_POST['guest_id'];
$check_in = $_POST['checkin_date'];
$check_out = $_POST['checkout_date'];
$num_guest = (int)$_POST['num_guest'];
$change_date = date("Y-m-d H:i:s");

if ((date("Y-m-d", strtotime($check_in))) == (date("Y-m-d", strtotime($change_date . '+1 day')))) {
    $expiration_date = date("Y-m-d 23:59:59");
} else {
    $expiration_date = date("Y-m-d H:i:s", strtotime($change_date . '+1 day'));
}


$guest_query = mysqli_query($conn, "SELECT *
FROM guest g
LEFT JOIN reservation rv on rv.guest_id = g.guest_id
WHERE g.guest_id = '$guest_id'");

$first_name = "";
$last_name = "";
$booking_reference = "";
$email = "";
$contact_number = "";
$address = "";
$city = "";
$zipcode = "";

while($row = mysqli_fetch_assoc($guest_query)){
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $booking_reference = $row['booking_reference'];
    $email = $row['email'];
    $contact_number = $row['contact_number'];
    $address  = $row['addressline_1'];
    $city = $row['city'];
    $zipcode = $row['zipcode'];
}


$earlier = new DateTime($check_in);
$later = new DateTime($check_out);
$nights = $later->diff($earlier)->format("%a");

$room_array = json_decode($_POST['room_details']);




$update_reservation = mysqli_query($conn, "UPDATE reservation SET num_guest = '$num_guest', checkin_date = '$check_in', checkout_date = '$check_out', expiration_date = '$expiration_date' WHERE reservation_id = '$reservation_id'");
$delete_billing = mysqli_query($conn,"DELETE FROM billing WHERE billing_id = '$billing_id'" );
$delete_rooms = mysqli_query($conn, "DELETE FROM room_reservation WHERE reservation_id = '$reservation_id'");




$room_rows = "";
$total_amount = 0;
foreach ($room_array as $room_array) {
    foreach ($room_array->room_ids as $rooms) {

        $sql_get_room = mysqli_query($conn, "SELECT rt.roomtype_name,rt.roomtype_price,rm.room_num FROM rooms rm
            left join room_type rt on rt.roomtype_id = rm.roomtype_id
            where rm.room_id = '$rooms'");

        $room_name = 0;
        $room_price = 0;
        $room_num = 0;
        while ($row = mysqli_fetch_assoc($sql_get_room)) {
            $room_name = $row['roomtype_name'];
            $room_price = $row['roomtype_price'];
            $room_num = $row['room_num'];
        }
        $sql_room = "INSERT INTO `room_reservation` (`reservation_id`, `room_id`,`roomtype_name`,`room_price`,`room_num`) VALUES ('$reservation_id', '$rooms','$room_name','$room_price','$room_num')";
        $result_room = mysqli_query($conn, $sql_room);
    }

    $sql_billing = "INSERT INTO `billing` (`billing_id`, `original_capital`) VALUES ('$billing_id', '$room_array->new_price')";
    $resul_billing = mysqli_query($conn, $sql_billing);

    $total_amount += (int)$room_array->new_price;
    $room_rows .= '<tr style="text-align: center;">
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $room_array->roomtype_name . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $room_array->new_price / $room_array->num_rooms / $nights . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $nights . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $room_array->num_rooms . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $room_array->new_price . '</span>
          </p>
        </td>
  
      </tr>';
}

$vat = ($total_amount * 0.12);
$vatable = $total_amount - $vat;
$downpayment = $total_amount / 2;

$email_body = '<html>
<style>
</style>
<body style="background-color:#ffffff;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#efefef;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px #ef7e24;     box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
    <thead>
      <tr>
        <th style="text-align:left;" colspan="4"><img style="max-width: 70px;" src="cid:logo_2u" ><strong> Fairfields Resort & Playhouse Inn.</strong></th>
        <th style="text-align:right;font-weight:400;">' . $change_date . '</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px; text-align: center; padding: 2rem; color:#17a2b8!important" colspan="5"><h2>CHANGE BOOKING</h2></td>
      </tr>
      <tr>
        <td colspan="5" style="border: solid 1px #ddd; padding:10px 20px;">
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Status</span><b style="color:red;font-weight:normal;margin:0">Pending</b></p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Reservation ID</span> ' . $reservation_id . '</p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Booking Reference</span> ' . $booking_reference . '</p>
          <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Payment Due</span>' . $expiration_date . '</p>
          
        </td>
      </tr>
      
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top" colspan="3">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Name</span> ' . $first_name . ' ' . $last_name . '</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> ' . $email . '</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> ' . $contact_number . '</p>
        </td>
        <td style="width:50%;padding:20px;vertical-align:top" colspan="2">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span> ' . $address . ', ' . $city . ', ' . $zipcode . '</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Number of gusets</span> ' . $num_guest . '</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Duration of your vacation</span><div style="font-size: 17px; color:mediumblue; font-weight: bold;"><small>' . $check_in . '</small>  to <small>' . $check_out . '.</small></p>
        </td>
      </tr>
      <tr  >
        <th  style="font-size:14px; padding:10px; border-bottom: 3px solid #929090; border-top:3px solid #929090 ;">Availed</th>
        <th  style="font-size:14px; padding:10px; border-bottom: 3px solid #929090; border-top:3px solid #929090 ;">Rate</th>
        <th  style="font-size:14px; padding:10px; border-bottom: 3px solid #929090; border-top:3px solid #929090 ;">Night(s)</th>
        <th  style="font-size:14px; padding:10px; border-bottom: 3px solid #929090; border-top:3px solid #929090 ;">Qty</th>
        <th  style="font-size:14px; padding:10px; border-bottom: 3px solid #929090; border-top:3px solid #929090 ;">Total Amount</th>
      </tr>
      ' . $room_rows . '
    </tbody>
    <tfooter>
      <tr style="text-align: center;">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px;"><b>Vatable Sales</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px;">
         Php ' . $vatable . '
        </td>
      </tr>
        <tr style="text-align: center;">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px;"><b>VAT (12%)</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px;">
         Php ' . $vat . '
        </td>
      </tr>
        <tr style="text-align: center; ">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px; "><b>Subtotal</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px; ">
         Php ' . $total_amount . '
        </td>
      </tr>
      
        <!--<tr style="text-align: center;">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px;"><b>Payed Amount</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px;">
         Php 5,000.00
        </td>
      </tr> 
        <tr style="text-align: center;">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px;"><b>Balance</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px;">
         Php 5,000.00
        </td>
      </tr>-->

      <tr>
          <td style="height: 1rem;"></td>
      </tr>
       <tr style="text-align: center;  ">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px;  color:navy"><b>Downpayment</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px; color:navy">
         Php ' . $downpayment . '
        </td>
      </tr>
    </tfooter>
    <tr>
        <td style="height: 3rem;"></td>
    </tr>
    <tr >
        <td colspan="5"><b style="font-family:Arial, Helvetica, sans-serif; ">HOW TO PAY?</b></td>
    </tr>
    <tr>
        <td colspan="5" style="color: #6b6b6b;">* Payment is available only for Bank Deposit</td>
       
    </tr>
    <tr> <td colspan="5" style="color: #6b6b6b;">* Deposit your 50% Downpayment to bank, <br/>and upload your picture of bank receipt here <a href="http://fairfieldsresort.com/my_booking.html" target="_blank">My Booking</a></td></tr>
    
    <tr>
        <td style="height: 1rem;"></td>
    </tr>
      <tr >
        <td colspan="5"><b style="font-family:Arial, Helvetica, sans-serif; ">POLICY & CONDITIONS</b></td>
    </tr>
    <tr><td colspan="5" style="color:#6b6b6b">*You need to pay your 50% downpayment before payment due, else your booking will be voided</td></tr>
    <tr><td colspan="5" style="color:#6b6b6b">*Downpayment is strictly 50% of the Total amount only</td></tr>
    <tr><td colspan="5" style="color:#6b6b6b">*No Cancellation/Re-book once the booking is paid</td></tr>
    <tr><td colspan="5" style="color:#6b6b6b">*No Refund</td></tr>
  </table>
</body>

</html>';


require("../email/PHPMailer-master/src/PHPMailer.php");
require("../email/PHPMailer-master/src/SMTP.php");
require("../email/PHPMailer-master/src/Exception.php");


$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP();

$mail->CharSet = "UTF-8";
$mail->Host = "sg3plcpnl0031.prod.sin3.secureserver.net";
//$mail->SMTPAutoTLS = false; 
$mail->SMTPDebug = 1;
$mail->Port = 25; //465 or 587

$mail->SMTPSecure = 'tsl';
$mail->SMTPAuth = true;
$mail->IsHTML(true);

//Authentication
$mail->Username = "desk.resort@fairfieldsresort.com";
$mail->Password = "Fa2425dS";

//Set Params
$mail->AddEmbeddedImage('../../img/icon.png', 'logo_2u');

$mail->SetFrom("desk.resort@fairfieldsresort.com", "FairFields Resort & Playhouse Inn");
$mail->AddAddress($email);
$mail->AddCC("desk.resort@fairfieldsresort.com");
$mail->Subject = "Booking Invoice | Change Booking | Fairfields Resort & Playhouse Inn";
$mail->Body = $email_body;



if (!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message has been sent";
}
 
?>