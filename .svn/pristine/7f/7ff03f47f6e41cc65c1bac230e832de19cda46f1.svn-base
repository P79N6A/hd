<?php

/**
 *  块 管理
 *  model blocks, block_values
 *  @author     Haiquan Zhang
 *  @created    2015-11-27
 *  
 */
class BlockValuesController extends \BackendBaseController {

    public function indexAction() {

        if(empty($block_id))
            $block_id = Request::getQuery("id", "int");
        if(!$block_id) {
            redirect(Url::get("block/index"));
        }   
        $block = Blocks::getOne($block_id); 
        $data = BlockValues::getBlockValues($block_id);
        View::setVars(compact('block', 'data'));
        
    }

    public function addAction() {
        $block_id = Request::getQuery("block_id", "int");
        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();
            $path = $this->validateAndUpload($messages);
            foreach ($path as $key => $value) {
                $data['imageurl-'.$value['key']][] = Oss::url($value['url']);
            }            
            $data['block_id'] = $block_id;
            $validator = BlockValues::makeValidator($data, $block_id);
            if (!$validator->fails()) {
                $blockvalue = new BlockValues();
                $messages = $blockvalue->createBlockValue($data);
            } else {
                $messages = $validator->messages()->all();
            }
        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));

    }

    public function editAction() {
        $value_id = Request::get('id', 'int');
        if(!$value_id) {
            redirect(Url::get("admin/add"));
        }
        $block_value = BlockValues::getOne($value_id);
        $block_id = $block_value->block_id;
        $channel_id = Session::get('user')->channel_id;
        $block = Blocks::getOne($block_value->block_id);
        if($block->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();

            $path = $this->validateAndUpload($messages);
            foreach ($path as $key => $value) {
                $data['imageurl-'.$value['key']][] = Oss::url($value['url']);
            }            
            $data['block_id'] = $block_id;
            $validator = BlockValues::makeValidator($data, $block_id, $block_value->id);
            if (!$validator->fails()) {
                $messages = $block_value->modifyBlockValue($data);
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $values = json_decode($block_value->value);
        View::setMainView('layouts/add');
        View::setVars(compact('block_value', 'values'));
    }

    public function deleteAction() {
        $baoliao_id = (int)Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $block_value = BlockValues::getOne($id);
        $block = Blocks::getOne($block_value->block_id);
        if($block_value && $block->channel_id != $channel_id && $block_value->delete()) {
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }
        else {
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }
        exit;
    }

    private function json($value) {
        $this->response->setJsonContent($value, JSON_UNESCAPED_UNICODE);
        return $this->response;
    }

    protected function validateAndUpload(&$messages) {
        $path = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $files = Request::getUploadedFiles();
               $images = array();            
                foreach ($files as $key => $value) {
                    $file = $value; 
                    $error = $file->getError();
                    if (!$error) {
                        $ext = $file->getExtension();
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                            $tmparr = array('key'=>$key, 'url'=>Oss::uniqueUpload($ext, $file->getTempName(), Auth::user()->channel_id.'/advert'));
                            array_push($images, $tmparr);
                        } else {
                            $messages[] = Lang::_('please upload valid ad image');
                        }
                    } elseif ($error == 4) {
                        $path = Request::getPost('thumb', null, '');
                        if (!$path) {
                            $messages[] = Lang::_('请选择要上传的广告图片');
                        }
                    } else {
                        $messages[] = Lang::_('unknown error');
                    }
               }   
            
            
        } else {
            $messages[] = Lang::_('请选择要上传的广告图片');
        }
       
        return ($path)?$path:$images;
    }

}
