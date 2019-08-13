<?php

/**
 * @RoutePrefix("/message")
 */
class MessageController extends ApiBaseController {


    /**
     * @Get("/")
     */
    public function indexAction() {
        $type = $this->request->getQuery('type');
        $page = $this->request->getQuery('page');
        $per_page = $this->request->getQuery('per_page');
        $activities = Activity::getActivities($page, $per_page, $type);
        if (isset($activities)) {
            return $this->jsonResponse(404, Lang::_('not found'));
        } else {
            return $this->jsonResponse(200, Lang::_('success'), $activities->toArray());
        }
    }

    /**
     * @Get("/{id:[0-9]+}")
     */
    public function getAction($id) {
        $activity = Activity::findFirst($id);
        if (!$activity) {
            return $this->jsonResponse(404, Lang::_('not found'));
        } else {
            return $this->jsonResponse(200, Lang::_('success'), $activity);
        }
    }
}