<?php

use \GenialCloud\Exceptions\HttpException;

class IndexController extends BaseController {

    protected $domain_id = 0;

    protected function defaultDomainCheck($host) {
        if($_SERVER['REQUEST_URI']=='/index.php?module=xianghunet&controller=m&action=ver&type=2&v=1.6.9') {
            $xhver = array('success'=>true,'data'=>array('ver'=>'1.7.0','title'=>"萧山手机台",'desc'=>"修复了一些bug",'link'=>'http://o.cztvcloud.com/6/apk/2016/09/29/387abbdd1597b768b3c2f81441099080.apk'));
            ob_clean();
            echo json_encode($xhver);
            exit;
        }
        $this->host = $host;
		$sybtv_domain = "www.sybtv.com";
		if(in_array($host, array('www.sybtv.com.cn', 'www.sybtv.cn'))) {
		    $host = $sybtv_domain;
            $this->host = $host;
		}
		
		$cncico_domain = "cncico.cztvcloud.com";
		if(in_array($host, array('www.cncico.com', 'cncico.com', 'vvvv.cncico.com'))) {
		    $host = $cncico_domain;
            $this->host = $host;
		}

        $ztv5_domain = "ztv-5.cztvcloud.com";
        if(in_array($host, array('www.ztv-5.com', 'ztv5.cztvcloud.com'))) {
            $host = $ztv5_domain;
            $this->host = $host;
        }

        header('Cache-Control: max-age=60');
        $this->domain = Domains::tplByDomainAndType($host, 'frontend');
        if(!$this->domain) {
            abort(403);
        }
        return true;
    }

    public function indexAction() {
        preg_match ("/(\/news2015\/view\/[0-9]*)/i", $_GET['_url'], $matches);
        View::disable();
        if(count($matches)==2) {
            $uri = $matches[1];
        }
        else {
            $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        }
        $domain = $this->domain;
        list($templates, $page_templates, $error_template) = Templates::tplNoneStatic($domain->id);
        $this->domain_id = $domain_id = $domain->id;
        $channel_id = $domain->channel_id;
        $page = 1;
        if(preg_match('!page_(\d+)(/)?!', $uri, $matches)) {
            $uri = str_replace($matches[0], '', $uri);
            $page = (int)$matches[1];
        }
        $allows = [
            'SmartyData' => [
                'url',
                'getNews', 'getNewsContent', 'getAlbum', 'getVideo', 'getVideoCollection',
                'getLatest', 'getLatestWithSort',
                'getLatestByCode', 'getLatestByCodeWithSort',
                'getLatestInIds', 'getLatestWithSortInIds',
                'getLatestInCodes', 'getLatestWithSortInCodes',
                'getRegionLatest', 'getRegionLatestWithSort',
                'getRegionLatestByCode', 'getRegionLatestByCodeWithSort',
                'getCategory', 'getCategoryByCode', 'getCategoryBreadcrumbs',
                'getSubCategory','getSubCategorySort', 'getSubRegion', 'getRegion',
                'getBlockByCode',
                'getSpecial', 'getSpecialDataByCode', 'getSpecialDataById','getOneChannel',
                'getDataList',
                'getMediaTypeValue',
                'getFeature',
                'listStations', 'getEpgs', 'getPrograms', 'getNewid'
            ],
        ];
        $smarty = new CZTVSmarty($domain_id, $channel_id, $allows, $page, $templates);
        list($template, $data_id, $data_type, $category_id, $region_id) = $this->fetchTemplate($templates, $page_templates, $uri);
        $code = 500;
        $message = 'Internal Server Error';
        $output = '';
        $html = '';
        try {
            if(!$template) {
                throw new HttpException(404);
            }
            $smarty->assign(compact('data_id', 'data_type', 'category_id', 'region_id'));
            $smarty->display('mem:'.$template['path']);
        } catch(SmartyCompilerException $e) {
            $output = '模板出错: ' . $e->getMessage();
        } catch(HttpException $e) {
            $code = $e->getCode();
            $message = $output = $e->getMessage();
            if($error_template) {
                try {
                    $html = $smarty->display('mem:'.$error_template['path']);
                } catch (Exception $e) {
                    $output = '错误页模板: ' . $e->getMessage();
                }
            }
        } catch(Exception $e) {
            $output = 'Error: ' . $e->getMessage();
        }
        if($output) {
            header('HTTP/1.1 '.$code.' '.$message);
            echo $html;
            if(Config::get('debug', false)) {
                echo '<!-- ', $output, ' -->';
            }
        }
        if(Config::get('debug', false)) {
            debug_sql($this);
        }
    }

    public function fetchTemplate(&$templates, &$page_templates, $uri) {
        $template = null;
        //系统级匹配
        $data_id = 0;
        $data_type = '';
        $category_id = 0;
        $region_id = 0;
        //首页
        if($uri == '/') {
            if(!isset($page_templates['/'])) {
                abort(404);
            }
            $template = $page_templates['/'];
            $data_type = 'index';
            return [$template, $data_id, $data_type, $category_id, $region_id];
        }
        //page 类页面
        if(isset($page_templates[$uri])) {
            $template = $page_templates[$uri];
            $data_type = 'page';
            return [$template, $data_id, $data_type, $category_id, $region_id];
        }
        $groups = explode('/', ltrim($uri, '/'));
        $group = '';
        if(isset($groups[0]) && $groups[0]) {
            $group = $groups[0];
        }
        if(isset($templates['groups'][$group])) {
            $templates = $templates['groups'][$group];
        } else {
            $templates = $templates['main'];
        }
        $ranges = Templates::getTypeRanges();
        $details = range($ranges['detail'][0], $ranges['detail'][1]);
        //详情优先
        foreach($details as $detail) {
            if(isset($templates[$detail])) {
                $t = $templates[$detail];
                if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $data_id = (int)$matches[1];
                    $template = $t;
                    $type_codes = [
                        Templates::TPL_DETAIL_NEWS => 'news',
                        Templates::TPL_DETAIL_ALBUM => 'album',
                        Templates::TPL_DETAIL_VIDEO => 'video',
                        Templates::TPL_DETAIL_VIDEO_COLLECTION => 'video_collection',
                        Templates::TPL_DETAIL_SPECIAL => 'special',
                    ];
                    $data_type = $type_codes[$detail];
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }

        $ugcs = range($ranges['ugc'][0], $ranges['ugc'][1]);
        //ugc直播
        foreach($ugcs as $ugc) {
            if(isset($templates[$ugc])) {
                $t = $templates[$ugc];
                if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $data_id = (int)$matches[1];
                    $template = $t;
                    $type_codes = [
                        Templates::TPL_UGC_VIDEO => 'ugc_video',
                        Templates::TPL_UGC_SIGNAL => 'ugc_signal',
                    ];
                    $data_type = $type_codes[$ugc];
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }


        //其次自定义
        $template = Templates::fetchWithFriendUrl($this->domain_id, $uri);
        if($template) {
            $category_id = $template['category_id'];
            $data_id = $template['data_id'];
            $region_id = $template['region_id'];
            return [$template, $data_id, $data_type, $category_id, $region_id];
        }
        //匹配通用分类
        $categories = range($ranges['category'][0], $ranges['category'][1]);
        foreach($categories as $category) {
            if(isset($templates[$category])) {
                $t = $templates[$category];
                if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $category_id = (int)$matches[1];
                    $template = $t;
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }
        //地区
        $region = $ranges['region'][0];
        if(isset($templates[$region])) {
            $t = $templates[$region];
            if(preg_match('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                $region_id = (int)$matches[1];
                $template = $t;
                return [$template, $data_id, $data_type, $category_id, $region_id];
            }
        }
        //匹配通用分类
        $categories = range($ranges['region_category'][0], $ranges['region_category'][1]);
        foreach($categories as $category) {
            if(isset($templates[$category])) {
                $t = $templates[$category];
                if(preg_match_all('!^'.$t['url_pattern'].'!', $uri, $matches)) {
                    $cpos = strpos($t['url_rules'], '{category_id}');
                    $rpos = strpos($t['url_rules'], '{region_id}');
                    if($cpos < $rpos) {
                        $cpos = 1;
                        $rpos = 2;
                    } else {
                        $cpos = 2;
                        $rpos = 1;
                    }
                    $category_id = (int)$matches[$cpos][0];
                    $region_id = (int)$matches[$rpos][0];
                    $template = $t;
                    return [$template, $data_id, $data_type, $category_id, $region_id];
                }
            }
        }
        return [$template, $data_id, $data_type, $category_id, $region_id];
    }

    public function errorAction() {
        echo 'Http error.';
    }

}