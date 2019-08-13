<?php

/**
 * Created by PhpStorm.
 * User: yantengwei
 * Date: 15/10/21
 * Time: 下午3:46
 */
class ExcelController extends \BackendBaseController {
    public function initialize() {
        parent::initialize();
        error_reporting(E_ALL);
        require_once APP_PATH . 'libraries/Excel/PHPExcel.php';
    }

    public function importStaffAction() {
        if (Request::isPost()) {
            $messages = [];
            if($excel = $this->validateExcel($messages)) {
                $objPHPExcel = PHPExcel_IOFactory::load($excel->getTempName());
                $sheetData = array_slice($objPHPExcel->getActiveSheet()->toArray(null,true,true,true),1) ;
                foreach ($sheetData as $person) {
                    $data = array(
                        "name" => $person['A'],
                        "mobile" => $person['B'],
                        "dept_id" => $person['C'],
                        "duty_id" => $person['D'],
                        "password" => "123456",
                        "status" => "2",
                    );
                    $admin = new Admin();
                    $messages = $admin->createAdmin($data);
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function exportStaffAction() {
        $objPHPExcel = new PHPExcel();
        $name = "Staff";
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("CZTV")
                    ->setLastModifiedBy("CZTV")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', Lang::_('name'))
                    ->setCellValue('B1', Lang::_('mobile'))
                    ->setCellValue('C1', Lang::_('department'))
                    ->setCellValue('D1', Lang::_('post'));

// Add some data
        $data = Admin::find("status!='0'");
        foreach ($data as $index => $admin) {
            $num=$index+2;
            $adminext = AdminExt::findFirst($admin->id);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$num, $admin->name)
                        ->setCellValue('B'.$num, $admin->mobile)
                        ->setCellValue('C'.$num, $adminext->department)
                        ->setCellValue('D'.$num, $adminext->duty);
        }


// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle(Lang::_('cztv members info'));


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    protected function validateExcel(&$messages) {
        $excel = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                if (in_array(strtolower($ext), ['xls', 'xlsx'])) {
                    $excel = $file;
                } else {
                    $messages[] = Lang::_('please upload valid excel image');
                }
            } elseif ($error == 4) {
                $excel = Request::getPost('thumb', null, '');
                if (!$excel) {
                    $messages[] = Lang::_('please choose upload excel image');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload excel image');
        }
        return $excel;
    }
}