<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 17/5/26
 * Time: 下午9:52
 */

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;

class UploadController extends BaseController
{
    public function image(Request $request)
    {
        $image = $request->file('image');
        //判断文件是否有效
        if (!($request->hasFile('image') && $image->isValid()))
            return ['message' => '该文件是无效的文件', 'status_code' => '-1'];

        $ext = $image->getClientOriginalExtension();
        if(in_array($ext,['png','PNG'])){
            $ext = 'jpg';
        }

        if(!in_array($ext,['jpg','jpeg','png','gif'])){
            return ['message' => '不支持上传该文件', 'status_code' => '-1'];
        }
        //获取文件大小
        $img_size = floor($image->getSize() / 1024) . 'KB';
        if ($img_size >= 2048) {
            return ['message' => '文件大小不能超过2M', 'status_code' => '-1'];
        }
        $upload_path = "./upload/" . date('Ymd') . '/';
        $host = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        $filename = date('His') . '-' . uniqid();
        //存储图片
        $request->file('image')->move($this->mk_dir($upload_path), $filename . '.' . $ext);
        $src_img_path = $upload_path . $filename . '.' . $ext;
        $des_img_path = $upload_path . $filename . '_small.' . $ext;
        //生成缩略图
        $slt = $this->CreateImage($src_img_path, $des_img_path, 120, 120);
        if ($slt) {
            return ['message' => '图片上传成功', 'status_code' => '200', 'data' => ['url' => $host.substr($src_img_path,1),'small_url'=>$host.substr($des_img_path,1)]];
        } else {
            return ['message' => '图片上传失败', 'status_code' => '-1'];
        }
    }

    /**
     * 创建图片（缩略图）
     * @param $SrcImageUrl
     * @param $DirImageUrl
     * @param $Width
     * @param $Height
     * @return bool
     */
    protected function CreateImage($SrcImageUrl, $DirImageUrl, $Width, $Height)
    {
        // 图片类型
        $type = substr(strrchr($SrcImageUrl, "."), 1);
        // 初始化图像
        if ($type == "jpg" || $type == "jpeg")
            $img = imagecreatefromjpeg($SrcImageUrl);
        if ($type == "gif")
            $img = imagecreatefromgif($SrcImageUrl);

        $srcw = imagesx($img);
        $srch = imagesy($img);
        if ($srcw / $srch > $Width / $Height) {
            if ($srcw > $Width) {
                $new_width = $Width;
                $new_height = $srch * ($Width / $srcw);
            } else {
                $new_width = $srcw;
                $new_height = $srch;
            }
        } else {
            if ($srch > $Height) {
                $new_height = $Height;
                $new_width = $srcw * ($Height / $srch);
            } else {
                $new_width = $srcw;
                $new_height = $srch;
            }
        }
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $img, 0, 0, 0, 0, $new_width, $new_height, $srcw, $srch);
        imagejpeg($new_image, $DirImageUrl);
        imagedestroy($img);
        imagedestroy($new_image);
        return true;
    }

    /**
     * 生成文件上传路径
     * @param $path
     * @return string
     */
    protected function mk_dir($path)
    {
        if (is_dir($path)) {
            return $path;
        } else {
            mkdir($path, 0777, true);
            return $path;
        }
    }
}