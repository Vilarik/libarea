<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FeedModel};
use Agouti\{Content, Config, Base};

class WebController extends MainController
{
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $links      = WebModel::getLinksAll($page, $limit, $uid['user_id']);

        $result = array();
        foreach ($links as $ind => $row) {
            $text = explode("\n", $row['link_content']);
            $row['link_content']    = Content::text($text[0], 'line');
            $result[$ind]           = $row;
        }

        $num = $page > 1 ? sprintf(lang('page-number'), $page) : '';

        $meta = [
            'canonical'     => '/web',
            'sheet'         => 'domains',
            'meta_title'    => lang('domains-title') . ' ' . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('domains-desc') . ' ' . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'sheet'         => 'domains',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'links'         => $result,
            'domains'       => WebModel::getLinksTop('all'),
        ];

        return view('/web/links', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Посты по домену
    public function posts($sheet)
    {
        $domain     = Request::get('domain');
        $uid        = Base::getUid();
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $link       = WebModel::getLinkOne($domain, $uid['user_id']);
        Base::PageError404($link);
 
        $link['link_content'] = Content::text($link['link_content'], 'line');

        $limit      = 25;
        $data       = ['link_url_domain' => $link['link_url_domain']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'link', $data);
        $pagesCount = FeedModel::feedCount($uid, 'link', $data);

        $result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('answer'), lang('answers-m'), lang('answers'));
            $result[$ind]                   = $row;
        }

        $meta_title = lang('domain') . ': ' . $domain . ' | ' . Config::get(Config::PARAM_NAME);
        $meta_desc  = lang('domain-desc') . ': ' . $domain . ' ' . Config::get(Config::PARAM_HOME_TITLE);

        $meta = [  
            'canonical'     => Config::get(Config::PARAM_URL) . getUrlByName('domain', ['domain' => $domain]),
            'sheet'         => 'domain',
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
        ];

        $data = [
            'sheet'         => 'domain',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'posts'         => $result,
            'domains'       => WebModel::getLinksTop($domain),
            'link'          => $link
        ];

        return view('/web/link', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
