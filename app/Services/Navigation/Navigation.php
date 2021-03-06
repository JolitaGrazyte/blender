<?php

namespace App\Services\Navigation;

use HTML;
use Menu;
use Request;

class Navigation
{
    public function getFrontMainMenu()
    {
        $menu = Menu::handler('main', ['class' => 'nav navbar-nav'])
            ->add('/', 'Home');

        return $menu;
    }

    public function getLanguageMenu()
    {
        $menu = Menu::handler('language', ['class' => 'navigation']);

        foreach (config('app.locales') as $locale) {
            $menu->add('/'.$locale, $locale);
        }

        return $menu;
    }

    public function getBackLanguageMenu()
    {
        $menu = Menu::handler('backLanguage', ['class' => 'navigation']);

        foreach (config('app.backLocales') as $locale) {
            $menu->add('/'.$locale.'/auth/login', $locale);
        }

        $menu = $this->setActiveMenuItem($menu, function ($item) {
            return app()->getLocale() == explode('/', trim($item->getContent()->getUrl(), '/'))[0];
        });

        return $menu;
    }

    public function getBackContentMenu()
    {
        $menu = Menu::handler('backContent');
        $menu->add(action('Back\ArticleController@index', [], false), fragment('back.articles.title'), null, null, ['class'=>'menu_group_item']);
        $menu->add(action('Back\NewsItemController@index', [], false), fragment('back.newsItems.title'), null, null, ['class'=>'menu_group_item -secondary']);
        $menu->add(action('Back\PersonController@index', [], false), fragment('back.people.title'), null, null, ['class'=>'menu_group_item -secondary']);

        $menu = $this->setActiveMenuItem($menu, function ($item) {
            return str_replace('/blender/', '/', $item->getContent()->getUrl()) == ('/'.Request::segment(2));
        });

        return $menu;
    }

    public function getBackModuleMenu()
    {
        $menu = Menu::handler('backModule');
        $menu->add(action('Back\FragmentController@index', [], false), fragment('back.fragments.title'), null, null, ['class'=>'menu_group_item']);
        $menu->add(action('Back\FormResponseController@showDownloadButton', [], false), fragment('back.formResponses.title'), null, null, ['class'=>'menu_group_item -secondary']);
        $menu->add(action('Back\TagController@index', [], false), fragment('back.tags.title'), null, null, ['class'=>'menu_group_item -secondary']);

        $menu = $this->setActiveMenuItem($menu, function ($item) {
            return str_replace('/blender/', '/', $item->getContent()->getUrl()) == ('/'.Request::segment(2));
        });

        return $menu;
    }


    public function getBackServiceMenu()
    {
        $menu = Menu::handler('backService');

        $menu->add(action('Back\FrontUserController@index', [], false), fragment('back.frontUsers.title'), null, null, ['class'=>'menu_group_item']);
        $menu->add(action('Back\BackUserController@index', [], false), fragment('back.backUsers.title'), null, null, ['class'=>'menu_group_item']);
        $menu->add(action('Back\ActivitylogController@index', [], false), 'Log', null, null, ['class'=>'menu_group_item -secondary']);
        $menu->add(action('Back\StatisticsController@index', [], false), fragment('back.statistics.menuTitle'), null, null, ['class'=>'menu_group_item -secondary']);

        $menu = $this->setActiveMenuItem($menu, function ($item) {
            return str_replace('/blender/', '/', $item->getContent()->getUrl()) == ('/'.Request::segment(2));
        });

        return $menu->render();
    }

    public function getBackDashboardMenu()
    {
        $menu = Menu::handler('backNavigation');

        $menu->add(action('Back\DashboardController@index', [], false), 'Dashboard', null, null, ['class'=>'menu_group_item']);

        $menu = $this->setActiveMenuItem($menu, function ($item) {
            return str_replace('/blender/', '/', $item->getContent()->getUrl()) == ('/'.Request::segment(2));
        });

        return $menu->render();
    }

    public function getBackUserMenu()
    {
        $menu = Menu::handler('backUser');

        $menu->add(action('Back\BackUserController@edit', ['id' => auth()->user()->id], false), HTML::avatar(auth()->user(), '-small') . '<span class=":responsive-desktop-only">' . auth()->user()->email . '</span>', null, null);
        $menu->add(action('Back\AuthController@getLogout', [], false), '<span class="fa fa-power-off"></span>', null, ['class' => 'menu_circle -log-out', 'title' => 'log out']);

        $menu = $this->setActiveMenuItem($menu, function ($item) {
            return str_replace('/blender/', '/', $item->getContent()->getUrl()) == ('/'.Request::segment(2));
        });

        return $menu->render();
    }

    protected function setActiveMenuItem($menu, callable $activeTest)
    {
        $menu->getItemsByContentType('Menu\Items\Contents\Link')->map(function ($item) use ($activeTest) {
            if ($activeTest($item)) {
                $item->addClass('active');
            }
        });

        return $menu;
    }
}
