<?php

namespace MessageNotification\Trait;

trait HandleFileTrait
{
    public function fileSaveCheck(string $path)
    {
        if ($this->is_url($path)) { //是url文件
            //下载文件到本地
            [$path,$filename] = $this->saveFile($path); //获取本地文件
        } else {
            if (! is_file($path) || ! file_exists($path)) {
                throw new \Exception('无效文件');
            }
        }

        if (! $path) {
            throw new \Exception('无效文件');
        }
        $extension = strtolower($this->getFileNameExtension($path));

        return [$path, $filename, $extension];
    }
}
