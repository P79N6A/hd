<?php

/**
 * Class SolrTask
 *
 * Solr 索引
 */

use GenialCloud\Helper\IWC;

class SolrTask extends Task {


    public function dataAction(array $params) {
        $solr_name = 'solr.data';
        $cache_name = 'queue2016:'.$solr_name.':updated_at';
        $time = strtotime('2013-01-01 00:00:00');
        $query = Data::query();
        if(isset($params[0]) && $params[0] == 'update') {
            if($t = MemcacheIO::get($cache_name)) {
                $time = $t;
                if($time>time()) {
                    MemcacheIO::set($cache_name, time(), 86400*30);
                }
            }
            $query->andWhere('updated_at >'.$time.' AND Data.status = :status: AND Data.partition_by = '.date('Y', $time).' AND cd.publish_status = :publish_status:', ['status' => 1, 'publish_status'=>1])
                ->rightJoin('CategoryData', 'cd.data_id = Data.id AND cd.partition_by = '.date('Y', $time), 'cd')->limit(1000, 0);
        }
        $this->info('Fetch Time '.IWC::full($time));
        $rs = $query->execute();
        if(count($rs)) {
            $solr = $this->getDI()->getShared($solr_name);
            $i = 0;
            $docs = [];
            $to_be_deleted = [];
            $last_update_time =0;
            foreach($rs as $r) {
                $i ++;
                $this->info($r->id.': '.$r->title);
                if($r->status) {
                    $doc = SolrEngine::newDataDocument($r);
                    $docs[] = $doc;
                } else {
                    $to_be_deleted[] = $r->id;
                }
                $last_update_time = $r->updated_at;
            }
            MemcacheIO::set($cache_name, $last_update_time, 86400*30);
            if(!empty($docs)) {
                $solr->addDocuments($docs, true);
            }
            if(!empty($to_be_deleted)) {
                $solr->deleteByIds($to_be_deleted);
            }
            $solr->commit();
            $solr->optimize();
        } else {
            $this->info('Empty Data.');
        }
        $this->info('Updating Data Cursor. Date:'.date("Y-n-d H:i:s", $last_update_time).'.');
        $this->info('Done.');

    }

    public function alldataAction(array $params) {
        $solr_name = 'solr.data';
        $cache_name = 'queue2016:alldata:'.$solr_name.':updated_at';
        $time = strtotime('2013-05-20 00:00:00');
        $query = Data::query();
        if(isset($params[0]) && $params[0] == 'update') {
            if($t = MemcacheIO::get($cache_name)) {
                $time = $t;
            }
        //$time = strtotime('2013-05-20 00:00:00');
            $query->andWhere('updated_at >'.$time.' AND Data.type <> "album" AND Data.channel_id = :channel_id: AND Data.status = :status: AND cd.publish_status = :publish_status:', ['channel_id' => 184, 'status' => 1, 'publish_status'=>1])
                ->rightJoin('CategoryData', 'cd.data_id = Data.id', 'cd')->order("updated_at")->limit(1000, 0);
        }
        $this->info('Fetch Time '.IWC::full($time));
        $rs = $query->execute();
        if(count($rs)) {
            $solr = $this->getDI()->getShared($solr_name);
            $i = 0;
            $docs = [];
            $to_be_deleted = [];
            $last_update_time =0;
            foreach($rs as $r) {
                $i ++;
                $this->info($r->id.': '.$r->type.': '.$r->title);
                if($r->status) {
                    $doc = SolrEngine::newDataDocument($r);
                    $docs[] = $doc;
                } else {
                    $to_be_deleted[] = $r->id;
                }
                $last_update_time = $r->updated_at;
            }
            MemcacheIO::set($cache_name, $last_update_time, 86400*30);
            if(!empty($docs)) {
                $solr->addDocuments($docs, true);
            }
            if(!empty($to_be_deleted)) {
                $solr->deleteByIds($to_be_deleted);
            }
            $solr->commit();
            $solr->optimize();
        } else {
            $this->info('Empty Data.');
        }
        $this->info('Updating Data Cursor. Date:'.date("Y-n-d H:i:s", $last_update_time).'.');
        $this->info('Done.');

    }

    public function userAction(array $params) {
        $solr_name = 'solr.user';
        $cache_name = 'queue2016:'.$solr_name.':updated_at';
        $time = strtotime('2013-01-01 00:00:00');
        $query = Users::query();
        if(isset($params[0]) && $params[0] == 'update') {
            if($t = MemcacheIO::get($cache_name)) {
                $time = $t;
            }
            $query->andCondition('updated_at', '>', $time)->limit(1000, 0);
        }
        $this->info('Fetch Time '.IWC::full($time));
        $rs = $query->execute();
        if(count($rs)) {
            $solr = $this->getDI()->getShared($solr_name);
            $i = 0;
            $docs = [];
            $to_be_deleted = [];
            $last_update_time =0;
            foreach($rs as $r) {
                $i ++;
                $this->info($r->uid.': '.$r->nickname);
                if($r->status) {
                    $doc = SolrEngine::newUserDocument($r);
                    $docs[] = $doc;
                } else {
                    $to_be_deleted[] = $r->id;
                }
                $last_update_time = $r->updated_at;
            }
            MemcacheIO::set($cache_name, $last_update_time, 86400*30);
            if(!empty($docs)) {
                $solr->addDocuments($docs, true);
            }
            if(!empty($to_be_deleted)) {
                $solr->deleteByIds($to_be_deleted);
            }
            $solr->commit();
            $solr->optimize();
        } else {
            $this->info('Empty Data.');
        }
        $this->info('Updating Data Cursor. Date:'.date("Y-n-d H:i:s", $last_update_time).'.');
        $this->info('Done.');

    }

    public function commentAction(array $params) {
        $solr_name = 'solr.comment';
        $cache_name = 'queue2016:'.$solr_name.':updated_at';
        $time = strtotime('2013-01-01 00:00:00');
        $query = Comment::query();
        if(isset($params[0]) && $params[0] == 'update') {
            if($t = MemcacheIO::get($cache_name)) {
                $time = $t;
            }
            $query->andCondition('create_at', '>', $time)->limit(1000, 0);
        }
        $this->info('Fetch Time '.IWC::full($time));
        $rs = $query->execute();
        if(count($rs)) {
            $solr = $this->getDI()->getShared($solr_name);
            $i = 0;
            $docs = [];
            $to_be_deleted = [];
            $last_update_time =0;
            foreach($rs as $r) {
                $i ++;
                $this->info($r->comment_id.': '.$r->username);
                if($r->status) {
                    $doc = SolrEngine::newCommentDocument($r);
                    $docs[] = $doc;
                } else {
                    $to_be_deleted[] = $r->id;
                }
                $last_update_time = $r->updated_at;
            }
            MemcacheIO::set($cache_name, $last_update_time, 86400*30);
            if(!empty($docs)) {
                $solr->addDocuments($docs, true);
            }
            if(!empty($to_be_deleted)) {
                $solr->deleteByIds($to_be_deleted);
            }
            $solr->commit();
            $solr->optimize();
        } else {
            $this->info('Empty Data.');
        }
        $this->info('Updating Data Cursor. Date:'.date("Y-n-d H:i:s", $last_update_time).'.');

        $this->info('Done.');

    }

    public function activitysignupAction(array $params) {
        $solr_name = 'solr.activitysignup';
        $cache_name = 'queue2016:'.$solr_name.':updated_at';
        $time = strtotime('2013-01-01 00:00:00');
        $query = ActivitySignup::query();
        if(isset($params[0]) && $params[0] == 'update') {
            if($t = MemcacheIO::get($cache_name)) {
                $time = $t;
            }
            $query->andCondition('update_at', '>', $time)->limit(1000, 0);
        }
        $this->info('Fetch Time '.IWC::full($time));
        $rs = $query->execute();
        if(count($rs)) {
            $solr = $this->getDI()->getShared($solr_name);
            $i = 0;
            $docs = [];
            $to_be_deleted = [];
            $last_update_time =0;
            foreach($rs as $r) {
                $i ++;
                $this->info($r->id.': '.$r->name);
                if($r->status==1) {
                    $doc = SolrEngine::newActivitySignupDocument($r);
                    $docs[] = $doc;
                } else {
                    $to_be_deleted[] = $r->id;
                }
                $last_update_time = $r->update_at;
            }
            MemcacheIO::set($cache_name, $last_update_time, 86400*30);
            if(!empty($docs)) {
                $solr->addDocuments($docs, true);
            }
            if(!empty($to_be_deleted)) {
                $solr->deleteByIds($to_be_deleted);
            }
            $solr->commit();
            $solr->optimize();
        } else {
            $this->info('Empty Data.');
        }
        $this->info('Updating Data Cursor. Date:'.date("Y-n-d H:i:s", $time).'.');

        $this->info('Done.');

    }

}
