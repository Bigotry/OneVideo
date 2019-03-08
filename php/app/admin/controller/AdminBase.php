<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\admin\controller;

use app\common\controller\ControllerBase;
use think\Hook;

/**
 * 后台基类控制器
 */
class AdminBase extends ControllerBase
{
    
    // 授权过的菜单列表
    protected $authMenuList     =   [];
    
    // 授权过的菜单url列表
    protected $authMenuUrlList  =   [];
    
    // 授权过的菜单树
    protected $authMenuTree     =   [];
    
    // 菜单视图
    protected $menuView         =   '';
    
    // 面包屑视图
    protected $crumbsView       =   '';
    
    // 页面标题
    protected $title            =   '';
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        
        // 执行父类构造方法
        parent::__construct();
        
        // 初始化后台模块常量
        $this->initAdminConst();
        
        // 初始化后台模块信息
        $this->initAdminInfo();
        
        // 后台控制器钩子
        Hook::listen('hook_controller_admin_base', $this->request);
    }
    
    /**
     * 初始化后台模块信息
     */
    final private function initAdminInfo()
    {
        
        // 验证登录
        !MEMBER_ID && $this->redirect('login/login');
        
        // 获取授权菜单列表
        $this->authMenuList = $this->logicAuthGroupAccess->getAuthMenuList(MEMBER_ID);
        
        // 获得权限菜单URL列表
        $this->authMenuUrlList = $this->logicAuthGroupAccess->getAuthMenuUrlList($this->authMenuList);
        
        // 检查菜单权限
        list($jump_type, $message) = $this->logicAdminBase->authCheck(URL, $this->authMenuUrlList);
        
        // 权限验证不通过则跳转提示
        RESULT_SUCCESS == $jump_type ?: $this->jump($jump_type, $message, url('index/index'));
        
        // 初始化基础数据
        IS_AJAX && !IS_PJAX ?: $this->initBaseInfo();
        
        // 若为PJAX则关闭布局
        IS_AJAX && $this->view->engine->layout(false);
    }
    
    /**
     * 初始化基础数据
     */
    final private function initBaseInfo()
    {
        
        // 获取过滤后的菜单树
        $this->authMenuTree = $this->logicAdminBase->getMenuTree($this->authMenuList, $this->authMenuUrlList);
       
        // 菜单转换为视图
        $this->menuView = $this->logicMenu->menuToView($this->authMenuTree);
        
        // 菜单自动选择
        $this->menuView = $this->logicMenu->selectMenu($this->menuView);
        
        // 获取面包屑
        $this->crumbsView = $this->logicMenu->getCrumbsView();
        
        // 获取默认标题
        $this->title = $this->logicMenu->getDefaultTitle();
        
        // 设置页面标题
        $this->assign('ob_title', $this->title);
        
        // 菜单视图
        $this->assign('menu_view', $this->menuView);
        
        // 面包屑视图
        $this->assign('crumbs_view', $this->crumbsView);
        
        // 授权菜单列表
        $this->assign('auth_menu_list', $this->authMenuList);
        
        // 登录会员信息
        $this->assign('member_info', session('member_info'));
    }
    
    /**
     * 初始化后台模块常量
     */
    final private function initAdminConst()
    {
        
        // 会员ID
        defined('MEMBER_ID')    or  define('MEMBER_ID',     is_login());
        
        // 是否为超级管理员
        defined('IS_ROOT')      or  define('IS_ROOT',       is_administrator());
    }
    
    /**
     * 设置指定标题
     */
    final protected function setTitle($title = '')
    {
        
        $this->assign('ob_title', $title);
    }
    
    /**
     * 获取内容头部视图
     */
    final protected function getContentHeader($describe = '')
    {
        
        $title           = empty($this->title) ? '' : $this->title;
        
        $describe_html   = empty($describe)    ? '' : '<small>' . $describe . '</small>';
        
        return "<section class='content-header'><input type='hidden' name='ob_title_hidden' id='ob_title_hidden' value='".$title."'/><h1>$title $describe_html</h1>$this->crumbsView</section>";
    }
    
    /**
     * 重写fetch方法
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        
        $content = parent::fetch($template, $vars, $replace, $config);
        
        IS_PJAX && $content = $this->getContentHeader() . $content;
        
        return $this->logicAdminBase->filter($content, $this->authMenuUrlList);
    }
}
