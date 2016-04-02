<?php
namespace app\usercenter\controller\goods;

use app\usercenter\controller\Common;
use think\Input;
use think\Session;
use org\Upload;
use org\Page;

class Image extends Common
{
    public function manager()
    {
        // echo "<form method='post' action='upload' enctype='multipart/form-data'>
        // <input type='file' name='image'/>
        // <input type='submit'/>
        // </form>";exit;
        $user_id                = Session::get('user_id');
        $map                    = [];
        $map['user_id']         = $user_id;
        $list                   = D('UserImages')->field('id,save_path as path')->where($map)->select();
        return json_encode($list);
    }

    public function upload()
    {
        //上限檢測
        $user_id                = Session::get('user_id');
        $return_type            = Input::get('type');
        $count                  = D('UserImages')->where("user_id={$user_id}")->count();
        if ($count>=100) {
            $return_info        = ['code'=>401,'msg'=>'上傳圖片數量超過限制，請管理圖檔集'];
            if ($return_type) {
                return "<script>window.parent.onUploadSuccess(".json_encode($return_info).");</script>";
            }else{
                return json_encode($return_info);
            }
        }
        $file                   = Input::file();
        //加載上傳類
        $config                 = [
            'maxSize'   => 1024*1024*3,     // 上传的文件大小限制 (0-不做限制)
            'rootPath'  => './upload/',     // 保存根路径
            'savePath'  => 'goods/',          // 保存根路径
        ];
        $Upload                 = new Upload($config);
        $info                   = $Upload->uploadOne($file['image']);
        if (!$info) {
            $return_info        = ['code'=>401,'msg'=>$Upload->getError()];
        }else{
            //入庫
            $data               = [];
            $data['user_id']    = $user_id;
            $data['save_path']  = $info['savepath'].$info['savename'];
            $data['save_name']  = $info['savename'];
            $insert_id          = D('UserImages')->add($data);
            if (!$insert_id) {
                $return_info    = ['code'=>401,'msg'=>'上傳失敗，請聯繫客服'];
            }else{
                $return_info        = ['code'=>200,'msg'=>'上傳成功','url'=>$data['save_path'],'id'=>$insert_id];
            }
        }
        if ($return_type) {
            return "<script>window.parent.onUploadSuccess(".json_encode($return_info).");</script>";
        }else{
            return json_encode($return_info);
        }
    }

    public function delete()
    {
        //刪除圖片
        $id                     = Input::get('id');
        $user_id                = Session::get('user_id');
        $image_info             = D('UserImages')->find($id);
        if ($user_id!=$image_info['user_id']) {
            $info               = ['code'=>401,'msg'=>'不能刪除不屬於您的圖片'];
        }else{
            $res                = D('UserImages')->delete($id);
            if ($res===false) {
                $info           = ['code'=>401,'msg'=>'刪除失敗'];
            }else{
                $info           = ['code'=>200,'msg'=>'刪除成功'];
            }
        }
        return json_encode($info);
    }
}