<?php

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
        $this->Image('http://www.cal4care.com.sg/wp-content/uploads/2018/11/cal4care-sg-logo.png',10,6,30);
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
        $this->Cell(70,5,'Cal4Care Pte Ltd',0,0,'L');
        $this->Ln();
        $this->SetX(120);
        $this->SetFont('Times','',8);
        //$this->Cell(30,5,'118 Aljuined Avenue 2 afsgsdghsghxfghdfghjdfghjdfghdfhdfghdfghdfghdfghfg',0,0,'L');
        $this->MultiCell('70','5','118 Aljunied Avenue 2 #07-102,Singapore ',0);
        // $this->Ln();
        $this->SetX(120);
        $this->SetFont('Times','',9);
        $this->MultiCell('70','5','Ph: +65 6340 1006, Fax +65 6340 1007, 24/7: 6340 1006 Email: Support@cal4care.com.sg, Toll Free 1800 3401006',0);
        // $this->Ln();
        $this->SetX(120);
        $this->SetFont('Times','',9);
        $this->MultiCell('70','5','Reg: 200812932, GST: 200812932',0);
        $this->SetX(120);
        $this->SetFont('Times','',9);
        $this->Cell('70','5','InvoiceNo',0);
        $this->SetX(145);
        $this->Cell('70','5',':',0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','InvoiceDate ',0);
        $this->SetX(145);
        $this->Cell('70','5',':',0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','Payment Due Date',0);
        $this->SetX(145);
        $this->Cell('70','5',':',0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','Invoice period',0);
        $this->SetX(145);
        $this->Cell('70','5',':',0);
        $this->Ln();
        $this->SetX(120);
        $this->Cell('70','5','Credit limit(SGD)',0);
        $this->SetX(145);
        $this->Cell('70','5',':',0);
        $this->Ln();
        $this->SetFont('');
        $this->SetY(30);
        $this->SetFont('Times','B',9);
        $this->Cell(30,5,'Customer Name',0,0,'L');
        $this->SetFont('');
        $this->SetY(35);
        $this->SetFont('Times','',9);
        $this->MultiCell('70','5','Abc pvt ltd, Singapore 983989',0);
        $this->MultiCell('70','5','Attn : RJ',0);
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