#!/bin/bash
ps -ef | grep -v grep | grep -q ugc_video
if [ $? -ne 0 ];then
    cd /data/cms; nohup ./run ugc_video "do" >> /dev/null 2>&1 &
fi
