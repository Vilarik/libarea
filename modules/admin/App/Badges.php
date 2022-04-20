<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, BadgeModel};
use Validation, UserData, Meta, Html;

class Badges
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // All awards
    // Все награды
    public function index($sheet, $type)
    {
        return view(
            '/view/default/badge/badges',
            [
                'meta'  => Meta::get(__('badges')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    // Form for adding an award
    // Форма добавления награды
    public function addPage($sheet, $type)
    {
        return view(
            '/view/default/badge/add',
            [
                'meta'  => Meta::get(sprintf(__('add.option'), __('badges'))),
                'data'  => [
                    'type'  => $type,
                    'sheet' => $sheet,
                ]
            ]
        );
    }

    // Reward change form 
    // Форма изменения награды
    public function editPage($sheet, $type)
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getId($badge_id);
        Html::pageError404($badge);

        return view(
            '/view/default/badge/edit',
            [
                'meta'  => Meta::get(__('edit')),
                'data'  => [
                    'badge' => $badge,
                    'sheet' => $sheet,
                    'type'  => $type,
                ]
            ]
        );
    }

    // Adding a reward 
    // Добавляем награду
    public function create()
    {
        $title         = Request::getPost('badge_title');
        $description   = Request::getPost('badge_description');
        $icon          = $_POST['badge_icon']; // для Markdown

        $redirect = getUrlByName('admin.badges');
        Validation::Length($title, __('title'), '4', '25', $redirect);
        Validation::Length($description, __('description'), '12', '250', $redirect);
        Validation::Length($icon, __('icon'), '12', '250', $redirect);

        BadgeModel::add(
            [
                'badge_title'       => $title,
                'badge_description' => $description,
                'badge_icon'        => $icon,
                'badge_tl'          => 0,
                'badge_score'       => 0,
            ]
        );

        redirect($redirect);
    }

    // Participant award form
    // Форма награждения участника
    public function addUserPage($sheet, $type)
    {
        $user_id    = Request::getInt('id');
        $user       = UserModel::getUser($user_id, 'id');

        return view(
            '/view/default/badge/user-add',
            [
                'meta'  => Meta::get(__('reward.user')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'user'      => $user ?? null,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    public function addUser()
    {
        $uid = Request::getPostInt('user_id');
        BadgeModel::badgeUserAdd(
            [
                'user_id'   => $uid,
                'badge_id'  => Request::getPostInt('badge_id')
            ]
        );

        Html::addMsg('successfully', 'success');

        redirect(getUrlByName('admin.user.edit', ['id' => $uid]));
    }

    public function edit()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getId($badge_id);

        $redirect = getUrlByName('admin.badges');
        if (!$badge['badge_id']) {
            redirect($redirect);
        }

        $title         = Request::getPost('badge_title');
        $description   = Request::getPost('badge_description');
        $icon          = $_POST['badge_icon']; // для Markdown

        Validation::Length($title, __('title'), '4', '25', $redirect);
        Validation::Length($description, __('description'), '12', '250', $redirect);
        Validation::Length($icon, __('icon'), '12', '250', $redirect);

        BadgeModel::edit(
            [
                'badge_id'          => $badge_id,
                'badge_title'       => $title,
                'badge_description' => $description,
                'badge_icon'        => $icon,
            ]
        );

        redirect($redirect);
    }

    public function remove()
    {
        $uid = Request::getPostInt('uid');
        BadgeModel::remove(
            [
                'bu_id'         => Request::getPostInt('id'),
                'bu_user_id'    => $uid,
            ]
        );

        Html::addMsg('command.executed', 'success');

        redirect('/admin/users/' . $uid . '/edit');
    }
}
