<?php

/**
 * Created by PhpStorm.
 * User: xwsoul
 * Date: 15/12/11
 * Time: 下午2:06
 */

use GenialCloud\Support\Parcel;

class SolrEngine {

    public static function f($q) {
        return str_replace(['*', ':', '~', '?', '!', '+', '-', '&', '|', '{', '}', '(', ')', '{', '}', '[', ']', '^', ','], '', trim($q));
    }


    private static function DepartmentData($data_id) {
        $governmentData = GovernmentDepartmentData::fetchGovernmentDepartmentId($data_id);
        if(isset($governmentData)){
            foreach ($governmentData as $v){
                $government_id = $v['government_department_id'];
            }
        }
        return GovernmentDepartment::fetchById($government_id);
    }

    public static function searchData(&$solr, $channel_id, $q, $page, $page_size = 50, $from=0, $to=0) {
        $query = new SolrQuery;
        $query->setStart(($page - 1) * $page_size);
        $query->setRows($page_size);
        //要返回的字段
        $query->addField('id');
        $query->addField('title');
        $query->addField('sub_title');
        $query->addField('intro');
        $query->addField('thumb');
        $query->addField('type');
        $query->addField('source_id');
        $query->addField('created_at');
        //参与查询的字段
        $q = 'title~' . $q . ',intro~' . $q;
		if($from&&$to) {
            $q =   ' created_at:['.$from.' TO '.$to.']';
        }
        else if($from) {
            $q =   ' created_at:['.$from.' TO 2147483648]';
        }
		
		
        $query->setQuery($q);
        //额外的过滤条件
        $query->addFilterQuery('channel_id:' . $channel_id);
        $resp = $solr->query($query);
        $r = $resp->getResponse()->response;
        $docs = $r->docs;
        $rs = [];
        foreach ($docs as $doc) {
            $d = self::processDocument($doc);
            if (!isset($doc->sub_title)) {
                $d['sub_title'] = '';
            }
            $param_values = DataExt::getExtValues($d['id']);
            $d = array_merge($d, $param_values);
            $government = self::DepartmentData($d['id']);
            if($government) {
                $d['government_id'] =$government->id;
                $d['government'] =$government->name;
            }
            $rs[] = $d;
        }
        $count = $r->numFound;
        $pages = ceil($count / $page_size);
        $size = $page_size;
        return compact('models', 'rs', 'count', 'pages', 'size', 'page');
    }

    public static function searchActivity(&$solr, $channel_id, $activity_id, $q, $page, $page_size = 50) {
        $query = new SolrQuery;
        $query->setStart(($page - 1) * $page_size);
        $query->setRows($page_size);
        //要返回的字段
        $query->addField('id');
        $query->addField('author_name');
        $query->addField('user_name');
        $query->addField('ext_field1');
        $query->addField('ext_field2');
        $query->addField('ext_fields');
        //参与查询的字段
        $q = 'author_name:' . $q . ' OR author_name:*' . $q . '* OR user_name:' . $q . ' OR user_name:*' . $q .'*';

        $query->setQuery($q);
        //额外的过滤条件
        $query->addFilterQuery('channel_id:' . $channel_id);
        $query->addFilterQuery('activity_id:' . $activity_id);
        $resp = $solr->query($query);
        $r = $resp->getResponse()->response;
        $docs = $r->docs;
        $rs = [];
        foreach ($docs as $doc) {
            $d = self::processDocument($doc);
            $rs[] = $d;
        }
        $count = $r->numFound;
        $page_size = ceil($count / $page_size);
        return compact('models', 'rs', 'count', 'page_size');
    }

    public static function processDocument($r) {
        $d = [];
        foreach ($r as $k => $v) {
            if (is_array($r->$k)) {
                if (count($v) == 1) {
                    $d[$k] = $v[0];
                } else {
                    $d[$k] = $v;
                }
            } else {
                $d[$k] = $v;
            }
        }
        return $d;
    }

    public static function searchUser(&$solr, $channel_id, $q, $qtype, $page, $page_size = 50) {
        $query = new SolrQuery;
        $query->setStart(($page - 1) * $page_size);
        $query->setRows($page_size);
        //要返回的字段
        $query->addField('id');
        $query->addField('username');
        $query->addField('nickname');
        $query->addField('avatar');
        $query->addField('gender');
        $query->addField('credits');
        $query->addField('status');
        $query->addField('email');
        $query->addField('mobile');
        $query->addField('created_at');
        //参与查询的字段
        $q2 = "";
        //参与查询的字段
        if ($qtype & 1) $q2 = 'username~' . $q['username'];
        if ($qtype & 2) $q2 .= ($q2 != "") ? ',nickname~' . $q['nickname'] : 'nickname~' . $q['nickname'];
        if ($qtype & 4) $q2 .= ($q2 != "") ? ',email~' . $q['email'] : 'email~' . $q['email'];
        if ($qtype & 8) $q2 .= ($q2 != "") ? ',mobile~' . $q['mobile'] : 'mobile~' . $q['mobile'];
        if ($qtype == 1) $q2 = 'username:' . $q['username'];
        if ($qtype == 2) $q2 = 'nickname:' . $q['nickname'];
        if ($qtype == 4) $q2 = 'email:' . $q['email'];
        if ($qtype == 8) $q2 = 'mobile:' . $q['mobile'];
        echo $q2;exit;

        $query->setQuery($q2);
        //额外的过滤条件
        $query->addFilterQuery('channel_id:' . $channel_id);
        $resp = $solr->query($query);
        $r = $resp->getResponse()->response;
        $docs = $r->docs;
        $rs = [];
        foreach ($docs as $doc) {
            $d = self::processDocument($doc);
            $rs[] = $d;
        }
        $count = $r->numFound;
        $page_size = ceil($count / $page_size);
        return compact('models', 'rs', 'count', 'page_size');
    }

    public static function searchComment(&$solr, $channel_id, $q, $qtype, $page, $page_size = 50) {
        $query = new SolrQuery;
        $query->setStart(($page - 1) * $page_size);
        $query->setRows($page_size);
        //要返回的字段
        $query->addField('id');
        $query->addField('user_id');
        $query->addField('status');
        $query->addField('ip');

        $query->addField('username');
        $query->addField('data_id');
        $query->addField('content');
        $query->addField('created_at');
        //参与查询的字段
        if ($qtype == 1) $q2 = 'content~' . $q['comment'];
        if ($qtype == 2) $q2 = 'username~' . $q['nickname'];
        if ($qtype == 3) $q2 = 'username~' . $q['nickname'] . ',content~' . $q['comment'];
        if($q['from']&&$q['to']) {
            $q2 .=   'AND created_at:['.$q['from'].' TO '.$q['to'].']';
        }
        else if($q['from']){
            $q2 .=   'AND created_at:['.$q['from'].' TO 2147483648]';
        }
        else if($q['to']){
            $q2 .=   'AND created_at:[-2147483648 TO '.$q['to'].']';
        }


        $query->setQuery($q2);
        //额外的过滤条件
        $query->addFilterQuery('channel_id:' . $channel_id);

        $resp = $solr->query($query);
        $r = $resp->getResponse()->response;
        $docs = $r->docs;
        $rs = [];
        foreach ($docs as $doc) {
            $d = self::processDocument($doc);
            $rs[] = $d;
        }
        $count = $r->numFound;
        $page_size = ceil($count / $page_size);
        return compact('models', 'rs', 'count', 'page_size');
    }

    /**
     * @param $r
     * @return SolrInputDocument
     */
    public static function newDataDocument(&$r) {
        $doc = new SolrInputDocument();
        $doc->addField('id', $r->id);
        $doc->addField('title', $r->title);
        $doc->addField('sub_title', $r->sub_title);
        $doc->addField('thumb', $r->thumb);
        $doc->addField('intro', $r->intro);
        $doc->addField('type', $r->type);
        $doc->addField('source_id', $r->source_id);
        $doc->addField('channel_id', $r->channel_id);
        $doc->addField('created_at', $r->created_at);
        if($r->data_template_id) {
            $param_values = DataExt::getExtValues($r->id);
            foreach($param_values  as $custom_key=>$ext_value) {
                $doc->addField($custom_key, $ext_value);
            }
        }

        return $doc;
    }

    /**
     * @param $r
     * @return SolrInputDocument
     */
    public static function newUserDocument(&$r) {
        $doc = new SolrInputDocument();
        $doc->addField('id', $r->uid);
        $doc->addField('username', $r->username);
        $doc->addField('nickname', $r->nickname);
        $doc->addField('avatar', $r->avatar);
        $doc->addField('gender', $r->gender);
        $doc->addField('credits', $r->credits);
        $doc->addField('status', $r->status);
        $doc->addField('email', $r->email);
        $doc->addField('mobile', $r->mobile);
        $doc->addField('channel_id', $r->channel_id);
        $doc->addField('created_at', $r->created_at);
        return $doc;
    }

    /**
     * @param $r
     * @return SolrInputDocument
     */
    public static function newCommentDocument(&$r) {
        $doc = new SolrInputDocument();
        $doc->addField('id', $r->id);
        $doc->addField('comment_id', $r->comment_id);
        $doc->addField('user_id', $r->user_id);
        $doc->addField('username', $r->username);
        $doc->addField('ip', $r->ip);
        $doc->addField('data_id', $r->data_id);
        $doc->addField('content', $r->content);
        $doc->addField('status', $r->content);
        $doc->addField('channel_id', $r->channel_id);
        $doc->addField('created_at', $r->create_at);
        return $doc;
    }


    /**
     * @param $r
     * @return SolrInputDocument
     */
    public static function newActivitySignupDocument(&$r) {
        $doc = new SolrInputDocument();
        $doc->addField('id', $r->id);
        $doc->addField('channel_id', $r->channel_id);
        $doc->addField('activity_id', $r->activity_id);
        $doc->addField('mobile', $r->mobile);
        $doc->addField('author_name', $r->name);
        $doc->addField('user_id', $r->user_id);
        $doc->addField('user_name', $r->user_name);
        $doc->addField('create_at', $r->create_at);
        $doc->addField('status', $r->status);
        $doc->addField('ext_field1', $r->ext_field1);
        $doc->addField('ext_field2', $r->ext_field2);
        $doc->addField('ext_fields', $r->ext_fields);
        $doc->addField('ext_values', $r->ext_values);
        return $doc;
    }

}