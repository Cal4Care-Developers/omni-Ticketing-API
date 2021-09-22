<?php
$string = $_REQUEST['res'];
$output = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($string)); 
$test = html_entity_decode($output,null,'UTF-8'); 
$character = json_decode($test);

$export_data =  json_decode(json_encode($character->result->data), true);
//print_r($export_data);exit;
 $invoice_no=$export_data[0]['invoice_no'];
 $created_at=$export_data[0]['created_at'];
 $date = new DateTime($created_at);
 $iv_dt = $date->format('d-m-Y');
$due=date('Y-m-d', strtotime($iv_dt. ' + 15 days'));

  $admin_id=$export_data[0]['admin_id'];
  $user_id=$export_data[0]['user_id'];
  $company_name=$export_data[0]['company_name'];
  $biller_city=$export_data[0]['biller_city'];
  $biller_add1=$export_data[0]['biller_add1'];
  $biller_add2=$export_data[0]['biller_add2'];
  $biller_state=$export_data[0]['biller_state'];
  $biller_country=$export_data[0]['biller_country'];
  $biller_zip_code=$export_data[0]['biller_zip_code'];
  $biller_phone=$export_data[0]['biller_phone'];
  $biller_toll_free=$export_data[0]['biller_toll_free'];
  $biller_logo_image=$export_data[0]['biller_logo_image'];
if($biller_logo_image==''){
	$biller_logo_image='https://omni.mconnectapps.com/api/v1.0/logo_image/omni-channels-logo.jpg';
}
else{
	$biller_logo_image=$biller_logo_image;
}
  $biller_email=$export_data[0]['biller_email'];
  $biller_reg_no=$export_data[0]['biller_reg_no'];
  $prevmonth=$export_data[0]['prevmonth'];
  $biller_account_no=$export_data[0]['biller_account_no'];
  $biller_bank='Bank :  '.$export_data[0]['biller_bank'];
  $branch='Branch :  '.$export_data[0]['biller_branch'];
  $prevmonth = date("d M Y", strtotime($prevmonth));  
  $prevEnd=$export_data[0]['prevEnd'];
 $prevEnd = date("d M Y", strtotime($prevEnd));  
$period= $prevmonth.' to '.$prevEnd;

  $agent_name=$export_data[0]['agent_name'];
  $ship_to=$export_data[0]['ship_to'];
  $ship_add1=$export_data[0]['ship_add1'];
  $ship_add2=$export_data[0]['ship_add2'];
  $ship_city=$export_data[0]['ship_city'];
  $ship_state=$export_data[0]['ship_state'];
  $ship_country=$export_data[0]['ship_country'];
  $ship_zip=$export_data[0]['ship_zip'];
  $monthly_charges=$export_data[0]['monthly_charges'];
  $amt=$export_data[0]['amt'];
  $btax=$monthly_charges+$amt;
  $discount=$export_data[0]['discount'];
  $tax_name=$export_data[0]['tax_name'];
$dis_rate=$btax-round((($discount / 100) * $btax),2);
  $tax_per=round($export_data[0]['tax_per'],2);
if($tax_name!=''){
$tax_r=round((($tax_per / 100) * $dis_rate),2); 
}
  $tot_amt=round($dis_rate+$tax_r,2);

$GLOBALS["invoice_no"] = $invoice_no;
$GLOBALS["iv_dt"] = $iv_dt;
$GLOBALS["due"] = $due;
$GLOBALS["period"] = $period;
$GLOBALS["company_name"] = $company_name;
$GLOBALS["biller_add1"] = $biller_add1;
$GLOBALS["biller_add2"] = $biller_add2;
$GLOBALS["biller_city"] = $biller_city;
$GLOBALS["biller_state"] = $biller_state;
$GLOBALS["biller_country"] = $biller_country;
$GLOBALS["biller_zip_code"] = $biller_zip_code;
if($biller_phone!=''){
$GLOBALS["biller_phone"] = 'Ph: '.$biller_phone;
}
if($biller_email!=''){
$GLOBALS["biller_email"] = 'Email: '.$biller_email;
}
if($biller_toll_free!=''){
$GLOBALS["biller_toll_free"] = 'Toll Free: '.$biller_toll_free;
}
if($biller_reg_no!=''){
$GLOBALS["biller_reg_no"] = 'Reg: '.$biller_reg_no;
}
$GLOBALS["biller_logo_image"] = $biller_logo_image;

$GLOBALS["agent_name"] = $agent_name;
$GLOBALS["ship_to"] = $ship_to;
$GLOBALS["ship_add1"] = $ship_add1;
$GLOBALS["ship_add2"] = $ship_add2;
$GLOBALS["ship_city"] = $ship_city;
$GLOBALS["ship_state"] = $ship_state;
$GLOBALS["ship_country"] = $ship_country;
$GLOBALS["ship_zip"] = $ship_zip;
//$GLOBALS["monthly_charges"] = $monthly_charges;


require('../fpdf182/fpdf.php');
//include '../fpdf182/class.pdf.php';


class PDF extends FPDF
{

    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

// Page header
    function Header()
    {
        // Logo
        $this->Image($GLOBALS["biller_logo_image"],10,6,30);
        // Arial bold 15
        $this->SetFont('Times','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(15,10,'Invoice',0,0,'C');
        //$this->Ln();
        $this->SetY(15);
        $this->SetX(120);
        $this->SetFont('Times','B',10);
        $this->Cell(70,5,$GLOBALS["company_name"],0,0,'L');
        $this->Ln();
        $this->SetX(120);
        $this->SetFont('Times','',8);
        //$this->Cell(30,5,'118 Aljuined Avenue 2 afsgsdghsghxfghdfghjdfghjdfghdfhdfghdfghdfghdfghfg',0,0,'L');
        $this->MultiCell('70','5',$GLOBALS["biller_add1"].' '.$GLOBALS["biller_add2"].' '.$GLOBALS["biller_city"].' '.$GLOBALS["biller_state"].' '.$GLOBALS["biller_country"].' '.$GLOBALS["biller_zip_code"]  ,0);
        // $this->Ln();
        $this->SetX(120);
        $this->SetFont('Times','',9);
        $this->MultiCell('70','5',$GLOBALS["biller_phone"].'  '.$GLOBALS["biller_email"].'  '.$GLOBALS["biller_toll_free"] ,0);
        // $this->Ln();
        $this->SetX(120);
        $this->SetFont('Times','',9);
        $this->MultiCell('70','5',$GLOBALS["biller_reg_no"],0);
        $this->SetX(120);
        $this->SetFont('Times','',9);
        $this->Cell('70','5','Invoice #',0);
        $this->SetX(145);
        $this->Cell('70','5',':  '.$GLOBALS["invoice_no"],0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','InvoiceDate ',0);
        $this->SetX(145);
        $this->Cell('70','5',':  '.$GLOBALS["iv_dt"] ,0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','Payment Due Date',0);
        $this->SetX(145);
        $this->Cell('70','5',':  '.$GLOBALS["due"],0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','Invoice period',0);
        $this->SetX(145);
        $this->Cell('70','5',':  '.$GLOBALS["period"] ,0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','Credit limit(SGD)',0);
        $this->SetX(145);
        $this->Cell('70','5',':',0);
        $this->Ln();
        $this->SetFont('');
        $this->SetY(30);
        $this->SetFont('Times','B',9);
        $this->Cell(30,5,$GLOBALS["agent_name"],0,0,'L');
        $this->SetFont('');
        $this->SetY(35);
        $this->SetFont('Times','',9);
        $this->MultiCell('70','5',$GLOBALS["ship_to"].'  '.$GLOBALS["ship_add1"].'  '.$GLOBALS["ship_add2"].' '.$GLOBALS["ship_city"].' '.$GLOBALS["ship_state"].'  '.$GLOBALS["ship_country"].'  '.$GLOBALS["ship_zip"]  ,0);
       //$this->MultiCell('70','5','Attn : RJ',0);
        // Line break
        $this->Ln(20);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',9);
        // Page number
        // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    function LoadData($file)
    {
        // Read file lines
        $lines = file($file);

        $data = array();
        foreach($lines as $line)
            $data[] = explode(';',trim($line));
        return $data;
    }
    function BasicTable($header)
    {
        $this->SetFillColor(178,190,181);
        // $this->Rect(160, 100, 20, 20, 'F');
        // $this->SetTextColor(255);
        $this->SetDrawColor(178,190,181);
        // Header
        foreach($header as $col)
            $this->Cell(40,7,$col,1,'',1,'1');
        $this->Ln();
        // Data

    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);
//$pdf->Header($company_name);
//$header = array('Previous Charges', 'Amount(SGD)', 'Current Charges', 'Amount(SGD)');
$pdf->SetY(74);
$pdf->SetX(20);
$pdf->SetFillColor(178,190,181);
$pdf->SetDrawColor(178,190,181);
$pdf->Cell(40,7,'Previous Charges',1,'',1,'1');
$pdf->Cell(40,7,'Amount(SGD)',1,'',1,'1');
$pdf->Cell(40,7,'Current Charges',1,'',1,'1');
$pdf->Cell(40,7,'Amount(SGD)',1,'',1,'1');
$pdf->Ln();
$pdf->SetX(20);
$pdf->SetFont('Times','',9);
$pdf->Cell('40','7','Previous Balance',0);
$pdf->Cell('40','7','0.00',0);
$pdf->Cell('40','7','Monthly Charges',0);
$pdf->Cell('40','7',$monthly_charges ,0,0,'R');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','Usage Charges',0);
$pdf->Cell('40','7',$amt,0,0,'R');
$pdf->Ln();

$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','Other Charges',0);
$pdf->Cell('40','7','0.00',0,0,'R');
$pdf->Line(100, 102, 200-20, 102);
$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','Current Charges Before Tax',0);
$pdf->Cell('40','7',$btax,0,0,'R');
$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','Discount Rates',0);
$pdf->Cell('40','7',$dis_rate,0,0,'R');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7',$tax_name,0);
$pdf->Cell('40','7',$tax_r,0,0,'R');
$pdf->Line(100, 122, 200-20, 122);
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->SetFont('Times','B',10);
$pdf->Cell('40','7','Total Charges ',0);
$pdf->Cell('40','7',$tot_amt,0,0,'R');
$pdf->Line(20, 130, 200-20, 130);
$pdf->Ln(9);
$pdf->SetX(20);
$pdf->Cell(100,7,'Fixed Charges',0,'',1,'0');
$pdf->Cell(40,7,'Amount',0,'',1,'0');
$pdf->Cell(40,7,'Total',0,'',1,'0');
$pdf -> Line(140, 130, 140, 180);
$pdf->Line(20, 138, 200-20, 138);
$pdf->Ln(9);
$pdf->SetX(20);
$pdf->SetFont('Times','B',9);
$pdf->Cell(100,7,'Telephone No',0,'',1,'0');
$pdf->Cell(20,7,$monthly_charges,0,'','R','0');
$pdf->Cell(40,7,$monthly_charges,0,'','R','0');
$pdf->SetFont('');
$pdf->Ln(6);
$pdf->SetX(25);
$pdf->Cell(40,7,$period,0,'','R','0');$pdf->Ln(9);
$pdf->SetX(20);
$pdf->SetFont('Times','B',9);
$pdf->Cell(100,7,'Usage Charges',0,'',1,'0');
$pdf->Line(20, 162, 200-20, 162);
$pdf->Ln(9);
$pdf->SetX(20);
$pdf->SetFont('Times','B',9);
$pdf->Cell(100,7,'Call Charges',0,'',1,'0');
$pdf->Cell(20,7,$amt,0,'','R','0');
$pdf->Cell(40,7,$amt,0,'','R','0');
$pdf->Ln(6);
$pdf->SetX(25);
$pdf->SetFont('');
$pdf->Cell(40,7,$period,0,'','R','0');$pdf->Ln(9);

$pdf->SetLineWidth(0.1);
$pdf->SetDash(2,3); //5mm on, 5mm off
$pdf->Line(20,190,210-20,190);
$pdf->SetDash(); //restores no dash

$pdf->Ln(10);
$pdf->SetX(55);
$pdf->SetFont('Times','',6);
$pdf->Cell(40,7,'Kindly Detach this with your remittance by cheque made payable to "'.$company_name.'"',0,'','R','0');
$pdf->SetFont('');
$pdf->Ln();
$pdf->SetX(35);
$pdf->SetFont('Times','B',9);
$pdf->Cell(20,7,'Payment Slip',0,'','L','0');
$pdf->SetX(140);
$pdf->Cell(20,7,'Total Amount Due(SGD)',0,'','L','0');
$pdf->SetX(185);
$pdf->Cell(20,7,$tot_amt,0,'','L','0');
$pdf->Ln();
$pdf->SetFont('');
$pdf->SetX(20);
$pdf->Cell(20,7,'Account Number',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':  '.$biller_account_no,0,'','L','0');
$pdf->SetFont('Times','B',8);
$pdf->SetX(140);
$pdf->Cell(25,7,'Total Amount',1,'','L','1');
$pdf->SetX(165);
$pdf->Cell(30,7,'Payment Due Date',1,'','L','1');

$pdf->Ln();
$pdf->SetFont('');

$pdf->SetX(20);
$pdf->Cell(20,7,'Tax Invoice Date',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':  '.$iv_dt,0,'','L','0');
$pdf->SetFont('Times','B',8);

$pdf->SetX(110);
$pdf->Cell(30,7,'Current Amount',1,'','L','1');
$pdf->SetX(150);
$pdf->Cell(25,7,$tot_amt,0,'','L','');
$pdf->SetX(170);
$pdf->Cell(25,7,$due,0,'','L','');
$pdf->Ln();
$pdf->SetFont('');

$pdf->SetX(20);
$pdf->Cell(20,7,'Invoice Period',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':  '.$period,0,'','L','0');
$pdf->SetFont('Times','B',9);

$pdf->SetX(110);
$pdf->Cell(30,7,'Amount Over Due',1,'','L','1');
$pdf->SetX(150);
$pdf->Cell(25,7,'0.00',0,'','L','');
$pdf->SetX(170);
$pdf->Cell(25,7,'Immediately',0,'','L','');
$pdf->SetFont('');


$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell(20,7,'Tax Invoice No',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':  '.$invoice_no,0,'','L','0');

$pdf->SetX(120);
$pdf->Cell(20,7,'Bank',0,'','L','0');
$pdf->Line(130, 230, 200-20, 230);


$pdf->Ln();

$pdf->SetX(120);
$pdf->Cell(20,7,'Cheque No',0,'','L','0');
$pdf->Line(137, 238, 200-20, 238);

$pdf->Ln();
$pdf->SetX(120);
$pdf->Cell(20,7,$biller_bank,0,'','L','0');

$pdf->Ln();
$pdf->SetX(120);
$pdf->Cell(20,7,$branch,0,'','L','0');

$pdf->Ln();
$pdf->SetX(30);
$pdf->SetFont('Times','B',9);
$pdf->Cell(20,7,$company_name,0,'','L','0');
$pdf->SetFont('Times','',8);
$pdf->Ln();
$pdf->SetX(20);
 $pdf->MultiCell('70','5',$GLOBALS["biller_add1"].' '.$GLOBALS["biller_add2"].' '.$GLOBALS["biller_city"].' '.$GLOBALS["biller_state"].' '.$GLOBALS["biller_country"].' '.$GLOBALS["biller_zip_code"]  ,0);
$pdf->SetX(20);
$pdf->MultiCell('70','5',$GLOBALS["biller_phone"].'  '.$GLOBALS["biller_email"].'  '.$GLOBALS["biller_toll_free"] ,0);

//$pdf->SetFillColor(976,245,458);

//$pdf->BasicTable($header);
// Data loading
//$data = $pdf->LoadData('countries.txt');

//$pdf->FancyTable($header,$data);


//for($i=1;$i<=40;$i++)
    //$pdf->Cell(0,10,'Printing line number '.$i,0,1);
$filename="pdf/".$invoice_no.".pdf";
$pdf->Output($filename,'F');

// curl to send email


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://omni.mconnectapps.com/api/v1.0/index.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\"operation\":\"call_tarrif\", \"moduleType\":\"call_tarrif\", \"api_type\": \"web\", \"access_token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJhdWQiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJpYXQiOjE2MDUxODU1NzQsIm5iZiI6MTYwNTE4NTU3NCwiZXhwIjoxNjA1MjAzNTc0LCJhY2Nlc3NfZGF0YSI6eyJ0b2tlbl9hY2Nlc3NJZCI6IjE1NyIsInRva2VuX2FjY2Vzc05hbWUiOiJyYXNoaWQiLCJ0b2tlbl9hY2Nlc3NUeXBlIjoiNCJ9fQ.8VNpUD-JJ3yW5BmUYuZhDQ4XVkpgIh5GbB38JScHv00\", \"element_data\":{\"action\":\"send_mail\",\"user_id\":\"$user_id\",\"admin_id\":\"$admin_id\",\"invoice_no\":\"$invoice_no\"}}",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;exit;

$pdf->Output('D',$filename);
?>