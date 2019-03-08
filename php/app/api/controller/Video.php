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

namespace app\api\controller;

/**
 * 视频接口控制器
 */
class Video extends ApiBase
{
    
    /**
     * 视频分类接口
     */
    public function categoryList()
    {
        
        return $this->apiReturn($this->logicVideo->getVideoCategoryList( [], true, '', false));
    }
    
    /**
     * 视频列表接口
     */
    public function videoList()
    {
        
        $where = [];
        
        if (!empty($this->param['cid'])) {
            
            $where['a.category_id'] = $this->param['cid'];
        }
        
        return $this->apiReturn($this->logicVideo->getVideoList($where, 'a.*,c.name as category_name', 'a.is_recommend desc,a.play_number desc,a.create_time desc'));
    }
    
    /**
     * 最新视频列表接口
     */
    public function newVideoList()
    {
        
        return $this->apiReturn($this->logicVideo->getVideoList([], 'a.*,c.name as category_name', 'a.create_time desc'));
    }
    
    /**
     * 推荐视频列表接口
     */
    public function recommendVideoList()
    {
        
        return $this->apiReturn($this->logicVideo->getVideoList(['is_recommend' => 1], 'a.*,c.name as category_name', 'a.play_number desc,a.create_time desc'));
    }
    
    /**
     * 视频播放记录列表接口
     */
    public function playLogList()
    {
        
        return $this->apiReturn($this->logicVideo->getPlayLogList($this->param));
    }
    
    /**
     * 设置视频播放记录
     */
    public function setPlayLog()
    {
        
        return $this->apiReturn($this->logicVideo->setPlayLog($this->param));
    }
}
