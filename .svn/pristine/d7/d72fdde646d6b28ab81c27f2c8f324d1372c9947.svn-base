<?php

/**
 * @RoutePrefix("/menu")
 */
class MenuController extends ApiBaseController {

    // 部门的栏目ID
    static $department = 0;

    // 镇街道栏目ID
    static $town = 0;

    // 镇街道地区ID
    static $town_regin = 0;

    // 萧山大江东的栏目ID
    static $east = 0;

    // 临时存储分类数据
    static $cat;

    // 临时存储栏目树
    static $tree;

    static $appstyle;

    static $tushuotown = array("瓜沥镇" => "368",
"河上镇" => "369",
"所前镇" => "370",
"闻堰镇" => "371",
"党湾镇" => "372",
"楼塔镇" => "373",
"靖江街道" => "374",
"宁围镇" => "375",
"新塘街道" => "376",
"红山农场" => "377",
"南阳街道" => "378",
"益农镇" => "379",
"新街镇" => "380",
"蜀山街道" => "381",
"临浦镇" => "382",
"城厢街道" => "383",
"衙前镇" => "384",
"北干街道" => "385",
"河庄街道" => "386",
"义桥镇" => "387",
"进化镇" => "388",
"戴村镇" => "389",
"新湾街道" => "390",
"义蓬街道" => "391",
"浦阳镇" => "392",
"前进街道" => "393",
"临江街道" => "394",
        "空港经济区" => "394",
        "湘湖新城" => "394",
        "萧山经济开发区" => "394",
        "钱江世纪城" => "394"
);


    static $videotown = array("瓜沥镇" => "486",
        "河上镇" => "468",
        "所前镇" => "467",
        "闻堰镇" => "466",
        "党湾镇" => "464",
        "楼塔镇" => "463",
        "靖江街道" => "462",
        "宁围镇" => "465",
        "新塘街道" => "461",
        "红山农场" => "460",
        "南阳街道" => "469",
        "益农镇" => "470",
        "新街镇" => "471",
        "蜀山街道" => "485",
        "临浦镇" => "484",
        "城厢街道" => "483",
        "衙前镇" => "482",
        "北干街道" => "481",
        "河庄街道" => "480",
        "义桥镇" => "479",
        "进化镇" => "478",
        "戴村镇" => "477",
        "新湾街道" => "476",
        "义蓬街道" => "475",
        "浦阳镇" => "474",
        "前进街道" => "473",
        "临江街道" => "472",
        "空港经济区" => "472",
        "湘湖新城" => "472",
        "萧山经济开发区" => "472",
        "钱江世纪城" => "472"
    );

    static $recommendtoppic = array("北干街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend/beigan.jpg",
        "城厢街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend/chengxiang.jpg",
        "宁围镇" => "http://o.cztvcloud.com/6/xianghuimg/recommend/ningwei.jpg",
        "闻堰镇" => "http://o.cztvcloud.com/6/xianghuimg/recommend/wenyan.jpg",
        "临浦镇" => "http://o.cztvcloud.com/6/xianghuimg/recommend/linpu.jpg",
        "瓜沥镇" => "http://o.cztvcloud.com/6/xianghuimg/recommend/guali.jpg",
        "河庄街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend2/hezuang.jpg",
        "临江街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend2/linjiang.jpg",
        "前进街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend2/qianjin.jpg",
        "新湾街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend2/xinwan.jpg",
        "义蓬街道" => "http://o.cztvcloud.com/6/xianghuimg/recommend2/yipeng.jpg",
    );

    static $videodepartment = array("萧山区妇联" => "456",
        "萧山区纪委" => "455",
        "区委组织部" => "454",
        "环境保护局" => "453",
        "萧山团区委" => "452",
        "萧山供电局" => "451",
        "萧山老龄委" => "450",
        "红色讲坛" => "457",
        "党史研究室" => "449",
        "城市管理局" => "448",
        "城乡一体办" => "447",
        "民政局" => "446",
        "农村合作银行" => "445"
    );


    public function initialize(){
        parent::initialize();
        self::$appstyle = Category::listStyle();
        $this->initCategory();
    }

    protected function initCategory(){
        self::$cat = Category::listCategory('', false, $this->channel_id);
        self::$tree = Category::getTree('', $this->channel_id);
    }

    /**
     * @Get('/')
     */
    public function indexAction() {
        $data = Menu::apiGetMenu($this->channel_id);
        if(!empty($data)) {
            foreach($data as $k => $v) {
                $data[$k] = [
                    'name' => $v['name'],
                    'icon' => cdn_url('image', $v['icon']),
                    'type' => $v['type'],
                ];
                // 专门为萧山做的接口
                if($v['type']=='gov' && $this->channel_id==6) {
                    if ($v['menu_json']) {
                        $townmenu = json_decode($v['menu_json']);
                        self::$department = $townmenu->department->category_id;
                        self::$town = $townmenu->town->category_id;
                        self::$town_regin = $townmenu->town->region_id;
                    }
                    $data[$k]['sub'] = $this->parseGov($v);
                }else{
                    $data[$k]['sub'] = $this->parseSub($v);
                }
            }
        }
        $this->_json($data);
    }

    private function parseGov($v) {
        return [
            [
                'id'=>self::$department,
                'name'=>'部门',
                'logo'=>'',
                'sub'=>$this->parseDepartment()
            ],
            [
                'id'=>self::$town,
                'name'=>'镇街',
                'logo'=>'',
                'sub'=>$this->parseTown('镇街')
            ],
            [
                'id'=>self::$east,
                'name'=>'大江东',
                'logo'=>'',
                'sub'=>$this->parseTown('大江东')
            ]
        ];
    }

    private function parseDepartment(){
        $department = self::$tree->getChild(self::$department);
        if (!empty($department)) {
            foreach ($department as $d) {
                $v = self::$cat[$d];
                $recommend = [
                    '区委组织部','萧山区纪委',
                ];
                $return[] = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'logo' => cdn_url('image',$v['logo']),
                    'recommend' => (in_array($v['name'], $recommend))?1:0,
                    'sub'=>$this->parseDepartmentSub($v, self::$videodepartment[trim($v['name'])]),
                    'app_style' => self::$appstyle[$v['app_style']],
                ];


            }
        }
        return $return;
    }

    private function parseDepartmentSub($v, $video_cate_id) {

        $return = [];
        $columns = self::$tree->getChild($v['id']);
        if(!empty($columns)) {
            foreach($columns as $d) {
                $v = self::$cat[$d];
                if($v['app_style']==0) continue;
                $return[] = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'logo' => $v['logo'] ? cdn_url('image',$v['logo']) :'',
                    'app_style' => self::$appstyle[$v['app_style']],
                ];

                if($video_cate_id>0) {
                    $return[] = [
                        'id' => $video_cate_id,
                        'name' => "视讯",
                        'logo' => '',
                        'app_style' => self::$appstyle[Category::APP_STYLE_NEWS]
                    ];

                }
            }
        }
        return $return;
    }

    private function parseTown($regionname) {
        $return = [];
        $town = Regions::apiGetRegionsByFather(self::$town_regin);
        $config = [
            '河庄街道','新湾街道','义蓬街道','前进街道','临江街道',
        ];
        $recommend = [
            '北干街道','城厢街道','宁围镇','闻堰镇','临浦镇','瓜沥镇',
        ];
        if(!empty($town)) {
            foreach($town as $v) {
                if($regionname=='大江东' && in_array($v['name'], $config)) {
                    $return[] = [
                        'id' => $v['id'],
                        'name' => $v['name'],
                        'logo' => self::$recommendtoppic[$v['name']],
                        'recommend' => 1,
                        'sub'=>$this->parseTownSub($v['id'], self::$tushuotown[$v['name']], self::$videotown[$v['name']]),
                    ];                    
                }
                if($regionname=='镇街' && !in_array($v['name'], $config)) {
                    $return[] = [
                        'id' => $v['id'],
                        'name' => $v['name'],
                        'logo' => (in_array($v['name'], $recommend))?self::$recommendtoppic[$v['name']]:0,
                        'recommend' => (in_array($v['name'], $recommend))?1:0,
                        'sub'=>$this->parseTownSub($v['id'], self::$tushuotown[$v['name']], self::$videotown[$v['name']]),
                    ];
                }
            }
        }
        return $return;
    }

    private function parseTownSub($region_id, $tushuo_cate_id, $video_cate_id) {
        $return = [];
        $columns = self::$tree->getChild(self::$town);
        if(!empty($columns)) {
            foreach($columns as $d) {
                $v = self::$cat[$d];
                if($v['app_style']==0) continue;
                $return[] = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'logo' => $v['logo'] ? cdn_url('image',$v['logo']) :'',
                    'app_style' => self::$appstyle[$v['app_style']],
                    'region_id'=>$region_id,
                ];
            }
            if($tushuo_cate_id>0) {
                $return[] = [
                    'id' => $tushuo_cate_id,
                    'name' => "图说",
                    'logo' => '',
                    'app_style' => self::$appstyle[Category::APP_STYLE_PICTURE]
                ];
            }
            if($video_cate_id>0) {
                $return[] = [
                    'id' => $video_cate_id,
                    'name' => "视讯",
                    'logo' => '',
                    'app_style' => self::$appstyle[Category::APP_STYLE_VIDEO]
                ];

            }
        }
        return $return;
    }

    /**
     * 解析新闻类型
     * @param array $v
     * @return json
     */
    protected function parseSub($v) {
        $return = [];
        $id = $v['category_id'];
        if($v['type'] == 'url' && $v['url']) {
            return [[
                'url' => $v['url']
            ]];
        }
        if(!$id){
            return $return;
        }
        $seo = CategorySeo::apiListCategorySeo($this->channel_id);
        $list = self::$tree->getChild($id);
        if(empty($list)){
            $list[] = $id;
        }
        foreach($list as $k => $v) {
            $v = self::$cat[$v];
            $array = [
                'id' => $v['id'],
                'name' => $v['name'],
                'logo' => $v['logo'] ? cdn_url('image',$v['logo']) :'',
                'app_style' => self::$appstyle[$v['app_style']],
                'allow_comment' => $v['allow_comment'],
            ];
            if(isset($seo[$v['id']])) {
                $s = $seo[$v['id']];
                $array['intro'] = $s['intro'];
                $array['tips'] = $s['tips'];
            }
            $return[] = $array;
        }
        return $return;
    }

}