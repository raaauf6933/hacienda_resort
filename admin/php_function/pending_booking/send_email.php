<?php
include "../dbconn.php";

$billing_id = $_POST['billing_id'];
$payed_capital = $_POST['payed_capital'];


$sql_guest = mysqli_query($conn, "SELECT *
FROM reservation rv
left join guest g on g.guest_id = rv.guest_id
where rv.billing_id = '$billing_id'");

$total_amount = 0;
$guest_name = "";
$guest_email = "";
$guest_phone = "";
$guest_address = "";
$num_guest = "";
$check_in = "";
$check_out = "";

while ($row = mysqli_fetch_assoc($sql_guest)) {
    $guest_name = $row['first_name'] . " " . $row['last_name'];
    $guest_email = $row['email'];
    $guest_phone = $row['contact_number'];
    $guest_address = $row['addressline_1'] . ", " . $row['city'] . ", " . $row['zipcode'];
    $num_guest = $row['num_guest'];
    $check_in = $row['checkin_date'];
    $check_out = $row['checkout_date'];
    $reservation_date = $row['reservation_date'];
    $booking_reference = $row['booking_reference'];
    $reservation_id = $row['reservation_id'];
}

$earlier = new DateTime($check_in);
$later = new DateTime($check_out);
$nights = $later->diff($earlier)->format("%a");


$sql_rooms = mysqli_query($conn, "SELECT rr.roomtype_name as availed,
rr.room_price as rate,
count(case when rv.billing_id = '$billing_id' then rr.roomtype_name end) as qty,
SUM(rr.room_price) as total_amount
FROM reservation rv
left join room_reservation rr on rr.reservation_id = rv.reservation_id
where rv.billing_id = '$billing_id'
group by rr.roomtype_name,rr.room_price");


$array_rooms = array();
$room_rows = "";
while ($row = mysqli_fetch_array($sql_rooms)) {
    array_push($array_rooms, $row);
}

foreach ($array_rooms as $rooms) {
  $total_amount += (int)$rooms[3] * $nights;
  $room_rows .= '<tr style="text-align: center;">
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $rooms[0] . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' .  $rooms[1] . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $nights . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $rooms[2] . '</span>
          </p>
        </td>
        <td  style="padding:15px;">
          <p style="font-size:14px;margin:0;padding:0px;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">' . $rooms[3] * $nights . '</span>
          </p>
        </td>
  
      </tr>';
}


$vat = ($total_amount * 0.12);
$vatable = $total_amount - $vat;

$balance = $total_amount - $payed_capital;

$email_body = '<html>
<style>
</style>
<body style="background-color:#ffffff;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#efefef;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px #ef7e24;     box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
    <thead>
      <tr>
        <th style="text-align:left;" colspan="4"><img style="max-width: 70px;" src="cid:logo_2u" ><strong> Hacienda Galea Resort.</strong></th>
        <th style="text-align:right;font-weight:400;"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px; text-align: center; padding: 2rem; color:#28a745!important" colspan="5"><h2>YOUR BOOKING IS CONFIRMED!</h2></td>
      </tr>
      <tr>
        <td colspan="5" style="border: solid 1px #ddd; padding:10px 20px;">
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Status</span><b style="color:green;font-weight:normal;margin:0">Confirmed</b></p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Reservation ID</span> ' . $reservation_id . ' </p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Booking Reference</span>' . $booking_reference . '</p>
        </td>
      </tr>
      
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top" colspan="3">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Name</span>' . $guest_name . ' </p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span>' . $guest_email . ' </p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span>' . $guest_phone . ' </p>
        </td>
        <td style="width:50%;padding:20px;vertical-align:top" colspan="2">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span>' . $guest_address . '</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Number of guest</span>' . $num_guest . ' </p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Duration of your stay</span><div style="font-size: 17px; color:mediumblue; font-weight: bold;"><small>' . $check_in . '</small>  to <small>' . $check_out . '</small></p>
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
   ' . $vatable . '
        </td>
      </tr>
        <tr style="text-align: center;">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px;"><b>VAT (12%)</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px;">
   ' . $vat . '
        </td>
      </tr>
        <tr style="text-align: center; ">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px; /*border-bottom: 3px solid #929090;*/"><b>Subtotal</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px; /*border-bottom: 3px solid #929090;*/">
       ' . $total_amount . '
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
          <td style="font-size:14px;padding:10px 15px 0 15px; /*border-bottom: 3px solid #929090;*/ color:navy"><b>Payed Amount</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px; /*border-bottom: 3px solid #929090;*/color:navy">
    ' . $payed_capital . '
        </td>
      </tr>
      <tr style="text-align: center;  ">
          <td></td>
          <td></td>
          <td></td>
          <td style="font-size:14px;padding:10px 15px 0 15px; /*border-bottom: 3px solid #929090;*/ color:navy"><b>Balance</b></td>
        <td colspan="1" style="font-size:14px;padding:10px 15px 0 15px; /*border-bottom: 3px solid #929090;*/color:navy">
       ' . $balance . '
        </td>
      </tr>
    </tfooter>
    <tr>
        <td style="height: 3rem;"></td>
    </tr>
    <tr> <td colspan="5" style="color: #6b6b6b;">* This is auto generated email, Do not reply.</td></tr>
  </table>
</body>

</html>';


require("email/PHPMailer-master/src/PHPMailer.php");
require("email/PHPMailer-master/src/SMTP.php");
require("email/PHPMailer-master/src/Exception.php");


$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP();

$mail->CharSet = "UTF-8";
$mail->Host = "sg2plzcpnl453268.prod.sin2.secureserver.net";
//$mail->SMTPAutoTLS = false; 
$mail->SMTPDebug = 1;
$mail->Port = 25; //465 or 587

$mail->SMTPSecure = 'tsl';
$mail->SMTPAuth = true;
$mail->IsHTML(true);

//Authentication
$mail->Username = "officialhaciendaresort@haciendagalearesort.com";
$mail->Password = "Hacienda2021";

//Set Params
$mail->AddEmbeddedImage('../../../images/logo.png', 'logo_2u');

$mail->SetFrom("officialhaciendaresort@haciendagalearesort.com", "Hacienda Galea Resort");
$mail->AddAddress($guest_email);
$mail->AddCC("officialhaciendaresort@haciendagalearesort.com");
$mail->Subject = "Booking Confirmation | Hacienda Galea Resort";
$mail->Body = $email_body;

$mail->Send();
    

?>