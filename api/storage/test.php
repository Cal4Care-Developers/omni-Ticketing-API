<?php
require('fpdf182/fpdf.php');
include 'fpdf182/class.pdf.php';


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);
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
$pdf->Cell('40','7','20.36',0);
$pdf->Cell('40','7','Monthly Charges',0);
$pdf->Cell('40','7','15.00',0,0,'R');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','Usage Charges',0);
$pdf->Cell('40','7','1.49',0,0,'R');
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
$pdf->Cell('40','7','16.49',0,0,'R');
$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','Discount Rates',0);
$pdf->Cell('40','7','16.49',0,0,'R');
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','GST ',0);
$pdf->Cell('40','7','1.15',0,0,'R');
$pdf->Line(100, 122, 200-20, 122);
$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell('40','7','',0);
$pdf->Cell('40','7','',0);
$pdf->SetFont('Times','B',10);
$pdf->Cell('40','7','Total Charges ',0);
$pdf->Cell('40','7','1.15',0,0,'R');
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
$pdf->Cell(20,7,'15.00',0,'','R','0');
$pdf->Cell(40,7,'15.00',0,'','R','0');
$pdf->SetFont('');
$pdf->Ln(6);
$pdf->SetX(25);
$pdf->Cell(40,7,'1 Sep 2020 to 30 Sep 2020',0,'','R','0');$pdf->Ln(9);
$pdf->SetX(20);
$pdf->SetFont('Times','B',9);
$pdf->Cell(100,7,'Usage Charges',0,'',1,'0');
$pdf->Line(20, 162, 200-20, 162);
$pdf->Ln(9);
$pdf->SetX(20);
$pdf->SetFont('Times','B',9);
$pdf->Cell(100,7,'Call Charges',0,'',1,'0');
$pdf->Cell(20,7,'1.49',0,'','R','0');
$pdf->Cell(40,7,'1.49',0,'','R','0');
$pdf->Ln(6);
$pdf->SetX(25);
$pdf->SetFont('');
$pdf->Cell(40,7,'1 Sep 2020 to 30 Sep 2020',0,'','R','0');$pdf->Ln(9);

$pdf->SetLineWidth(0.1);
$pdf->SetDash(2,3); //5mm on, 5mm off
$pdf->Line(20,190,210-20,190);
$pdf->SetDash(); //restores no dash

$pdf->Ln(10);
$pdf->SetX(55);
$pdf->SetFont('Times','',6);
$pdf->Cell(40,7,'Kindly Detach this with your remittance by cheque made payable to "Cal4Care Pte ltd"',0,'','R','0');
$pdf->SetFont('');
$pdf->Ln();
$pdf->SetX(35);
$pdf->SetFont('Times','B',9);
$pdf->Cell(20,7,'Payment Slip',0,'','L','0');
$pdf->SetX(140);
$pdf->Cell(20,7,'Total Amount Due(SGD)',0,'','L','0');
$pdf->SetX(185);
$pdf->Cell(20,7,'17.64',0,'','L','0');
$pdf->Ln();
$pdf->SetFont('');
$pdf->SetX(20);
$pdf->Cell(20,7,'Account Number',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':',0,'','L','0');
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
$pdf->Cell(20,7,':',0,'','L','0');
$pdf->SetFont('Times','B',8);

$pdf->SetX(110);
$pdf->Cell(30,7,'Current Amount',1,'','L','1');
$pdf->SetX(150);
$pdf->Cell(25,7,'17.64',0,'','L','');
$pdf->SetX(170);
$pdf->Cell(25,7,'19-09-2019',0,'','L','');
$pdf->Ln();
$pdf->SetFont('');

$pdf->SetX(20);
$pdf->Cell(20,7,'Invoice Period',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':',0,'','L','0');
$pdf->SetFont('Times','B',9);

$pdf->SetX(110);
$pdf->Cell(30,7,'Amount Over Due',1,'','L','1');
$pdf->SetX(150);
$pdf->Cell(25,7,'17.64',0,'','L','');
$pdf->SetX(170);
$pdf->Cell(25,7,'Immediately',0,'','L','');
$pdf->SetFont('');


$pdf->Ln();
$pdf->SetX(20);
$pdf->Cell(20,7,'Tax Invoice No',0,'','L','0');
$pdf->SetX(50);
$pdf->Cell(20,7,':',0,'','L','0');

$pdf->SetX(120);
$pdf->Cell(20,7,'Bank',0,'','L','0');
$pdf->Line(130, 230, 200-20, 230);


$pdf->Ln();

$pdf->SetX(120);
$pdf->Cell(20,7,'Cheque No',0,'','L','0');
$pdf->Line(137, 238, 200-20, 238);

$pdf->Ln();
$pdf->SetX(120);
$pdf->Cell(20,7,'Bank : Overseas chinese Banking Corporate',0,'','L','0');

$pdf->Ln();
$pdf->SetX(120);
$pdf->Cell(20,7,'Branch : Singapore',0,'','L','0');

$pdf->Ln();
$pdf->SetX(30);
$pdf->SetFont('Times','B',9);
$pdf->Cell(20,7,'Cal4Care Pte Ltd',0,'','L','0');
$pdf->SetFont('Times','',8);
$pdf->Ln();
$pdf->SetX(20);
$pdf->MultiCell('70','5','118 Aljunied Avenue 2 #07-102,Singapore ',0);
$pdf->SetX(20);
$pdf->MultiCell('70','5','Ph: +65 6340 1006, Fax +65 6340 1007, 24/7: 6340 1006 Email: Support@cal4care.com.sg, Toll Free 1800 3401006',0);

//$pdf->SetFillColor(976,245,458);

//$pdf->BasicTable($header);
// Data loading
//$data = $pdf->LoadData('countries.txt');

//$pdf->FancyTable($header,$data);


//for($i=1;$i<=40;$i++)
    //$pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();
?>