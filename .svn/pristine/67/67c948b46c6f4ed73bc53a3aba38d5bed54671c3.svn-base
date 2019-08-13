<?php

/*
 * 玻森语义分析接口
 */

class BosonController extends \BackendBaseController
{
    /**
     * @var BosonNLP;
     */
    private static $boson = null;

    public function initialize() {

        parent::initialize();
        if (! self::$boson){
            $config = F::getConfig('BosonNLP');
            if($config["apikey"]==""){
                throw new Exception("BosonNLP没有配置");
            }else{
                self::$boson = new BosonNLP($config["apikey"]);
            }
        }
        $this->view->disable();
    }

    public function summaryAction() {
        $title = Request::getPost("title");
        $content = Request::getPost("content");
        $percentage = Request::getPost("percentage");
        $not_exceed = Request::getPost("not_exceed");
        $summary = self::$boson->summary($content,$title,$percentage,$not_exceed);
        if($summary){
            $summary = $summary["response"];
        }
        echo json_encode(compact("summary"));
    }

    public function keywordsAction() {
        $content = Request::getPost("content");
        $keywords = self::$boson->keywords($content,10);
        if ($keywords){
            $keywords = $keywords["response"];
        }
        echo json_encode(compact("keywords"));
    }

    public function keywordsSummaryAction(){

        $title = Request::getPost("title");
        $content =  BosonNLP::removePunct( Request::getPost("content") );
        $percentage = Request::getPost("percentage");
        $not_exceed = Request::getPost("not_exceed");

        $summary = self::$boson->summary($content,$title,$percentage,$not_exceed);
        $keywords = self::$boson->keywords($content,10);
        if($summary){
            $tmp = BosonNLP::removePunct($summary["response"]);
            $summary = str_replace('\n', '', $tmp);
        }
        if ($keywords){
            $res = json_decode($keywords["response"],true);
            $keywords = [];
            foreach ($res as $word){
                $keywords[] = $word[1];
            }

        }
        echo json_encode(compact("keywords","summary"));
    }

    public function nerAction() {
        $content = Request::getPost("content");
        echo self::$boson->ner($content)["response"];
    }
}