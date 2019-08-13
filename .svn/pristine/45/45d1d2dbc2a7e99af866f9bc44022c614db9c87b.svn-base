<?php

use CZTVPush\GeTui\Sender;

class QueuexTask extends Task {

    //每分钟一次
    public function pushAction() {
        $tasks = Queues::findUnprocessTasks(Queues::TASK_PUSH);
        if(!empty($tasks)) {
            foreach($tasks as $task) {
                if(!$this->doPush($task)) {
                    $this->warning("processing task ". $task->id. ' error.');
                }
            }
            $this->info('Done.');
        } else {
            $this->info('Empty push task list.');
        }
    }

    private function doPush(Queues &$task) {
        $r = false;
        $channel_id = $task->channel_id;
        $task_data = json_decode($task->task_data, true);
        $data = Data::getById($task_data['data_id'], $channel_id);
        $title = $task_data['data_title'];
        if($data) {
            $push = Setting::getByChannel($channel_id, 'getui.push');
            if($push) {

                $pushconfig = array(
                    'AppKey'=>$push['AppKey'],
                    'MasterSecret'=>$push['MasterSecret'],
                    'AppID'=>$push['AppID'],
                );
				$pushtype = "news";
				if($data->type=='album') $pushtype = "album";				
                $pushdata = [
                    'title' => $title,
                    'data' => ['push_news_type'=>$pushtype, 'push_news_id'=>$data->id, 'title' => $title],
                    'clientType' => $task->push_terminal,
                    'offlineExpireTime' => 3600 * 2 * 1000,			// 离线时间单位为毫秒
                ];
                if($task->single == 1 && strlen($task->push_single_client) > 0) {
                	// 个推
                	$singleClient = str_replace("，", ",", $task->push_single_client);
                	$pushClient = explode(',', $singleClient);
                	if(count($pushClient) > 0) {
                		$rep_return = F::getuiProxy($pushconfig, $pushdata, $pushClient[0]);
                	}
                }else {
                	// 全推
                	$rep_return = F::getuiProxy($pushconfig, $pushdata);
                }            
                echo $rep_return;
               	$repReturn = GeTuiTask::valReturn($rep_return);
               	$remark = "";
               	$status = Queues::STATUS_FAILED;
               	if(is_array($repReturn)){
               		foreach ($repReturn[0] as $v) {
               			$rep = json_decode($v,true);
               			if($rep != null) {
               				if($rep['result'] == 'ok'){
               					$status = Queues::STATUS_DONE;
               				}else {
               					$status = Queues::STATUS_FAILED;
               				}
               				$remark .= json_encode($v);
               			}
               		}
               	}
               	$remark = stripslashes($remark);
                if($status == Queues::STATUS_DONE) {
                    $task->done($remark);
                    $r = true;
                } else {
                    $task->failedFor($rep_return);
                }
            } else {
                $task->failedFor("Invalid Getui setting.");
            }
        } else {
            $task->failedFor('Data '.$task_data['data_id'].' not found');
        }
        return $r;
    }

    public function publishAction() {
        $tasks = Queues::getUnprocessedTasks(Queues::TASK_PUBLISH, 1);
        if(!empty($tasks)) {
            $tasks_group = [];
            foreach($tasks as $task) {
                $tasks_group[$task->channel_id][] = $task;
            }
            foreach($tasks_group as $channel_id => $_tasks) {
                $this->publishChannelTasks($channel_id, $_tasks);
            }
        } else {
            $this->info('Empty publish task list.');
        }
    }

    private function publishChannelTasks($channel_id, &$tasks) {
        $domains = Domains::findChannelDomains($channel_id);
        foreach($tasks as $task) {
            $this->publishTask($domains, $task);
        }
    }

    private function publishTask(&$domains, $task) {
        $data = json_decode($task->task_data, true);
        if(!empty($data)) {
            $data_id = $data['data_id'];
            //地区ID
            $region_ids = Regions::queByDataID($data_id);
            //分类ID
            $category_ids = Category::queByDataID($data_id);
            //专题ID
            $special_ids = Specials::queByDataID($data_id);
        } else {
            $this->warning('Invalid task_data(task id: '.$task['id'].')');
        }
    }
}