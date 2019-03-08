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

/**
 * 视频控制器
 */
class Video extends AdminBase
{
    
    /**
     * 视频列表
     */
    public function videoList()
    {
        
        $where = $this->logicVideo->getWhere($this->param);
        
        $this->assign('list', $this->logicVideo->getVideoList($where, 'a.*,c.name as category_name', 'a.create_time desc'));
        
        return $this->fetch('video_list');
    }
    
    /**
     * 视频添加
     */
    public function videoAdd()
    {
        
        $this->videoCommon();
        
        return $this->fetch('video_edit');
    }
    
    /**
     * 视频编辑
     */
    public function videoEdit()
    {
        
        $this->videoCommon();
        
        $info = $this->logicVideo->getVideoInfo(['a.id' => $this->param['id']], 'a.*,c.name as category_name');
        
        $this->assign('info', $info);
        
        return $this->fetch('video_edit');
    }
    
    /**
     * 视频添加与编辑通用方法
     */
    public function videoCommon()
    {
        
        IS_POST && $this->jump($this->logicVideo->videoEdit($this->param));
        
        $this->assign('video_category_list', $this->logicVideo->getVideoCategoryList([], true, '', false));
    }
    
    /**
     * 视频分类添加
     */
    public function videoCategoryAdd()
    {
        
        IS_POST && $this->jump($this->logicVideo->videoCategoryEdit($this->param));
        
        return $this->fetch('video_category_edit');
    }
    
    /**
     * 视频分类编辑
     */
    public function videoCategoryEdit()
    {
        
        IS_POST && $this->jump($this->logicVideo->videoCategoryEdit($this->param));
        
        $info = $this->logicVideo->getVideoCategoryInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('video_category_edit');
    }
    
    /**
     * 视频分类列表
     */
    public function videoCategoryList()
    {
        
        $this->assign('list', $this->logicVideo->getVideoCategoryList());
       
        return $this->fetch('video_category_list');
    }
    
    /**
     * 视频分类删除
     */
    public function videoCategoryDel($id = 0)
    {
        
        $this->jump($this->logicVideo->videoCategoryDel(['id' => $id]));
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('Video', $this->param));
    }
}
