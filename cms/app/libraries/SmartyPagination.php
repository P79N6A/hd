<?php

use GenialCloud\Helper\Pagination as BasePagination;
use Phalcon\Tag;

class SmartyPagination extends BasePagination {

    protected $activeClass = 'active';
    protected $header = '<ul class="pagination">';
    protected $footer = '</ul>';
    protected $prevLabel = '<i class="fa fa-angle-left"></i>';
    protected $nextLabel = '<i class="fa fa-angle-right"></i>';
    protected $maxButton = 8;

    protected function createButton($params) {
        $label = $params['label'];
        unset($params['label']);
        if (isset($params['class'])) {
            $html = '<li class="' . $params['class'] . '">';
            unset($params['class']);
        } else {
            $html = '<li>';
        }
        $html .= Tag::tagHtml($this->tagName, $params) . $label . Tag::tagHtmlClose($this->tagName);
        $html .= '</li>';
        return $html;
    }

    protected function createButtons() {
        $activeClass = $this->activeClass;
        $buttons = array();
        $currentPage = $this->currentPage;
        $maxPage = $this->maxPage;

        list($begin, $end) = $this->getPageRange();

        $buttons[] = $this->createButton(array(
            'label' => $this->prevLabel,
            'title' => '上页',
            'href' => $currentPage == 1 ? $this->defaultUrl : $this->createUrl($currentPage - 1),
        ));

        //按钮总数的一半
        $halfButtonCount = floor($this->maxButton / 2);

        $t = $this;
        $buttonParams = function ($page) use ($t, $currentPage, $activeClass) {
            return [
                'label' => $page,
                'title' => '第 ' . $page . ' 页 ',
                'href' => $currentPage == $page ? $this->defaultUrl : $this->createUrl($page),
                'class' => $currentPage == $page ? $activeClass : '',
            ];
        };

        $buttons[] = $this->createButton($buttonParams(1));
        if ($maxPage >= 2) {
            $buttons[] = $this->createButton($buttonParams(2));
        }
        /**  第1，2个 * */
        if ($maxPage > $this->maxButton) {
            if ($currentPage >= $halfButtonCount + 2) {
                $buttons[] = $this->createButton(array(
                    'label' => '...',
                    'title' => '',
                    'href' => $this->defaultUrl,
                    'class' => '',
                ));
            }
        }

        for ($i = $begin; $i <= $end; $i++) {
            $buttons[] = $this->createButton($buttonParams($i));
        }

        /** 现在末尾2个 * */
        if ($maxPage > $this->maxButton) {
            if ($currentPage < $maxPage - 4) {
                $buttons[] = $this->createButton(array(
                    'label' => '...',
                    'title' => '',
                    'href' => $this->defaultUrl,
                    'class' => '',
                ));
            }
        }
        if ($maxPage > 2) {
            if ($maxPage > 3) {
                $buttons[] = $this->createButton($buttonParams($maxPage - 1));
            }
            $buttons[] = $this->createButton($buttonParams($maxPage));
        }

        $buttons[] = $this->createButton(array(
            'label' => $this->nextLabel,
            'title' => '下页',
            'href' => $this->maxPage <= $currentPage ? $this->defaultUrl : $this->createUrl($currentPage + 1),
        ));
        return $buttons;
    }

    protected function createUrl($page) {
        $data = Request::get();
        if (isset($data['_url'])) {
            $uri = $data['_url'];
            unset($data['_url']);
        } else {
            $uri = '/';
        }
        if (strrpos($uri, '/') != strlen($uri) - 1) {
            $uri .= '/';
        }
        if (preg_match('!page_(\d+)(/)?!', $uri, $matches)) {
            $uri = str_replace($matches[0], '', $uri);
        }
        unset($data['page']);
        $queries = http_build_query($data);
        $page_uri = '';
        if ($page > 1) {
            $page_uri = 'page_' . $page . '/';
        }
        return $uri . $page_uri . ($queries ? '?' . $queries : '');
    }

}