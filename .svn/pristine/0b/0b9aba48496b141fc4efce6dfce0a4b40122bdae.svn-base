<?php

class SendMessageTask extends Task {
    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';

    public function updateoptionAction(){        
		$excelData = F::readExcel('message_list_test.csv');
        foreach ($excelData as $row => $row_arr){
            $mobile = $row_arr[2];
            if (!preg_match("/^1[34578]\d{9}$/i", $mobile)) {
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