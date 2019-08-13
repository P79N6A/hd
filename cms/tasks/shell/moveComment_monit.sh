#!/bin/bash
ps -ef | grep -v grep | grep -q moveComment.php
if [ $? -ne 0 ];then
        /usr/local/php/bin/php /data/cms/tasks/shell/moveComment.php >>/data/log/moveCommentphp.log 2>&1 &
        echo "restart" >> /data/log/moveCommentphp.log
        date >> /data/log/moveCommentphp.log
fi
