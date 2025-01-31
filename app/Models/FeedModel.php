<?php

namespace App\Models;

use UserData;
use DB;

class FeedModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function feed($page, $limit, $sheet, $slug, $topic = '')
    {
        $user_id    = UserData::getUserId();
        $string     = self::sorting($sheet);

        // Deleted post, banned from showing in the feed and limited by TL (trust_level)
        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $trust_level =  ($user_id == 0) ? "AND post_tl = 0" : "AND post_tl <= " . UserData::getUserTl();
        $display = "AND post_is_deleted = 0 $trust_level";

        // Sorting posts by conditions
        // Сортировка постов по условиям
        $sort = "ORDER BY post_answers_count DESC";
        if (in_array($sheet, ['facet.feed', 'web.feed', 'questions', 'posts'])) {
            $sort = "ORDER BY post_top DESC, post_date DESC";
        } elseif (in_array($sheet, ['admin.posts.all', 'admin.posts.ban', 'profile.posts'])) {
            $sort = "ORDER BY post_date DESC";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_feature,
                    post_translation,
                    post_draft,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_hits_count,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_ip,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    votes_post_item_id, votes_post_user_id,
                    u.id, u.login, u.avatar, u.created_at,
                    fav.tid, fav.user_id, fav.action_type
                    
                        FROM posts
                            LEFT JOIN
                            (
                                SELECT 
                                    relation_post_id,
                                    GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                    FROM facets      
                                    LEFT JOIN facets_posts_relation on facet_id = relation_facet_id 
                                        
                                    GROUP BY relation_post_id  
                            ) AS rel ON rel.relation_post_id = post_id 
                            
                            INNER JOIN users u ON u.id = post_user_id
                            LEFT JOIN favorites fav ON fav.tid = post_id AND fav.user_id = " . $user_id . " AND fav.action_type = 'post'
                            LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = " . $user_id . "
                                        
                                $string $display $sort LIMIT :start, :limit";

        if (in_array($sheet, ['facet.feed', 'recommend', 'questions', 'posts'])) {
            return DB::run($sql, ['qa' => "%" . $slug . "@%", 'start' => $start, 'limit' => $limit])->fetchAll();
        }

        if (in_array($sheet, ['profile.posts', 'web.feed'])) {
             return DB::run($sql, ['qa' => $slug, 'start' => $start, 'limit' => $limit])->fetchAll();
        }

        if ($sheet == 'facet.feed.topic') {
            return DB::run($sql, ['qa' => "%" . $slug . "@%", 'topic' => "%" . $topic . "@%", 'start' => $start, 'limit' => $limit])->fetchAll();
        }
    }

    public static function feedCount($sheet, $slug, $topic = '')
    {
        $string     = self::sorting($sheet);
        $user_id    = UserData::getUserId();

        $trust_level = ($user_id == 0) ? "AND post_tl = 0" : "AND post_tl <= " . UserData::getUserTl();
        $display = "AND post_is_deleted = 0 $trust_level";

        $sql = "SELECT 
                    post_id,
                    rel.*
                        FROM posts
                             LEFT JOIN
                        (
                            SELECT 
                                relation_post_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets      
                                    LEFT JOIN facets_posts_relation on facet_id = relation_facet_id 
                                        GROUP BY relation_post_id  
                        ) AS rel ON rel.relation_post_id = post_id 
                            $string $display";

        if (in_array($sheet, ['facet.feed', 'recommend', 'questions', 'posts'])) {
             return DB::run($sql, ['qa' => "%" . $slug . "@%"])->rowCount();
        }

        if (in_array($sheet, ['profile.posts', 'web.feed'])) {
             return DB::run($sql, ['qa' => $slug])->rowCount() ;
        }

        if ($sheet == 'facet.feed.topic') {
            return DB::run($sql, ['qa' => "%" . $slug . "@%", 'topic' => "%" . $topic . "@%"])->rowCount();
        }

        return;
    }

    public static function sorting($sheet)
    {
        switch ($sheet) {
            case 'facet.feed':
                $string     = "WHERE facet_list LIKE :qa AND post_draft = 0 AND post_type = 'post'";
                break;
            case 'facet.feed.topic':
                $string     = "WHERE facet_list LIKE :qa AND facet_list LIKE :topic AND post_draft = 0 AND post_type = 'post'";
                break;
            case 'questions':
                $string     = "WHERE facet_list LIKE :qa AND post_draft = 0 AND post_feature = 1";
                break;
            case 'posts':
                $string     = "WHERE facet_list LIKE :qa AND post_draft = 0 AND post_feature = 0";
                break;
            case 'recommend':
                $string     = "WHERE facet_list LIKE :qa AND post_is_recommend = 1 AND post_draft = 0 AND post_type = 'post'";
                break;
            case 'web.feed':
                $string     = "WHERE post_url_domain = :qa AND post_draft = 0";
                break;
            case 'profile.posts':
                $string     = "WHERE post_user_id  = :qa AND post_draft = 0 AND post_type = 'post'";
                break;
        }

        return $string;
    }
}
