<?php


namespace Curl\StrService;

use Illuminate\Support\Facades\Storage;

class FileService
{

    /**
     * 生成文件名称
     * @return string
     */
    private function makeName(){
        $str = 'abcdefghijklmnopqrstuvwxyz123456789';
        return date('His').substr(str_shuffle($str),4,7);
    }


    /**
     * @param $file
     * @param $savePath
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function upload($files,$savePath)
    {

        if ($files) {

            $data = array();
            foreach ($files as $key => $value) {
                $fileArray = array();
                $saveName = $savePath .'/'. date('Y/m/d').'/'. $this->makeName() . '.' . $value->getClientOriginalExtension();

                $res = Storage::disk()->put($saveName,file_get_contents($value->getRealPath()));

                if ($res) {

                    $fileArray['path'] = $saveName;
                    $fileArray['url'] =  Storage::url($saveName);
                    $fileArray['name'] = str_replace('.' . $value->getClientOriginalExtension(), '', $value->getClientOriginalName());
                    $fileArray['size'] = $value->getClientSize();
                    $fileArray['ext'] = $value->getClientOriginalExtension();
                    if (strpos($value->getMimeType(), 'image') !== false) {

                        $fileArray['width'] = getimagesize($value->getRealPath())[0];
                        $fileArray['height'] = getimagesize($value->getRealPath())[1];
                    }

                    $data[] = $fileArray;
                }
            }
            return $data;

        }
        tne('FILE_NOT_EXIST');

    }
}