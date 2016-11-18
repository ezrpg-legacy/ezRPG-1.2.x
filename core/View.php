<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/21/2016
 * Time: 9:19 PM
 */

namespace ezrpg\core;

class View{
    protected $container;
    protected $tpl;

    public function __construct($container)
    {
        $this->container = $container;
        $this->tpl = $this->container['tpl'] = new \Smarty();
        $this->tpl->addPluginsDir(CORE_DIR . '/ext/smarty');
        $this->tpl->caching = 0;
        $this->tpl->assign('GAMESETTINGS', $this->container['config']['app']);
        if ($this->container['config']['debug']['debug_mode']) {
            echo 'GAMESETTINGS Smarty Variable is being deprecated. Use {settings g=\'general\' n=\'Setting_Name\'} for your GameSettings needs.';
        }

        $this->tpl->addTemplateDir(array(
            'admin' => THEME_DIR . 'themes/admin/',
            'default' => THEME_DIR . 'themes/default/'
        ));

        $this->tpl->compile_dir = $this->tpl->cache_dir = CACHE_DIR . 'templates/';
    }
}