<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt  LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(0);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Asia/Manila');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
include('../dbcon.php');

$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
  die($cacheMethod . " caching method is not available" . EOL);
}
echo date('H:i:s'), " Enable Cell Caching using ", $cacheMethod, " method", EOL;


// Create new PHPExcel object
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("Classes/download-maternal.xlsx");

// Set document properties
echo date('H:i:s'), " Set properties", EOL;
$objPHPExcel->getProperties()->setCreator("John Doe")
  ->setLastModifiedBy("John Doe")
  ->setTitle("Health Record Management")
  ->setSubject("Health Record Management")
  ->setDescription("Copyright by AIM")
  ->setKeywords("Health Record Management")
  ->setCategory("Health Record Management");


// Create a first sheet
echo date('H:i:s'), " Add data", EOL;

$objPHPExcel->setActiveSheetIndex(0);

$cells = 5;

$maternal = mysqli_query($con, "SELECT maternal_id, patientid, 
DATE_FORMAT(reg_date,'%m-%d-%Y') AS regdate, DATE_FORMAT(birth_date,'%m-%d-%Y') AS birthdate, 
fname, minitial, lname, purok, address, 
DATE_FORMAT(lmp,'%m-%d-%Y') AS lmpdate, g, p, DATE_FORMAT(edc,'%m-%d-%Y') AS edcdate, 
DATE_FORMAT(trimester1,'%m-%d-%Y') AS tri1, DATE_FORMAT(trimester1a,'%m-%d-%Y') AS tri1a, 
DATE_FORMAT(trimester2,'%m-%d-%Y') AS tri2, DATE_FORMAT(trimester2a,'%m-%d-%Y') AS tri2a, 
DATE_FORMAT(trimester3,'%m-%d-%Y') AS tri3, DATE_FORMAT(trimester3a,'%m-%d-%Y') AS tri3a 
FROM maternal INNER JOIN client ON maternal.patientid = client.id");

$userQuery = mysqli_query($con, "SELECT user_id, username, password, CONCAT(fname, ' ', lname) AS fullname from users WHERE type = 'bhw'");
$user = mysqli_fetch_assoc($userQuery);
$preparedByName = $user['fullname'];

if (mysqli_num_rows($maternal) > 0) {
while ($data = mysqli_fetch_array($maternal)) {

  $maternal_id = strip_tags($data['maternal_id']);
  $patientid = strip_tags($data['patientid']);
  $regdate = strip_tags($data['regdate']);
  $fname = strip_tags($data['fname']);
  $minitial = strip_tags($data['minitial']);
  $lname = strip_tags($data['lname']);
  $address = strip_tags($data['purok']) . ', ' . strip_tags($data['address']);
  $age = strip_tags($data['age']);
  $lmpdate = strip_tags($data['lmpdate']);
  $g = 'G' . strip_tags($data['g']) . ' ' . 'P' . strip_tags($data['p']);
  $edcdate = strip_tags($data['edcdate']);
  $tri1 = strip_tags($data['tri1']);
  $tri1a = strip_tags($data['tri1a']);
  $tri2 = strip_tags($data['tri2']);
  $tri2a = strip_tags($data['tri2a']);
  $tri3 = strip_tags($data['tri3']);
  $tri3a = strip_tags($data['tri3a']);

  $birthdate = $data['birthdate'];
  $birthDateObj = DateTime::createFromFormat('m-d-Y', $birthdate);
  
  if ($birthDateObj === false) {
      echo "Failed to parse the birthdate.";
  } else {
      $currentDateObj = new DateTime();
      $age = $birthDateObj->diff($currentDateObj);

      $years = $age->y;
      $months = $age->m;
    }


  if ($minitial == "") {
    $fullname = strip_tags($data['fname']) . ' ' . strip_tags($data['lname']);
  } else {
    $fullname = strip_tags($data['fname']) . ' ' . strip_tags($data['minitial']) . ' ' . strip_tags($data['lname']);
  }


  if ($lmpdate == '00-00-0000') {
    $lmpdate = "";
  }
  if ($edcdate == '00-00-0000') {
    $edcdate = "";
  }
  if ($tri1 == '00-00-0000') {
    $tri1 = "";
  }
  if ($tri1a == '00-00-0000') {
    $tri1a = "";
  }
  if ($tri2 == '00-00-0000') {
    $tri2 = "";
  }
  if ($tri2a == '00-00-0000') {
    $tri2a = "";
  }
  if ($tri3 == '00-00-0000') {
    $tri3 = "";
  }
  if ($tri3a == '00-00-0000') {
    $tri3a = "";
  }


    if ($years == 1) {
        $ageToDisplay = '1 year old';
    } elseif ($years > 1) {
        $ageToDisplay = $years . ' years old';
    } elseif ($months == 1) {
        $ageToDisplay = '1 month old';
    } elseif ($months > 1) {
        $ageToDisplay = $months . ' months old';
    } elseif ($months == 0) {
        $ageToDisplay = '0 month';
    } else {
        $ageToDisplay = 'Unknown';
    }


  $objPHPExcel->getActiveSheet()
    ->setCellValue('A' . $cells, "$regdate")
    ->setCellValue('B' . $cells, "$fullname")
    ->setCellValue('C' . $cells, "$address")
    ->setCellValue('D' . $cells, "$ageToDisplay")
    ->setCellValue('E' . $cells, "$lmpdate\n$g")
    ->setCellValue('F' . $cells, "$edcdate")
    ->setCellValue('G' . $cells, "$tri1\n$tri1a")
    ->setCellValue('H' . $cells, "$tri2\n$tri2a")
    ->setCellValue('I' . $cells, "$tri3\n$tri3a");


  $objPHPExcel->getActiveSheet()
    ->getRowDimension($cells)
    ->setRowHeight(40);

  $cells++;
}

    $objPHPExcel->getActiveSheet()
        ->setCellValue('A' . ($cells + 2), "Prepared by: $preparedByName")
        ->mergeCells('A' . ($cells + 2) . ':I' . ($cells + 2));

    $objPHPExcel->getActiveSheet()
        ->getStyle('A' . ($cells + 2))
        ->getFont()
        ->setSize(12);

    $objPHPExcel->getActiveSheet()
        ->getStyle('A' . ($cells + 2))
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()
        ->getRowDimension($cells + 2)
        ->setRowHeight(40);
}


// Create a second sheet
echo date('H:i:s'), " Add data", EOL;
$objPHPExcel->setActiveSheetIndex(1);

$cells = 6;

$maternal = mysqli_query($con, "SELECT maternal_id, patientid, tetanus_status, 
DATE_FORMAT(tt1date,'%m-%d-%Y') AS tt1, DATE_FORMAT(tt2date,'%m-%d-%Y') AS tt2,
DATE_FORMAT(tt3date,'%m-%d-%Y') AS tt3, DATE_FORMAT(tt4date,'%m-%d-%Y') AS tt4, DATE_FORMAT(tt5date,'%m-%d-%Y') AS tt5,
DATE_FORMAT(iron1date,'%m-%d-%Y') AS iron1st, 1datenumber, DATE_FORMAT(iron2date,'%m-%d-%Y') AS iron2nd, 2datenumber,
DATE_FORMAT(iron3date,'%m-%d-%Y') AS iron3rd, 3datenumber, DATE_FORMAT(iron4date,'%m-%d-%Y') AS iron4th, 4datenumber,
DATE_FORMAT(iron5date,'%m-%d-%Y') AS iron5th, 5datenumber, DATE_FORMAT(iron6date,'%m-%d-%Y') AS iron6th, 6datenumber, 
DATE_FORMAT(sydate,'%m-%d-%Y') AS sy_date, syresult, DATE_FORMAT(syresult_date,'%m-%d-%Y') AS result_date, given_penicillin, 
DATE_FORMAT(given_penicillin_date,'%m-%d-%Y') AS penicillin_date, 
DATE_FORMAT(date_terminated,'%m-%d-%Y') AS terminated_date, 
outcome, gender, birth_weight, facility, nid, attended, remarks
FROM maternal INNER JOIN client ON maternal.patientid = client.id");
 
$userQuery = mysqli_query($con, "SELECT user_id, username, password, CONCAT(fname, ' ', lname) AS fullname from users WHERE type = 'bhw'");
$user = mysqli_fetch_assoc($userQuery);
$preparedByName = $user['fullname'];

if (mysqli_num_rows($maternal) > 0) {
while ($data = mysqli_fetch_array($maternal)) {
  $maternal_id = strip_tags($data['maternal_id']);
  $patientid = strip_tags($data['patientid']);
  $tetanus_status = strip_tags($data['tetanus_status']);
  $tt1 = strip_tags($data['tt1']);
  $tt2 = strip_tags($data['tt2']);
  $tt3 = strip_tags($data['tt3']);
  $tt4 = strip_tags($data['tt4']);
  $tt5 = strip_tags($data['tt5']);
  $iron1st = strip_tags($data['iron1st']) . " \n " . strip_tags($data['1datenumber']);
  $iron2nd = strip_tags($data['iron2nd']) . " \n " . strip_tags($data['2datenumber']);
  $iron3rd = strip_tags($data['iron3rd']) . " \n " . strip_tags($data['3datenumber']);
  $iron4th = strip_tags($data['iron4th']) . " \n " . strip_tags($data['4datenumber']);
  $iron5th = strip_tags($data['iron5th']) . " \n " . strip_tags($data['5datenumber']);
  $iron6th = strip_tags($data['iron6th']) . " \n " . strip_tags($data['6datenumber']);
  $sy_date = strip_tags($data['sy_date']);
  $syresult = strip_tags($data['syresult']);
  $syresult1 = strip_tags($data['result_date']);
  $penicillin = strip_tags($data['given_penicillin']);
  $penicillin1 = strip_tags($data['penicillin_date']);
  $terminated_date = strip_tags($data['terminated_date']);
  $outcome = strip_tags($data['outcome']) . " \n " . strip_tags($data['gender']);
  $birth_weight = strip_tags($data['birth_weight']);
  $facility = strip_tags($data['facility']);
  $nid = strip_tags($data['nid']);
  $attended = strip_tags($data['attended']);
  $remarks = strip_tags($data['remarks']);

  if ($tt1 == '00-00-0000') {
    $tt1 = "";
  }
  if ($tt2 == '00-00-0000') {
    $tt2 = "";
  }
  if ($tt3 == '00-00-0000') {
    $tt3 = "";
  }
  if ($tt4 == '00-00-0000') {
    $tt4 = "";
  }
  if ($tt5 == '00-00-0000') {
    $tt5 = "";
  }
  if ($iron1st < 1) {
    $iron1st = "";
  }
  if ($iron2nd < 1) {
    $iron2nd = "";
  }
  if ($iron3rd < 1) {
    $iron3rd = "";
  }
  if ($iron4th < 1) {
    $iron4th = "";
  }
  if ($iron5th < 1) {
    $iron5th = "";
  }
  if ($iron6th < 1) {
    $iron6th = "";
  }
  if ($sy_date == '00-00-0000') {
    $sy_date = "";
  }
  if ($syresult1 == '00-00-0000') {
    $syresult1 = "";
  }
  if ($penicillin1 == '00-00-0000') {
    $penicillin1 = "";
  }
  if ($terminated_date == '00-00-0000') {
    $terminated_date = "";
  }
  if ($birth_weight < 1) {
    $birth_weight = "";
  }


  $objPHPExcel->getActiveSheet()
    ->setCellValue('A' . $cells, "$tetanus_status")
    ->setCellValue('B' . $cells, "$tt1")
    ->setCellValue('C' . $cells, "$tt2")
    ->setCellValue('D' . $cells, "$tt3")
    ->setCellValue('E' . $cells, "$tt4")
    ->setCellValue('F' . $cells, "$tt5")
    ->setCellValue('G' . $cells, "$iron1st")
    ->setCellValue('H' . $cells, "$iron2nd")
    ->setCellValue('I' . $cells, "$iron3rd")
    ->setCellValue('J' . $cells, "$iron4th")
    ->setCellValue('K' . $cells, "$iron5th")
    ->setCellValue('L' . $cells, "$iron6th")
    ->setCellValue('M' . $cells, "$sy_date")
    ->setCellValue('N' . $cells, "$syresult\n$syresult1")
    ->setCellValue('O' . $cells, "$penicillin\n$penicillin1")
    ->setCellValue('P' . $cells, "$terminated_date")
    ->setCellValue('Q' . $cells, "$outcome")
    ->setCellValue('R' . $cells, "$birth_weight")
    ->setCellValue('S' . $cells, "$facility")
    ->setCellValue('T' . $cells, "$nid")
    ->setCellValue('U' . $cells, "$attended")
    ->setCellValue('V' . $cells, "$remarks");
  
  $objPHPExcel->getActiveSheet()
    ->getRowDimension($cells)
    ->setRowHeight(40);

  $cells++;
}

    $objPHPExcel->getActiveSheet()
        ->setCellValue('A' . ($cells + 2), "Prepared by: $preparedByName")
        ->mergeCells('A' . ($cells + 2) . ':V' . ($cells + 2));

    $objPHPExcel->getActiveSheet()
        ->getStyle('A' . ($cells + 2))
        ->getFont()
        ->setSize(12);

    $objPHPExcel->getActiveSheet()
        ->getStyle('A' . ($cells + 2))
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()
        ->getRowDimension($cells + 2)
        ->setRowHeight(40);
}



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="TCL-Maternal-Care.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
$objWriter->save('php://output');

exit;