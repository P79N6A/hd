<?php

class SearchController extends InteractionBaseController {

    public function indexAction() {
        $code = 200;
        $msg = 'OK';
        $data = [];
        $solr = $this->getDI()->getShared('solr.data');
        $origin_q = Request::get('q');
        $q = SolrEngine::f($origin_q);
        $d = trim(Request::get('d'));
        $from = trim(Request::get('from'));
        $p = Request::get('p', 'int', 1);
        $size = Request::get('size', 'int', 50);
        if(!$q || !$d || $p < 1) {
            $code = 403;
            $msg = Lang::_('invalid params');
            $this->jsonp(compact('code', 'msg', 'data'));
        }
        $domain = Domains::tplByDomainAndType($d, 'frontend', true);
        if(!$domain) {
            $code = 404;
            $msg = Lang::_('invalid domain');
            $this->jsonp(compact('code', 'msg', 'data'));
        }
        $data = SolrEngine::searchData($solr, $domain->channel_id, $q, $p);
		if($from) {
            $from2 = time()-$from*86400;
            $to=0;
            if($from=='2014'||$from=='2015'||$from=='2016'||$from=='2017') {
                $from2 = strtotime($from."-1-1");
                $to = $from2+86400*365;
            }
            $data = SolrEngine::searchData($solr, $domain->channel_id, $q, $p, $size, $from2, $to);
        }
        else {
            $data = SolrEngine::searchData($solr, $domain->channel_id, $q, $p, $size);
        }
        if(!empty($data['rs'])) {
            list($templates, $page_templates, $error_template) = Templates::tplNoneStatic($domain->id);
            SmartyData::init($domain->channel_id, $domain->id);
            SmartyData::initTemplates($templates);
            foreach($data['rs'] as $idx => $v) {
                $url = SmartyData::url(['data_id' => $v['id']], Templates::getMediaTypeValue($v['type']));
                $data['rs'][$idx]['url'] = $url;
            }
        }
        $this->jsonp(compact('code', 'msg', 'data'));
    }

}