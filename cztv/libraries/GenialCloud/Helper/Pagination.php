<?php

namespace GenialCloud\Helper;

use Phalcon\Tag;
use \Request;
use GenialCloud\Core\Component;

class Pagination {

    use Component;

    protected $pageSize = 10;
    protected $itemCount = 0;
    protected $currentPage = 1;
    protected $maxPage = 0;
    protected $pagination;
    protected $nextLabel = '下一页';
    protected $firstLabel = '首页';
    protected $prevLabel = '上一页';
    protected $lastLabel = '末页';
    protected $maxButton = 6;
    protected $header = '<div class="pagination">';
    protected $footer = '</div>';
    protected $tagName = 'a';
    protected $defaultUrl = 'javascript:void(0)';
    protected $show = false;
    protected $activeClass = 'current';

    protected function init() {
        $this->maxPage = ceil($this->itemCount/$this->pageSize);
    }

    public function render($hide_when_can_not_jump=false) {
        if($hide_when_can_not_jump && $this->maxPage < 2) {
            return '';
        } else {
            $buttons = $this->createButtons();
            return $this->header . implode('', $buttons) . $this->footer;
        }
    }

    public function setPage($page, $itemCount, $pageSize = 10) {
        $this->pageSize = $pageSize;
        $this->itemCount = intval($itemCount);
        $this->maxPage = max(1, (int) ceil($this->itemCount / intval($pageSize)));
        $this->currentPage = min($this->maxPage, max(1, intval($page)));
    }

    public function getLimit() {
        return $this->pageSize * ($this->currentPage - 1) . ',' . $this->pageSize;
    }

    protected function createButton($params) {
        $doParams = array();
        foreach ($params as $k => $v) {
            if (!empty($v) && $k !== 'label') {
                $doParams[$k] = $v;
            } else {
                continue;
            }
        }
        return Tag::tagHtml($this->tagName, $doParams) . $params['label'] . Tag::tagHtmlClose($this->tagName);
    }

    protected function createButtons() {
        $buttons = array();
        $buttons[] = $this->createButton(array(
            'label' => $this->firstLabel,
            'title' => $this->firstLabel,
            'href' => $this->currentPage == 1 ? $this->defaultUrl : $this->createUrl(1),
        ));
        $buttons[] = $this->createButton(array(
            'label' => $this->prevLabel,
            'title' => $this->prevLabel,
            'href' => $this->currentPage == 1 ? $this->defaultUrl : $this->createUrl($this->currentPage - 1),
        ));

        for ($i = 1; $i <= $this->maxPage; $i++) {
            $buttons[] = $this->createButton(array(
                'label' => $i,
                'title' => '页 ' . $i,
                'href' => $this->currentPage == $i ? $this->defaultUrl : $this->createUrl($i),
                'class' => $this->currentPage == $i ? "current number" : 'number',
            ));
        }
        $buttons[] = $this->createButton(array(
            'label' => $this->nextLabel,
            'title' => $this->nextLabel,
            'href' => $this->maxPage == $this->currentPage ? $this->defaultUrl : $this->createUrl($this->currentPage + 1),
        ));
        $buttons[] = $this->createButton(array(
            'label' => $this->lastLabel,
            'title' => $this->lastLabel,
            'href' => $this->maxPage == $this->currentPage ? $this->defaultUrl : $this->createUrl($this->maxPage),
        ));
        return $buttons;
    }

    protected function createUrl($page) {
        $data = Request::get();
        if(isset($data['_url'])) {
            $uri = $data['_url'];
            unset($data['_url']);
        } else {
            $uri = '/';
        }
        $data['page'] = $page;
        return $uri . "?" . http_build_query($data);
    }

    protected function getPageRange() {
        $currentPage = $this->currentPage;
        $pageCount = $this->maxPage;
        $halfButtonCount = floor($this->maxButton / 2);
        $maxButton = $this->maxButton - $halfButtonCount;
        $beginPage = max(3, $currentPage - (int) ($maxButton / 2));
        if (($endPage = $beginPage + $maxButton - 1) >= $pageCount - 2) {
            $endPage = $pageCount - 2;
            $beginPage = max(3, $endPage - $maxButton + 1);
        }
        return array($beginPage, $endPage);
    }

}