<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{

    public $image;

    public function rules()
    {
        return [
          [['image'], 'required'],
          [['image'], 'file', 'extensions' => 'jpg,png']
        ];
    }

    public function uploadFile(UploadedFile $file, $currentImage)
    {
        $this->image = $file;

        if ($this->validate())
        {
            $this->deleteCurrentImage($currentImage);

            return $this->saveImage();
        }
    }

    private function getFolder()
    {
        return Yii::getAlias('@app/web') . '/uploads/';
    }

    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage)
    {
        if ($this->imageExist($currentImage))
        {
            unlink($this->getFolder() . $currentImage);
        }
    }

    private function imageExist($currentImage)
    {
        if (!empty($currentImage))
        {
            return file_exists($this->getFolder() . $currentImage);
        }
    }

    private function saveImage()
    {
        $filename = $this->generateFilename();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}