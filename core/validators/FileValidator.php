<?php
namespace core\validators;
//use Yii;
//use yii\helpers\FileHelper;
//use yii\helpers\Html;
//use yii\helpers\Json;
//use yii\helpers\StringHelper;
//use yii\web\JsExpression;
//use yii\web\UploadedFile;
class FileValidator extends Validator {
    public $extensions;
    public $checkExtensionByMimeType = true;
    public $mimeTypes;
    public $minSize;
    public $maxSize;
    public $maxFiles                 = 1;
    public $minFiles                 = 0;
    public $message;
    public $uploadRequired;
    public $tooBig;
    public $tooSmall;
    public $tooMany;
    public $tooFew;
    public $wrongExtension;
    public $wrongMimeType;
    public function init() {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', 'File upload failed.');
        }
        if ($this->uploadRequired === null) {
            $this->uploadRequired = Yii::t('yii', 'Please upload a file.');
        }
        if ($this->tooMany === null) {
            $this->tooMany = Yii::t('yii', 'You can upload at most {limit, number} {limit, plural, one{file} other{files}}.');
        }
        if ($this->tooFew === null) {
            $this->tooFew = Yii::t('yii', 'You should upload at least {limit, number} {limit, plural, one{file} other{files}}.');
        }
        if ($this->wrongExtension === null) {
            $this->wrongExtension = Yii::t('yii', 'Only files with these extensions are allowed: {extensions}.');
        }
        if ($this->tooBig === null) {
            $this->tooBig = Yii::t('yii', 'The file "{file}" is too big. Its size cannot exceed {formattedLimit}.');
        }
        if ($this->tooSmall === null) {
            $this->tooSmall = Yii::t('yii', 'The file "{file}" is too small. Its size cannot be smaller than {formattedLimit}.');
        }
        if (!is_array($this->extensions)) {
            $this->extensions = preg_split('/[\s,]+/', strtolower($this->extensions), -1, PREG_SPLIT_NO_EMPTY);
        }
        else {
            $this->extensions = array_map('strtolower', $this->extensions);
        }
        if ($this->wrongMimeType === null) {
            $this->wrongMimeType = Yii::t('yii', 'Only files with these MIME types are allowed: {mimeTypes}.');
        }
        if (!is_array($this->mimeTypes)) {
            $this->mimeTypes = preg_split('/[\s,]+/', strtolower($this->mimeTypes), -1, PREG_SPLIT_NO_EMPTY);
        }
        else {
            $this->mimeTypes = array_map('strtolower', $this->mimeTypes);
        }
    }
    public function validateAttribute($model, $attribute) {
        if ($this->maxFiles != 1 || $this->minFiles > 1) {
            $rawFiles = $model->$attribute;
            if (!is_array($rawFiles)) {
                $this->addError($model, $attribute, $this->uploadRequired);

                return;
            }

            $files             = $this->filterFiles($rawFiles);
            $model->$attribute = $files;

            if (empty($files)) {
                $this->addError($model, $attribute, $this->uploadRequired);

                return;
            }

            $filesCount = count($files);
            if ($this->maxFiles && $filesCount > $this->maxFiles) {
                $this->addError($model, $attribute, $this->tooMany, ['limit' => $this->maxFiles]);
            }

            if ($this->minFiles && $this->minFiles > $filesCount) {
                $this->addError($model, $attribute, $this->tooFew, ['limit' => $this->minFiles]);
            }

            foreach ($files as $file) {
                $result = $this->validateValue($file);
                if (!empty($result)) {
                    $this->addError($model, $attribute, $result[0], $result[1]);
                }
            }
        }
        else {
            $result = $this->validateValue($model->$attribute);
            if (!empty($result)) {
                $this->addError($model, $attribute, $result[0], $result[1]);
            }
        }
    }
    private function filterFiles(array $files) {
        $result = [];

        foreach ($files as $fileName => $file) {
            if ($file instanceof UploadedFile && $file->error !== UPLOAD_ERR_NO_FILE) {
                $result[$fileName] = $file;
            }
        }

        return $result;
    }
    protected function validateValue($value) {
        if (!$value instanceof UploadedFile || $value->error == UPLOAD_ERR_NO_FILE) {
            return [$this->uploadRequired, []];
        }

        switch ($value->error) {
            case UPLOAD_ERR_OK:
                if ($this->maxSize !== null && $value->size > $this->getSizeLimit()) {
                    return [
                        $this->tooBig,
                            [
                            'file'           => $value->name,
                            'limit'          => $this->getSizeLimit(),
                            'formattedLimit' => Yii::$app->formatter->asShortSize($this->getSizeLimit()),
                        ],
                    ];
                }
                elseif ($this->minSize !== null && $value->size < $this->minSize) {
                    return [
                        $this->tooSmall,
                            [
                            'file'           => $value->name,
                            'limit'          => $this->minSize,
                            'formattedLimit' => Yii::$app->formatter->asShortSize($this->minSize),
                        ],
                    ];
                }
                elseif (!empty($this->extensions) && !$this->validateExtension($value)) {
                    return [$this->wrongExtension, ['file' => $value->name, 'extensions' => implode(', ', $this->extensions)]];
                }
                elseif (!empty($this->mimeTypes) && !$this->validateMimeType($value)) {
                    return [$this->wrongMimeType, ['file' => $value->name, 'mimeTypes' => implode(', ', $this->mimeTypes)]];
                }

                return null;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return [$this->tooBig, [
                        'file'           => $value->name,
                        'limit'          => $this->getSizeLimit(),
                        'formattedLimit' => Yii::$app->formatter->asShortSize($this->getSizeLimit()),
                ]];
            case UPLOAD_ERR_PARTIAL:
                Yii::warning('File was only partially uploaded: ' . $value->name, __METHOD__);
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                Yii::warning('Missing the temporary folder to store the uploaded file: ' . $value->name, __METHOD__);
                break;
            case UPLOAD_ERR_CANT_WRITE:
                Yii::warning('Failed to write the uploaded file to disk: ' . $value->name, __METHOD__);
                break;
            case UPLOAD_ERR_EXTENSION:
                Yii::warning('File upload was stopped by some PHP extension: ' . $value->name, __METHOD__);
                break;
            default:
                break;
        }

        return [$this->message, []];
    }
    public function getSizeLimit() {
        // Get the lowest between post_max_size and upload_max_filesize, log a warning if the first is < than the latter
        $limit     = $this->sizeToBytes(ini_get('upload_max_filesize'));
        $postLimit = $this->sizeToBytes(ini_get('post_max_size'));
        if ($postLimit > 0 && $postLimit < $limit) {
            Yii::warning('PHP.ini\'s \'post_max_size\' is less than \'upload_max_filesize\'.', __METHOD__);
            $limit = $postLimit;
        }
        if ($this->maxSize !== null && $limit > 0 && $this->maxSize < $limit) {
            $limit = $this->maxSize;
        }
        if (isset($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE'] > 0 && $_POST['MAX_FILE_SIZE'] < $limit) {
            $limit = (int) $_POST['MAX_FILE_SIZE'];
        }

        return $limit;
    }
    public function isEmpty($value, $trim = false) {
        $value = is_array($value) ? reset($value) : $value;
        return !($value instanceof UploadedFile) || $value->error == UPLOAD_ERR_NO_FILE;
    }
    private function sizeToBytes($sizeStr) {
        switch (substr($sizeStr, -1)) {
            case 'M':
            case 'm':
                return (int) $sizeStr * 1048576;
            case 'K':
            case 'k':
                return (int) $sizeStr * 1024;
            case 'G':
            case 'g':
                return (int) $sizeStr * 1073741824;
            default:
                return (int) $sizeStr;
        }
    }
    protected function validateExtension($file) {
        $extension = mb_strtolower($file->extension, 'UTF-8');

        if ($this->checkExtensionByMimeType) {
            $mimeType = FileHelper::getMimeType($file->tempName, null, false);
            if ($mimeType === null) {
                return false;
            }

            $extensionsByMimeType = FileHelper::getExtensionsByMimeType($mimeType);

            if (!in_array($extension, $extensionsByMimeType, true)) {
                return false;
            }
        }

        if (!empty($this->extensions)) {
            foreach ((array) $this->extensions as $ext) {
                if (StringHelper::endsWith($file->name, ".$ext", false)) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        return 'yii.validation.file(attribute, messages, ' . Json::encode($options) . ');';
    }
    public function getClientOptions($model, $attribute) {
        $label = $model->getAttributeLabel($attribute);
        $options = [];
        if ($this->message !== null) {
            $options['message'] = $this->formatMessage($this->message, [
                'attribute' => $label,
            ]);
        }
        if ($this->mimeTypes !== null) {
            $mimeTypes = [];
            foreach ($this->mimeTypes as $mimeType) {
                $mimeTypes[] = new JsExpression(Html::escapeJsRegularExpression($this->buildMimeTypeRegexp($mimeType)));
            }
            $options['mimeTypes']     = $mimeTypes;
            $options['wrongMimeType'] = $this->formatMessage($this->wrongMimeType, [
                'attribute' => $label,
                'mimeTypes' => implode(', ', $this->mimeTypes),
            ]);
        }
        if ($this->extensions !== null) {
            $options['extensions']     = $this->extensions;
            $options['wrongExtension'] = $this->formatMessage($this->wrongExtension, [
                'attribute'  => $label,
                'extensions' => implode(', ', $this->extensions),
            ]);
        }
        if ($this->minSize !== null) {
            $options['minSize']  = $this->minSize;
            $options['tooSmall'] = $this->formatMessage($this->tooSmall, [
                'attribute'      => $label,
                'limit'          => $this->minSize,
                'formattedLimit' => Yii::$app->formatter->asShortSize($this->minSize),
            ]);
        }
        if ($this->maxSize !== null) {
            $options['maxSize'] = $this->maxSize;
            $options['tooBig']  = $this->formatMessage($this->tooBig, [
                'attribute'      => $label,
                'limit'          => $this->getSizeLimit(),
                'formattedLimit' => Yii::$app->formatter->asShortSize($this->getSizeLimit()),
            ]);
        }
        if ($this->maxFiles !== null) {
            $options['maxFiles'] = $this->maxFiles;
            $options['tooMany']  = $this->formatMessage($this->tooMany, [
                'attribute' => $label,
                'limit'     => $this->maxFiles,
            ]);
        }
        return $options;
    }
    private function buildMimeTypeRegexp($mask) {
        return '/^' . str_replace('\*', '.*', preg_quote($mask, '/')) . '$/i';
    }
    protected function validateMimeType($file) {
        $fileMimeType = $this->getMimeTypeByFile($file->tempName);
        foreach ($this->mimeTypes as $mimeType) {
            if (strcasecmp($mimeType, $fileMimeType) === 0) {
                return true;
            }
            if (strpos($mimeType, '*') !== false && preg_match($this->buildMimeTypeRegexp($mimeType), $fileMimeType)) {
                return true;
            }
        }
        return false;
    }
    protected function getMimeTypeByFile($filePath) {
        return FileHelper::getMimeType($filePath);
    }
}