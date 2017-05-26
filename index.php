<?php

require('./tfpdf.php');
$pdf = new tFPDF('L','in',array(4,3));
//set the default line width for drawing the 
$pdf->SetLineWidth(.01);

$pdf->SetCompression(false);
//sets the creator and if the pdf is utf-8
$pdf->SetCreator('PLCH', true);
$pdf->AddPage();

$pdf->AddFont('roboto','','roboto.ttf',true);


function drawbracket($pdf, $start_x, $start_y, $position='top') {
	$pdf->SetFillColor(0);
	$line_length = 0.15;
	
	if($position == 'top'){
		$end_x = ($start_x + $line_length);
		$end_y = ($start_y + $line_length);
	}
	else{
		$end_x = ($start_x - $line_length);
		$end_y = ($start_y - $line_length);
	}
	
	
	//horizontal
	$pdf->Line($start_x, $start_y, 
		$end_x, $start_y);
	//vertical
	$pdf->Line($start_x, $start_y,
		$start_x, $end_y);
}


function code39($xpos, $ypos, $code, $baseline, $height, $pdf){

	$wide = $baseline;
	$narrow = $baseline / 2 ; 
	$gap = $narrow;

	$barChar['0'] = 'nnnwwnwnn';
	$barChar['1'] = 'wnnwnnnnw';
	$barChar['2'] = 'nnwwnnnnw';
	$barChar['3'] = 'wnwwnnnnn';
	$barChar['4'] = 'nnnwwnnnw';
	$barChar['5'] = 'wnnwwnnnn';
	$barChar['6'] = 'nnwwwnnnn';
	$barChar['7'] = 'nnnwnnwnw';
	$barChar['8'] = 'wnnwnnwnn';
	$barChar['9'] = 'nnwwnnwnn';
	$barChar['A'] = 'wnnnnwnnw';
	$barChar['B'] = 'nnwnnwnnw';
	$barChar['C'] = 'wnwnnwnnn';
	$barChar['D'] = 'nnnnwwnnw';
	$barChar['E'] = 'wnnnwwnnn';
	$barChar['F'] = 'nnwnwwnnn';
	$barChar['G'] = 'nnnnnwwnw';
	$barChar['H'] = 'wnnnnwwnn';
	$barChar['I'] = 'nnwnnwwnn';
	$barChar['J'] = 'nnnnwwwnn';
	$barChar['K'] = 'wnnnnnnww';
	$barChar['L'] = 'nnwnnnnww';
	$barChar['M'] = 'wnwnnnnwn';
	$barChar['N'] = 'nnnnwnnww';
	$barChar['O'] = 'wnnnwnnwn';
	$barChar['P'] = 'nnwnwnnwn';
	$barChar['Q'] = 'nnnnnnwww';
	$barChar['R'] = 'wnnnnnwwn';
	$barChar['S'] = 'nnwnnnwwn';
	$barChar['T'] = 'nnnnwnwwn';
	$barChar['U'] = 'wwnnnnnnw';
	$barChar['V'] = 'nwwnnnnnw';
	$barChar['W'] = 'wwwnnnnnn';
	$barChar['X'] = 'nwnnwnnnw';
	$barChar['Y'] = 'wwnnwnnnn';
	$barChar['Z'] = 'nwwnwnnnn';
	$barChar['-'] = 'nwnnnnwnw';
	$barChar['.'] = 'wwnnnnwnn';
	$barChar[' '] = 'nwwnnnwnn';
	$barChar['*'] = 'nwnnwnwnn';
	$barChar['$'] = 'nwnwnwnnn';
	$barChar['/'] = 'nwnwnnnwn';
	$barChar['+'] = 'nwnnnwnwn';
	$barChar['%'] = 'nnnwnwnwn';

	$pdf->SetFillColor(0);
	$code = '*'.strtoupper($code).'*';
	
	for($i=0; $i<strlen($code); $i++) {
		$char = $code[$i];
		
		if(!isset($barChar[$char])) {
			$pdf->Error('Invalid character in barcode: '.$char);
		}
		
		$seq = $barChar[$char];
		if ($char == '*') {
			$this_height = $height + .22;
		}
		else {
			$this_height = $height;
		}
		
		for($bar=0; $bar<9; $bar++) {
			if($seq[$bar] == 'n') {
				$lineWidth = $narrow;
			}
			else {
				$lineWidth = $wide;
			}
			
			if($bar % 2 == 0) {
				$pdf->Rect($xpos, $ypos, $lineWidth, $this_height, 'F');
			}
			
			$xpos += $lineWidth;
		} //end for
	
		$xpos += $gap;
	} //end for
} //end function code39


//$pdf->AddFont('roboto','','roboto.ttf');

// header text
// 10 pt font = 0.138889 inches
// 8  pt font = 0.111111 inches 
// alignment
drawbracket($pdf, 0.05, (0.2 - 0.111111), 'top');
drawbracket($pdf, 3.95, 0.33, 'not-top');

$pdf->SetFont('roboto','',8);
$pdf->Text(0.05, 0.2, '1234567890123456789012345678901234567890123456789012345678');
$pdf->Text(0.05, 0.33, 'RUS: Здравствулте мир / GER: äuszeren religiösen');
//$pdf->Text(0.05, 0.6, 'Burgenland" álarc nélkül : történeti-földrajzi ');
// /header text

// barcode
// alignment
//~ drawbracket($pdf, .15, .45, 'top'); //close
drawbracket($pdf, .15, .52, 'top');
drawbracket($pdf, 2.8, 1.25, 'not-top');

//$pdf->Code39(.45,1,'A000053256624',0.02,.5);
//~ code39(.43,1,'A000053256624',0.02,.5,$pdf);
code39(.3,.52,'A000053256624',0.024,.5,$pdf);
// /barcode


//~ sticker part 1
drawbracket($pdf, 2.12, 1.8, 'not-top');

//~ sticker part 2
drawbracket($pdf, 2.12, 2.15, 'not-top');

//~ sticker part 3
drawbracket($pdf, 2.12, 2.5, 'not-top');

//~ sticker part 4
drawbracket($pdf, 2.12, 2.85, 'not-top');



// ~ top right sticker
drawbracket($pdf, 2.95, .35, 'top');
drawbracket($pdf, 3.92, 1.64, 'not-top');

//~ bottom right sticker
drawbracket($pdf, 2.4, 1.83, 'top');
drawbracket($pdf, 3.9, 2.85, 'not-top');


$pdf->Output();
?>
