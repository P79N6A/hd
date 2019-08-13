<?php

class StationTask extends Task {

    //每分钟一次
    public function initAction() {
        foreach(self::$stations as $s){
            $stations = Stations::query()->andCondition('code',$s[3])->first();
            if(!$stations){
                $model = new Stations();
                $id = $model->saveGetId(array_combine($model->metaData()[0],$s));
                foreach(self::$stations_epg as $e){
                    $epg = new StationsEpg();
                    if($s[3]==$e[1]){
                        $e[1] = $id;
                        $epg->save(array_combine($epg->metaData()[0],$e));
                        echo 'epg',$e[1],$e[2],$e[3],PHP_EOL;
                    }
                }
                echo 'station',$s[3],PHP_EOL;
            }
        }
    }

    public static $stations = array(
        array('1', '0','0','101','浙江卫视','1','http://img01.cztv.com/channel/test199/1/ac_logo_5b563c7f8b3c464afb1a15fa935de55c.png', 'lantian','channel01',''),
        array('2', '0','0','102','钱江频道','1','http://img01.cztv.com/channel/test199/1/ac_logo_2684841b622ae80c5088044ea2de73eb.png', 'lantian','channel12',''),
        array('3', '0','0','103','浙江经视','1','http://img01.cztv.com/channel/test199/1/ac_logo_8b8919531888f83a59730a1c1993b18c.png', 'lantian','channel11',''),
        array('4', '0','0','104','教育科技','1','http://img01.cztv.com/channel/test199/1/ac_logo_ce3fcded1ae1aa8528427c37f9fcd11e.png', 'lantian','channel10',''),
        array('5', '0','0','105','浙江影视','1','http://img01.cztv.com/channel/test199/1/ac_logo_037ab586b8595561a2e9ab0e858b0ce5.png', 'lantian','channel09',''),
        array('6', '0','0','106','6频道','1','http://img01.cztv.com/channel/test199/1/ac_logo_2ae5661cbe834fe3b76d183cbd39bef1.png', 'lantian','channel08',''),
        array('7', '0','0','107','公共新农村','1','http://img01.cztv.com/channel/test199/1/ac_logo_4c6b2e253884d7b3218cafe82621ae2b.png', 'lantian','channel07',''),
        array('8', '0','0','108','浙江少儿','1','http://img01.cztv.com/channel/test199/1/ac_logo_b404522c43175ecbbb84d0d3a327cc3d.png', 'lantian','channel06',''),
        array('9', '0','0','109','留学世界','1','http://img01.cztv.com/channel/test199/1/ac_logo_e0e91142a6b58bf98e607e216d8eb59c.png', 'lantian','channel05',''),
        array('10', '0','0','110','浙江国际','1','http://img01.cztv.com/channel/test199/1/ac_logo_1929faf680b211f78a7b5f517cda54c4.png', 'lantian','channel04',''),
        array('11', '0','0','111','好易购','1','http://img01.cztv.com/channel/test199/1/ac_logo_4acd1dfdde118a15e68298a0ddb630c6.png', 'lantian','channel03',''),
        array('12', '0','0','113','浙江卫视HD','1','http://img01.cztv.com/channel/test199/1/ac_logo_f142089f76b8ab8e0fc59617c347775f.png', 'lantian','channel01',''),
        array('13', '0','0','1601','浙江之声','2','http://img01.cztv.com/channel/test199/1/ac_logo_ab38149c1223f81dce367c3f1fdb03aa.png', 'lantian','audio02',''),
        array('14', '0','0','1602','财富广播','2','http://img01.cztv.com/channel/test199/1/ac_logo_f8e6597a8059ee8784b2b1e435d84fb0.png', 'lantian','audio05',''),
        array('15', '0','0','1603','民生996','2','http://img01.cztv.com/channel/test199/1/ac_logo_907e31b1f8f6bc13ba5c91d7adc607d9.png', 'lantian','audio03',''),
        array('16', '0','0','1604','城市之声','2','http://img01.cztv.com/channel/test199/1/ac_logo_b536f8f3e9df369715f9afaa1d015e54.png', 'lantian','audio06',''),
        array('17', '0','0','1605','交通之声','2','http://img01.cztv.com/channel/test199/1/ac_logo_5ad8571ac18082d374a41e13f0614ee7.png', 'lantian','audio04',''),
        array('18', '0','0','1606','动听968','2','http://img01.cztv.com/channel/test199/1/ac_logo_06452435b81b088b4ff9e420ddd38dbb.png', 'lantian','audio01',''),
        array('19', '0','0','1607','女主播电台','2','http://img01.cztv.com/channel/test199/1/ac_logo_bb142cc0e4d3089e0d221839f8ca89cb.png', 'lantian','audio07',''),
        array('20', '0','0','1608','浙江新闻广播','2','http://signuphdcztv.oss-cn-beijing.aliyuncs.com/ac_logo_14b1be33b1d5eedb606f12f700f49cdb.jpg', 'lantian','audio08','www.cztv.com')
    );

    public static $stations_epg = array(
        array('1','101','360p','640','480','http://hls4.l.cztv.com','50','439','60','0'),
        array('2','101','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('3','101','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('4','102','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('5','102','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('6','102','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('7','103','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('8','103','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('9','103','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('10','104','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('11','104','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('12','104','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('13','105','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('14','105','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('15','105','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('16','106','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('17','106','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('18','106','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('19','107','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('20','107','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('21','107','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('22','108','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('23','108','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('24','108','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('25','109','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('26','109','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('27','109','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('28','110','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('29','110','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('30','110','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('31','111','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('32','111','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('33','111','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('34','1601','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('35','1602','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('36','1603','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('37','1604','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('38','1604','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('39','1605','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('40','1606','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('41','1606','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('42','1607','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('43','1608','128k','0','0','http://hls4.l.cztv.com','100','0','60','0'),
        array('44','112','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('45','112','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('46','112','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0'),
        array('47','113','360p','640','480','http://hls4.l.cztv.com','100','439','60','0'),
        array('48','113','540p','720','540','http://hls4.l.cztv.com','100','781','60','0'),
        array('49','113','720p','720','540','http://hls4.l.cztv.com','100','1465','60','0')
    );

}