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

namespace app\common\logic;

use think\Db;

/**
 * 视频逻辑
 */
class Video extends LogicBase
{
    
    /**
     * 视频分类编辑
     */
    public function videoCategoryEdit($data = [])
    {
        
        $validate_result = $this->validateVideoCategory->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateVideoCategory->getError()];
        }
        
        $url = url('videoCategoryList');
        
        $result = $this->modelVideoCategory->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '视频分类' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelVideoCategory->getError()];
    }
    
    /**
     * 获取视频列表
     */
    public function getVideoList($where = [], $field = 'a.*,c.name as category_name', $order = '')
    {
        
        $this->modelVideo->alias('a');
        
        $join = [
                    [SYS_DB_PREFIX . 'video_category c', 'a.category_id = c.id'],
                ];
        
        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $this->modelVideo->join = $join;
        
        $list = $this->modelVideo->getList($where, $field, $order);
        
        foreach ($list as &$info)
        {
            $info['cover_url'] = DOMAIN . get_picture_url($info['cover_id']);
            $info['file_url']  = DOMAIN . get_file_url($info['file_id']);
        }
        
        return $list;
    }
    
    /**
     * 获取视频播放记录列表
     */
    public function getPlayLogList()
    {
        
        $member_id  = request()->member['id'];
        
        $this->modelVideoPlayLog->alias('vpl');
        
        $join = [
                    [SYS_DB_PREFIX . 'video v', 'vpl.video_id = v.id'],
                ];
        
        $where['vpl.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $this->modelVideoPlayLog->join = $join;
        
        $where['vpl.member_id'] = $member_id;
        
        $list = $this->modelVideoPlayLog->getList($where, 'vpl.*,v.name,v.cover_id,v.file_id,v.id as vid,v.describe', 'vpl.create_time desc');
        
        foreach ($list as &$info)
        {
            $info['cover_url'] = DOMAIN . get_picture_url($info['cover_id']);
            $info['file_url']  = DOMAIN . get_file_url($info['file_id']);
        }
        
        return $list;
    }
    
    /**
     * 获取视频列表搜索条件
     */
    public function getWhere($data = [])
    {
        
        $where = [];
        
        !empty($data['search_data']) && $where['a.name'] = ['like', '%'.$data['search_data'].'%'];
        
        return $where;
    }
    
    /**
     * 视频信息编辑
     */
    public function videoEdit($data = [])
    {
        
        $validate_result = $this->validateVideo->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateVideo->getError()];
        }
        
        $url = url('videoList');
        
        $result = $this->modelVideo->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '视频' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '视频操作成功', $url] : [RESULT_ERROR, $this->modelVideo->getError()];
    }

    /**
     * 获取视频信息
     */
    public function getVideoInfo($where = [], $field = 'a.*,c.name as category_name')
    {
        
        $this->modelVideo->alias('a');
        
        $join = [
                    [SYS_DB_PREFIX . 'video_category c', 'a.category_id = c.id'],
                ];
        
        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $this->modelVideo->join = $join;
        
        return $this->modelVideo->getInfo($where, $field);
    }
    
    /**
     * 获取分类信息
     */
    public function getVideoCategoryInfo($where = [], $field = true)
    {
        
        return $this->modelVideoCategory->getInfo($where, $field);
    }
    
    /**
     * 获取视频分类列表
     */
    public function getVideoCategoryList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        $list = $this->modelVideoCategory->getList($where, $field, $order, $paginate);
        
        foreach ($list as &$info)
        {
            $info['cover_url'] = DOMAIN . get_picture_url($info['cover_id']);
        }
        
        return $list;
    }
    
    /**
     * 视频分类删除
     */
    public function videoCategoryDel($where = [])
    {
        
        $result = $this->modelVideoCategory->deleteInfo($where);
        
        $result && action_log('删除', '视频分类删除，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '视频分类删除成功'] : [RESULT_ERROR, $this->modelVideoCategory->getError()];
    }
    
    /**
     * 视频删除
     */
    public function videoDel($where = [])
    {
        
        $result = $this->modelVideo->deleteInfo($where);
        
        $result && action_log('删除', '视频删除，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '视频删除成功'] : [RESULT_ERROR, $this->modelVideo->getError()];
    }
    
    /**
     * 设置视频播放记录
     */
    public function setPlayLog($data = [])
    {
        
        $member_id  = request()->member['id'];
        $vid        = $data['vid'];
        
        $info = Db::name('video_play_log')->where(['video_id' => $vid, 'member_id' => $member_id])->field(true)->find();
        
        $func = function() use ($member_id, $vid, $info){
        
                        Db::name('video')->where(['id' => $vid])->setInc('play_number');

                        if (empty($info)) {

                            $this->modelVideoPlayLog->setInfo(['video_id' => $vid, 'member_id' => $member_id]);

                        } else {

                            $this->modelVideoPlayLog->setInfo(['video_id' => $vid, 'member_id' => $member_id, 'id' => $info['id'], 'create_time' => time()]);
                        }
                };
        
        $result = closure_list_exe([$func]);
        
        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $this->modelVideo->getError()];
    }
}
