<?php
namespace DAO;
use DAO\IMailerDAO as IMailerDAO;
use Services\PHPMailer as PHPMailer;
use Services\SMTP as SMTP;
use Services\Exception;
use Helper\SessionHelper as SessionHelper;



class MailerDAO implements IMailerDAO{

    public function welcomeMail($lastname,$name,$email){
        
        $fullName = $lastname . " " . $name;
        try{
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'd06bef564e3a17';
        $phpmailer->Password = 'dfeffc4266b7af';
        $phpmailer->setFrom('pethero@pethero.com', 'Mailer');
        $phpmailer->addAddress($email,$fullName);  
    
        //Content
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->isHTML(true);                                  //Set email format to HTML
        $phpmailer->Subject = 'Welcome to PetHERO!';
        $phpmailer->Body    = "
        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
 <head>
  <meta charset='UTF-8'>
  <meta content='width=device-width, initial-scale=1' name='viewport'>
  <meta name='x-apple-disable-message-reformatting'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta content='telephone=no' name='format-detection'>
  <title>Nueva plantilla</title><!--[if (mso 16)]>
    <style type='text/css'>
    a {text-decoration: none;}
    </style>
    <![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]>
<xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG></o:AllowPNG>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
</xml>
<![endif]-->
  <style type='text/css'>
#outlook a {
	padding:0;
}
.es-button {
	mso-style-priority:100!important;
	text-decoration:none!important;
}
a[x-apple-data-detectors] {
	color:inherit!important;
	text-decoration:none!important;
	font-size:inherit!important;
	font-family:inherit!important;
	font-weight:inherit!important;
	line-height:inherit!important;
}
.es-desk-hidden {
	display:none;
	float:left;
	overflow:hidden;
	width:0;
	max-height:0;
	line-height:0;
	mso-hide:all;
}
[data-ogsb] .es-button {
	border-width:0!important;
	padding:10px 20px 10px 20px!important;
}
@media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1, h2, h3, h1 a, h2 a, h3 a { line-height:120% } h1 { font-size:30px!important; text-align:center } h2 { font-size:26px!important; text-align:center } h3 { font-size:20px!important; text-align:center } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:26px!important } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important } .es-menu td a { font-size:12px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class='gmail-fix'] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:20px!important; display:block!important; border-left-width:0px!important; border-right-width:0px!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0!important } .es-m-p0r { padding-right:0!important } .es-m-p0l { padding-left:0!important } .es-m-p0t { padding-top:0!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-m-p5 { padding:5px!important } .es-m-p5t { padding-top:5px!important } .es-m-p5b { padding-bottom:5px!important } .es-m-p5r { padding-right:5px!important } .es-m-p5l { padding-left:5px!important } .es-m-p10 { padding:10px!important } .es-m-p10t { padding-top:10px!important } .es-m-p10b { padding-bottom:10px!important } .es-m-p10r { padding-right:10px!important } .es-m-p10l { padding-left:10px!important } .es-m-p15 { padding:15px!important } .es-m-p15t { padding-top:15px!important } .es-m-p15b { padding-bottom:15px!important } .es-m-p15r { padding-right:15px!important } .es-m-p15l { padding-left:15px!important } .es-m-p20 { padding:20px!important } .es-m-p20t { padding-top:20px!important } .es-m-p20r { padding-right:20px!important } .es-m-p20l { padding-left:20px!important } .es-m-p25 { padding:25px!important } .es-m-p25t { padding-top:25px!important } .es-m-p25b { padding-bottom:25px!important } .es-m-p25r { padding-right:25px!important } .es-m-p25l { padding-left:25px!important } .es-m-p30 { padding:30px!important } .es-m-p30t { padding-top:30px!important } .es-m-p30b { padding-bottom:30px!important } .es-m-p30r { padding-right:30px!important } .es-m-p30l { padding-left:30px!important } .es-m-p35 { padding:35px!important } .es-m-p35t { padding-top:35px!important } .es-m-p35b { padding-bottom:35px!important } .es-m-p35r { padding-right:35px!important } .es-m-p35l { padding-left:35px!important } .es-m-p40 { padding:40px!important } .es-m-p40t { padding-top:40px!important } .es-m-p40b { padding-bottom:40px!important } .es-m-p40r { padding-right:40px!important } .es-m-p40l { padding-left:40px!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; max-height:inherit!important } }
</style>
 </head>
 <body style='width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0'>
  <div class='es-wrapper-color' style='background-color:#FFFFFF'><!--[if gte mso 9]>
			<v:background xmlns:v='urn:schemas-microsoft-com:vml' fill='t'>
				<v:fill type='tile' color='#ffffff'></v:fill>
			</v:background>
		<![endif]-->
   <table class='es-wrapper' width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#FFFFFF'>
     <tr>
      <td valign='top' style='padding:0;Margin:0'>
       <table cellpadding='0' cellspacing='0' class='es-header' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top'>
         <tr>
          <td align='center' style='padding:0;Margin:0'>
           <table bgcolor='#ffffff' class='es-header-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
             <tr>
              <td class='esdev-adapt-off' align='left' style='padding:20px;Margin:0'>
               <table cellpadding='0' cellspacing='0' class='esdev-mso-table' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:560px'>
                 <tr>
                  <td class='esdev-mso-td' valign='top' style='padding:0;Margin:0'>
                   <table cellpadding='0' cellspacing='0' class='es-left' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                     <tr>
                      <td class='es-m-p0r' valign='top' align='center' style='padding:0;Margin:0;width:415px'>
                       <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                         <tr>
                          <td align='center' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:59px;color:#666666;font-size:39px'><strong>Pet Hero!</strong></p></td>
                         </tr>
                       </table></td>
                     </tr>
                   </table></td>
                  <td style='padding:0;Margin:0;width:20px'></td>
                  <td class='esdev-mso-td' valign='top' style='padding:0;Margin:0'>
                   <table cellpadding='0' cellspacing='0' align='right' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                     <tr>
                      <td align='left' style='padding:0;Margin:0;width:125px'>
                       <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                         <tr>
                          <td align='center' style='padding:0;Margin:0;font-size:0px'><img class='adapt-img' src='https://yisyaf.stripocdn.email/content/guids/CABINET_147c57d5e3490695c4ead9607ae58419/images/herodog.jpg' alt style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic' width='125'></td>
                         </tr>
                       </table></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
             <tr>
              <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
               <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                 <tr>
                  <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                   <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                     <tr>
                      <td align='center' style='padding:0;Margin:0'><h1 style='Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:30px;font-style:normal;font-weight:bold;color:#333333'>Bienvenido/a $fullName!</h1></td>
                     </tr>
                     <tr>
                      <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'><br></p></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
           </table></td>
         </tr>
       </table>
       <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
         <tr>
          <td align='center' style='padding:0;Margin:0'>
           <table bgcolor='#ffffff' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
             <tr>
              <td class='esdev-adapt-off es-m-p10' align='left' background='https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/66551620375036465.png' style='padding:20px;Margin:0;background-image:url(https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/66551620375036465.png);background-repeat:no-repeat;background-position:center center'>
               <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                 <tr>
                  <td align='left' style='padding:0;Margin:0;width:560px'>
                   <table cellpadding='0' cellspacing='0' width='100%' bgcolor='#f6e6cb' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#f6e6cb' role='presentation'>
                     <tr>
                      <td align='center' style='Margin:0;padding-top:5px;padding-bottom:10px;padding-left:10px;padding-right:10px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#666666;font-size:16px'><strong>Ahora eres parte de la comunidad pet friendly mas grande del pais!</strong></p></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
             <tr>
              <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
               <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                 <tr>
                  <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                   <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                     <tr>
                      <td align='center' style='padding:0;Margin:0;font-size:0px'><img class='adapt-img' src='https://yisyaf.stripocdn.email/content/guids/CABINET_147c57d5e3490695c4ead9607ae58419/images/sin_titulo.png' alt style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic' width='560'></td>
                     </tr>
                     <tr>
                      <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                       <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                         <tr>
                          <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                         </tr>
                       </table></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
             <tr>
              <td align='left' style='padding:0;Margin:0;padding-left:20px;padding-right:20px'>
               <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                 <tr>
                  <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                   <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                     <tr>
                      <td align='center' bgcolor='#fff2cc' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:24px;color:#666666;font-size:16px'>Reserva con un Keeper o postulate como uno!</p></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
             <tr>
              <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
               <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                 <tr>
                  <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                   <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                     <tr>
                      <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'><br></p></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
           </table></td>
         </tr>
       </table>
       <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
         <tr>
          <td align='center' bgcolor='#fef8ed' style='padding:0;Margin:0;background-color:#fef8ed'>
           <table bgcolor='#fef8ed' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#fef8ed;width:600px'>
             <tr>
              <td class='es-m-p10r es-m-p10l' align='left' style='Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px'>
               <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                 <tr>
                  <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                   <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                     <tr>
                      <td align='left' class='es-m-txt-c' style='padding:0;Margin:0;padding-top:20px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#a0937d;font-size:14px'>SHIPPING ADDRESS</p></td>
                     </tr>
                     <tr>
                      <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                       <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                         <tr>
                          <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                         </tr>
                       </table></td>
                     </tr>
                     <tr>
                      <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'>Pet Hero<br>7600<br>Argentina</p></td>
                     </tr>
                   </table></td>
                 </tr>
               </table></td>
             </tr>
           </table></td>
         </tr>
       </table></td>
     </tr>
   </table>
  </div>
 </body>
</html>";
        $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $phpmailer->send();
    }catch (Exception $ex){ 
        throw $ex;
    }
    }


function newBooking($lastname,$name,$email){
      $fullName = $lastname . " " . $name;
      try{
      $phpmailer = new PHPMailer();
      $phpmailer->isSMTP();
      $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
      $phpmailer->SMTPAuth = true;
      $phpmailer->Port = 2525;
      $phpmailer->Username = 'd06bef564e3a17';
      $phpmailer->Password = 'dfeffc4266b7af';
      $phpmailer->setFrom('pethero@pethero.com', 'Mailer');
      $phpmailer->addAddress($email,$fullName);    
      $phpmailer->CharSet = 'UTF-8';
      $phpmailer->addCC($email);
      //Attachments
      // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content

      $phpmailer->isHTML(true);                                  //Set email format to HTML
      $phpmailer->Subject = "PET HERO - ¡New booking request!";
      $phpmailer->Body    = "
      <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
      <html xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
       <head>
        <meta charset='UTF-8'>
        <meta content='width=device-width, initial-scale=1' name='viewport'>
        <meta name='x-apple-disable-message-reformatting'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta content='telephone=no' name='format-detection'>
        <title>Nueva plantilla</title><!--[if (mso 16)]>
          <style type='text/css'>
          a {text-decoration: none;}
          </style>
          <![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]>
      <xml>
          <o:OfficeDocumentSettings>
          <o:AllowPNG></o:AllowPNG>
          <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
      </xml>
      <![endif]-->
        <style type='text/css'>
      #outlook a {
        padding:0;
      }
      .es-button {
        mso-style-priority:100!important;
        text-decoration:none!important;
      }
      a[x-apple-data-detectors] {
        color:inherit!important;
        text-decoration:none!important;
        font-size:inherit!important;
        font-family:inherit!important;
        font-weight:inherit!important;
        line-height:inherit!important;
      }
      .es-desk-hidden {
        display:none;
        float:left;
        overflow:hidden;
        width:0;
        max-height:0;
        line-height:0;
        mso-hide:all;
      }
      [data-ogsb] .es-button {
        border-width:0!important;
        padding:10px 20px 10px 20px!important;
      }
      @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1, h2, h3, h1 a, h2 a, h3 a { line-height:120% } h1 { font-size:30px!important; text-align:center } h2 { font-size:26px!important; text-align:center } h3 { font-size:20px!important; text-align:center } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:26px!important } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important } .es-menu td a { font-size:12px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class='gmail-fix'] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:20px!important; display:block!important; border-left-width:0px!important; border-right-width:0px!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0!important } .es-m-p0r { padding-right:0!important } .es-m-p0l { padding-left:0!important } .es-m-p0t { padding-top:0!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-m-p5 { padding:5px!important } .es-m-p5t { padding-top:5px!important } .es-m-p5b { padding-bottom:5px!important } .es-m-p5r { padding-right:5px!important } .es-m-p5l { padding-left:5px!important } .es-m-p10 { padding:10px!important } .es-m-p10t { padding-top:10px!important } .es-m-p10b { padding-bottom:10px!important } .es-m-p10r { padding-right:10px!important } .es-m-p10l { padding-left:10px!important } .es-m-p15 { padding:15px!important } .es-m-p15t { padding-top:15px!important } .es-m-p15b { padding-bottom:15px!important } .es-m-p15r { padding-right:15px!important } .es-m-p15l { padding-left:15px!important } .es-m-p20 { padding:20px!important } .es-m-p20t { padding-top:20px!important } .es-m-p20r { padding-right:20px!important } .es-m-p20l { padding-left:20px!important } .es-m-p25 { padding:25px!important } .es-m-p25t { padding-top:25px!important } .es-m-p25b { padding-bottom:25px!important } .es-m-p25r { padding-right:25px!important } .es-m-p25l { padding-left:25px!important } .es-m-p30 { padding:30px!important } .es-m-p30t { padding-top:30px!important } .es-m-p30b { padding-bottom:30px!important } .es-m-p30r { padding-right:30px!important } .es-m-p30l { padding-left:30px!important } .es-m-p35 { padding:35px!important } .es-m-p35t { padding-top:35px!important } .es-m-p35b { padding-bottom:35px!important } .es-m-p35r { padding-right:35px!important } .es-m-p35l { padding-left:35px!important } .es-m-p40 { padding:40px!important } .es-m-p40t { padding-top:40px!important } .es-m-p40b { padding-bottom:40px!important } .es-m-p40r { padding-right:40px!important } .es-m-p40l { padding-left:40px!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; max-height:inherit!important } }
      </style>
       </head>
       <body style='width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0'>
        <div class='es-wrapper-color' style='background-color:#FFFFFF'><!--[if gte mso 9]>
            <v:background xmlns:v='urn:schemas-microsoft-com:vml' fill='t'>
              <v:fill type='tile' color='#ffffff'></v:fill>
            </v:background>
          <![endif]-->
         <table class='es-wrapper' width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#FFFFFF'>
           <tr>
            <td valign='top' style='padding:0;Margin:0'>
             <table cellpadding='0' cellspacing='0' class='es-header' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top'>
               <tr>
                <td align='center' style='padding:0;Margin:0'>
                 <table bgcolor='#ffffff' class='es-header-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                   <tr>
                    <td class='esdev-adapt-off' align='left' style='padding:20px;Margin:0'>
                     <table cellpadding='0' cellspacing='0' class='esdev-mso-table' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:560px'>
                       <tr>
                        <td class='esdev-mso-td' valign='top' style='padding:0;Margin:0'>
                         <table cellpadding='0' cellspacing='0' class='es-left' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                           <tr>
                            <td class='es-m-p0r' valign='top' align='center' style='padding:0;Margin:0;width:415px'>
                             <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td align='center' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:59px;color:#666666;font-size:39px'><strong>Pet Hero!</strong></p></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                        <td style='padding:0;Margin:0;width:20px'></td>
                        <td class='esdev-mso-td' valign='top' style='padding:0;Margin:0'>
                         <table cellpadding='0' cellspacing='0' align='right' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:0;Margin:0;width:125px'>
                             <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td align='center' style='padding:0;Margin:0;font-size:0px'><img class='adapt-img' src='https://yisyaf.stripocdn.email/content/guids/CABINET_147c57d5e3490695c4ead9607ae58419/images/herodog.jpg' alt style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic' width='125'></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0'><h1 style='Margin:0;line-height:42px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:35px;font-style:normal;font-weight:bold;color:#333333'>¡Felicitaciones!</h1></td>
                           </tr>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:32px;color:#666666;font-size:21px'>$fullName</p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table>
             <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
               <tr>
                <td align='center' style='padding:0;Margin:0'>
                 <table bgcolor='#ffffff' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                   <tr>
                    <td class='esdev-adapt-off es-m-p10' align='left' background='https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/66551620375036465.png' style='padding:20px;Margin:0;background-image:url(https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/66551620375036465.png);background-repeat:no-repeat;background-position:center center'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='left' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' bgcolor='#f6e6cb' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#f6e6cb' role='presentation'>
                           <tr>
                            <td align='center' style='Margin:0;padding-top:5px;padding-bottom:10px;padding-left:10px;padding-right:10px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:30px;color:#666666;font-size:20px'><strong>¡Tienes una nueva reserva esperando a ser confirmada!</strong></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;font-size:0px'><img class='adapt-img' src='https://yisyaf.stripocdn.email/content/guids/CABINET_147c57d5e3490695c4ead9607ae58419/images/sin_titulo.png' alt style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic' width='560'></td>
                           </tr>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                             <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' bgcolor='#fff2cc' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:30px;color:#666666;font-size:20px'><strong>Ingresa a la aplicación para confirmar la reserva</strong></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'><br></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table>
             <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
               <tr>
                <td align='center' bgcolor='#fef8ed' style='padding:0;Margin:0;background-color:#fef8ed'>
                 <table bgcolor='#fef8ed' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#fef8ed;width:600px'>
                   <tr>
                    <td class='es-m-p10r es-m-p10l' align='left' style='Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' class='es-m-txt-c' style='padding:0;Margin:0;padding-top:20px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#a0937d;font-size:14px'>SHIPPING ADDRESS</p></td>
                           </tr>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                             <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                               </tr>
                             </table></td>
                           </tr>
                           <tr>
                            <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'>Pet Hero<br>7600<br>Argentina</p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table></td>
           </tr>
         </table>
        </div>
       </body>
      </html>";
      $phpmailer->AltBody = "";
    
        $phpmailer->send();
    }catch (Exception $ex){ 
        throw $ex;
    }
}



    public function bookingCupon($Keeperemail,$Keepername,$Keeperlastname,$cuit,$amountReservation,$firstDate,$lastDate,$petName){
      $owner = SessionHelper::getCurrentUser();
      $fullNameOwner = $owner->getfirstName() . " " . $owner->getLastName();
      $fullNameKeeper= $Keeperlastname . " ". $Keepername;
      $totalBooking = $amountReservation*2;

      try{
      $phpmailer = new PHPMailer();
      $phpmailer->isSMTP();
      $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
      $phpmailer->SMTPAuth = true;
      $phpmailer->Port = 2525;
      $phpmailer->Username = 'd06bef564e3a17';
      $phpmailer->Password = 'dfeffc4266b7af';
      $phpmailer->setFrom('pethero@pethero.com', 'PET HERO');
      $phpmailer->addAddress($Keeperemail,$fullNameKeeper);    
      //$phpmailer->addCC($owner->getEmail());
      
      //Content
      $phpmailer->CharSet = 'UTF-8';
      $phpmailer->isHTML(true);                                
      $phpmailer->Subject = "PET HERO - NEW RESERVATION";
      $phpmailer->Body    = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
      <html xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
       <head>
        <meta charset='UTF-8'>
        <meta content='width=device-width, initial-scale=1' name='viewport'>
        <meta name='x-apple-disable-message-reformatting'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta content='telephone=no' name='format-detection'>
        <title>Nueva plantilla</title><!--[if (mso 16)]>
          <style type='text/css'>
          a {text-decoration: none;}
          </style>
          <![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]>
      <xml>
          <o:OfficeDocumentSettings>
          <o:AllowPNG></o:AllowPNG>
          <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
      </xml>
      <![endif]-->
        <style type='text/css'>
      #outlook a {
        padding:0;
      }
      .es-button {
        mso-style-priority:100!important;
        text-decoration:none!important;
      }
      a[x-apple-data-detectors] {
        color:inherit!important;
        text-decoration:none!important;
        font-size:inherit!important;
        font-family:inherit!important;
        font-weight:inherit!important;
        line-height:inherit!important;
      }
      .es-desk-hidden {
        display:none;
        float:left;
        overflow:hidden;
        width:0;
        max-height:0;
        line-height:0;
        mso-hide:all;
      }
      [data-ogsb] .es-button {
        border-width:0!important;
        padding:10px 20px 10px 20px!important;
      }
      @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1, h2, h3, h1 a, h2 a, h3 a { line-height:120% } h1 { font-size:30px!important; text-align:center } h2 { font-size:26px!important; text-align:center } h3 { font-size:20px!important; text-align:center } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:26px!important } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important } .es-menu td a { font-size:12px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class='gmail-fix'] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:20px!important; display:block!important; border-left-width:0px!important; border-right-width:0px!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0!important } .es-m-p0r { padding-right:0!important } .es-m-p0l { padding-left:0!important } .es-m-p0t { padding-top:0!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-m-p5 { padding:5px!important } .es-m-p5t { padding-top:5px!important } .es-m-p5b { padding-bottom:5px!important } .es-m-p5r { padding-right:5px!important } .es-m-p5l { padding-left:5px!important } .es-m-p10 { padding:10px!important } .es-m-p10t { padding-top:10px!important } .es-m-p10b { padding-bottom:10px!important } .es-m-p10r { padding-right:10px!important } .es-m-p10l { padding-left:10px!important } .es-m-p15 { padding:15px!important } .es-m-p15t { padding-top:15px!important } .es-m-p15b { padding-bottom:15px!important } .es-m-p15r { padding-right:15px!important } .es-m-p15l { padding-left:15px!important } .es-m-p20 { padding:20px!important } .es-m-p20t { padding-top:20px!important } .es-m-p20r { padding-right:20px!important } .es-m-p20l { padding-left:20px!important } .es-m-p25 { padding:25px!important } .es-m-p25t { padding-top:25px!important } .es-m-p25b { padding-bottom:25px!important } .es-m-p25r { padding-right:25px!important } .es-m-p25l { padding-left:25px!important } .es-m-p30 { padding:30px!important } .es-m-p30t { padding-top:30px!important } .es-m-p30b { padding-bottom:30px!important } .es-m-p30r { padding-right:30px!important } .es-m-p30l { padding-left:30px!important } .es-m-p35 { padding:35px!important } .es-m-p35t { padding-top:35px!important } .es-m-p35b { padding-bottom:35px!important } .es-m-p35r { padding-right:35px!important } .es-m-p35l { padding-left:35px!important } .es-m-p40 { padding:40px!important } .es-m-p40t { padding-top:40px!important } .es-m-p40b { padding-bottom:40px!important } .es-m-p40r { padding-right:40px!important } .es-m-p40l { padding-left:40px!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; max-height:inherit!important } }
      </style>
       </head>
       <body style='width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0'>
        <div class='es-wrapper-color' style='background-color:#FFFFFF'><!--[if gte mso 9]>
            <v:background xmlns:v='urn:schemas-microsoft-com:vml' fill='t'>
              <v:fill type='tile' color='#ffffff'></v:fill>
            </v:background>
          <![endif]-->
         <table class='es-wrapper' width='100%' cellspacing='0' cellpadding='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#FFFFFF'>
           <tr>
            <td valign='top' style='padding:0;Margin:0'>
             <table cellpadding='0' cellspacing='0' class='es-header' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top'>
               <tr>
                <td align='center' style='padding:0;Margin:0'>
                 <table bgcolor='#ffffff' class='es-header-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                   <tr>
                    <td class='esdev-adapt-off' align='left' style='padding:20px;Margin:0'>
                     <table cellpadding='0' cellspacing='0' class='esdev-mso-table' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:560px'>
                       <tr>
                        <td class='esdev-mso-td' valign='top' style='padding:0;Margin:0'>
                         <table cellpadding='0' cellspacing='0' class='es-left' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                           <tr>
                            <td class='es-m-p0r' valign='top' align='center' style='padding:0;Margin:0;width:415px'>
                             <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td align='center' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:59px;color:#666666;font-size:39px'><strong>Pet Hero!</strong></p></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                        <td style='padding:0;Margin:0;width:20px'></td>
                        <td class='esdev-mso-td' valign='top' style='padding:0;Margin:0'>
                         <table cellpadding='0' cellspacing='0' align='right' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:0;Margin:0;width:125px'>
                             <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td align='center' style='padding:0;Margin:0;font-size:0px'><img class='adapt-img' src='https://yisyaf.stripocdn.email/content/guids/CABINET_147c57d5e3490695c4ead9607ae58419/images/herodog.jpg' alt style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic' width='125'></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0'><h1 style='Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:30px;font-style:normal;font-weight:bold;color:#333333'>Reserva realizada con exito!</h1></td>
                           </tr>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'><br></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table>
             <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
               <tr>
                <td align='center' style='padding:0;Margin:0'>
                 <table bgcolor='#ffffff' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                   <tr>
                    <td class='esdev-adapt-off es-m-p10' align='left' background='https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/66551620375036465.png' style='padding:20px;Margin:0;background-image:url(https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/66551620375036465.png);background-repeat:no-repeat;background-position:center center'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='left' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' bgcolor='#f6e6cb' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#f6e6cb' role='presentation'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:10px;padding-left:15px;padding-right:15px;font-size:0px'><a target='_blank' href='https://viewstripo.email' style='-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#926B4A;font-size:14px'><img src='https://yisyaf.stripocdn.email/content/guids/CABINET_455a2507bd277c27cf7436f66c6b427c/images/60121620374838489.png' alt style='display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic' width='30'></a></td>
                           </tr>
                           <tr>
                            <td align='center' style='Margin:0;padding-top:5px;padding-bottom:10px;padding-left:10px;padding-right:10px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'>Reservado con el keeper $fullNameKeeper<br>Con el CBU <strong>$cuit</strong> <br> desde el dia $firstDate hasta el $lastDate&nbsp;</p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                             <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'><!--[if mso]><table style='width:560px' cellpadding='0' cellspacing='0'><tr><td style='width:194px' valign='top'><![endif]-->
                     <table cellpadding='0' cellspacing='0' class='es-left' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                       <tr>
                        <td class='es-m-p0r es-m-p20b' align='center' style='padding:0;Margin:0;width:174px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                           </tr>
                         </table></td>
                        <td class='es-hidden' style='padding:0;Margin:0;width:20px'></td>
                       </tr>
                     </table><!--[if mso]></td><td style='width:173px' valign='top'><![endif]-->
                     <table cellpadding='0' cellspacing='0' class='es-left' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                       <tr>
                        <td class='es-m-p20b' align='center' style='padding:0;Margin:0;width:173px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:20px;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:45px;color:#666666;font-size:30px'><strong>$petName</strong></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table><!--[if mso]></td><td style='width:20px'></td><td style='width:173px' valign='top'><![endif]-->
                     <table cellpadding='0' cellspacing='0' class='es-right' align='right' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right'>
                       <tr>
                        <td align='center' style='padding:0;Margin:0;width:173px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:20px;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:45px;color:#666666;font-size:30px'>$totalBooking</p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table><!--[if mso]></td></tr></table><![endif]--></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                             <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'><!--[if mso]><table style='width:560px' cellpadding='0' cellspacing='0'><tr><td style='width:270px' valign='top'><![endif]-->
                     <table cellpadding='0' cellspacing='0' class='es-left' align='left' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left'>
                       <tr>
                        <td class='es-m-p20b' align='left' style='padding:0;Margin:0;width:270px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'>Seña<br><b>Total</b></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table><!--[if mso]></td><td style='width:20px'></td><td style='width:270px' valign='top'><![endif]-->
                     <table cellpadding='0' cellspacing='0' class='es-right' align='right' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right'>
                       <tr>
                        <td align='left' style='padding:0;Margin:0;width:270px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='right' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'>$amountReservation<br>&nbsp;$totalBooking</p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table><!--[if mso]></td></tr></table><![endif]--></td>
                   </tr>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'><br></p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table>
             <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
               <tr>
                <td align='center' style='padding:0;Margin:0'>
                 <table bgcolor='#ffffff' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px'>
                   <tr>
                    <td align='left' style='padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                             <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td style='padding:0;Margin:0;border-bottom:1px solid #999999;background:none;height:1px;width:100%;margin:0px'></td>
                               </tr>
                             </table></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table>
             <table cellpadding='0' cellspacing='0' class='es-content' align='center' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%'>
               <tr>
                <td align='center' bgcolor='#fef8ed' style='padding:0;Margin:0;background-color:#fef8ed'>
                 <table bgcolor='#fef8ed' class='es-content-body' align='center' cellpadding='0' cellspacing='0' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#fef8ed;width:600px'>
                   <tr>
                    <td class='es-m-p10r es-m-p10l' align='left' style='Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px'>
                     <table cellpadding='0' cellspacing='0' width='100%' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                       <tr>
                        <td align='center' valign='top' style='padding:0;Margin:0;width:560px'>
                         <table cellpadding='0' cellspacing='0' width='100%' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                           <tr>
                            <td align='left' class='es-m-txt-c' style='padding:0;Margin:0;padding-top:20px'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#a0937d;font-size:14px'>SHIPPING ADDRESS</p></td>
                           </tr>
                           <tr>
                            <td align='center' style='padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0'>
                             <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px'>
                               <tr>
                                <td style='padding:0;Margin:0;border-bottom:1px solid #a0937d;background:none;height:1px;width:100%;margin:0px'></td>
                               </tr>
                             </table></td>
                           </tr>
                           <tr>
                            <td align='left' style='padding:0;Margin:0'><p style='Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#666666;font-size:14px'>$fullNameKeeper<br>Pet Hero<br>7600<br>Argentina</p></td>
                           </tr>
                         </table></td>
                       </tr>
                     </table></td>
                   </tr>
                 </table></td>
               </tr>
             </table></td>
           </tr>
         </table>
        </div>
       </body>
      </html>";
      $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $response = $phpmailer->send();
      if($response == true){
        return true;
      }else{
        return false;
      }
  }catch (Exception $ex){ 
      throw $ex;
  }
  
  }
}
?>