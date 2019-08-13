<?php
class MessageTask extends Task {
    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';

    public function updateoptionAction(){
        $objReader = PHPExcel_IOFactory::createReader('CSV')
            ->setDelimiter(',')
            ->setInputEncoding('GBK')
            ->setEnclosure('"')
            ->setLineEnding("\r\n")
            ->setSheetIndex(0);
        $objPHPExcel = $objReader->load('message_list_test.csv');

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }

        foreach ($excelData as $row => $row_arr){
            if($row==1){continue;}

            $mobile = $row_arr[2];

            if(strlen($mobile)!=11){
                continue;
            }

            if (preg_match("/^1[34578]\d{9}$/i", $mobile)) {
                continue;
            }

            $send_return = Message::sendCodeNotice($mobile);
            if($send_return!='success'){
                file_put_contents('message_notice_fail.txt', $row.' is error!/n',FILE_APPEND);
            }else{
                file_put_contents('message_notice_success.txt', $row.' is success!/n',FILE_APPEND);
            }

        }
    }

}



?>