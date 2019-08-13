#!/bin/bash
ps -ef | grep -v grep | grep -q queuex
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run queuex push   >>queue_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q "push_msg\smain"
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run push_msg main   >>push_msg_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q "cdn_yff\sinsert"
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run cdn_yff insert   >>/data/cdnyff_insert_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q "cdn_yff\spush"
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run cdn_yff push   >>/data/cdnyff_push_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q "cdn_yff\squeueinsert"
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run cdn_yff queueinsert   >>/data/cdnyff_queueinsert_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q user_comment_queue
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run user_comment_queue main   >>user_comment_queue_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q "push_msg\spushprograms"
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run push_msg pushprograms   >>push_msg_pushprograms_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q updateset
if [ $? -ne 0 ];then
     cd /data/cms; nohup ./run updateset update   >>updateset_log.txt 2>&1 &
fi
ps -ef | grep -v grep | grep -q activity_vote_queue
if [ $? -ne 0 ];then
     cd /data/cms; ./run activity_vote_queue main   >>log.txt 2>&1 &
fi