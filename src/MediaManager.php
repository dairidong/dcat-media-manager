<?php

namespace Jatdung\MediaManager;

use Dcat\Admin\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File as FileFacade;
use Jatdung\MediaManager\Actions\Delete;
use Jatdung\MediaManager\Actions\Move;
use Jatdung\MediaManager\Actions\Detail;
use Jatdung\MediaManager\Concerns\HasAddressBar;
use Jatdung\MediaManager\Concerns\HasPanel;
use Jatdung\MediaManager\Concerns\HasSwitchDisk;
use Jatdung\MediaManager\Concerns\HasTools;
use Jatdung\MediaManager\Files\Audio;
use Jatdung\MediaManager\Files\Code;
use Jatdung\MediaManager\Files\Directory;
use Jatdung\MediaManager\Files\File;
use Jatdung\MediaManager\Files\Image;
use Jatdung\MediaManager\Files\Pdf;
use Jatdung\MediaManager\Files\Ppt;
use Jatdung\MediaManager\Files\Text;
use Jatdung\MediaManager\Files\Video;
use Jatdung\MediaManager\Files\Word;
use Jatdung\MediaManager\Files\Zip;
use Jatdung\MediaManager\Tools\FileSelector;
use Jatdung\MediaManager\Widgets\MoveFileModal;
use Jatdung\MediaManager\Widgets\DetailModal;
use League\Flysystem\StorageAttributes;

class MediaManager
{
    use HasTools, HasPanel, HasAddressBar, HasSwitchDisk;

    protected $view = 'jatdung.media-manger::index';

    /**
     * @var FileSelector
     */
    protected $fileSelector;

    /**
     * @var array<string, mixed>
     */
    protected $options = [
        'toolbar' => true,
        'address_bar' => true,
        'switch_disk' => true,
        'show_hidden_files' => false,
        'enable_batch_actions' => true,
    ];

    /**
     * @var array<string,string>
     */
    protected $fileTypes = [
        Image::class => 'png|jpg|jpeg|tmp|gif',
        Word::class => 'doc|docx',
        Ppt::class => 'ppt|pptx',
        Pdf::class => 'pdf',
        Code::class => 'php|js|java|python|ruby|go|c|cpp|sql|m|h|json|html|aspx',
        Zip::class => 'zip|tar\.gz|rar|rpm',
        Text::class => 'txt|pac|log|md',
        Audio::class => 'mp3|wav|flac|3pg|aa|aac|ape|au|m4a|mpc|ogg',
        Video::class => 'mkv|rmvb|flv|mp4|avi|wmv|rm|asf|mpeg',
    ];

    /**
     * @var Collection
     */
    protected $files;

    /**
     * @param MediaService $service
     */
    public function __construct(protected MediaService $service)
    {
        $this->files = new Collection();

        $this->option('show_hidden_files', $service->isShowHiddenFiles());
        $this->setUpTools();
        $this->setUpPanel();
        $this->setUpAddressBar();
        $this->setUpSwitchDisk();
    }

    /**
     * Get or set option for grid.
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this|mixed
     */
    public function option($key, $value = null)
    {
        if (is_null($value)) {
            return $this->options[$key] ?? null;
        }

        if (is_array($key)) {
            $this->options = array_merge($this->options, $key);
        } else {
            $this->options[$key] = $value;
        }

        return $this;
    }

    /**
     * @return string
     *
     * @throws Exceptions\DriverException
     */
    public function disk()
    {
        return $this->service->disk();
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->service->path();
    }

    /**
     * @return MediaService
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @return bool
     */
    public function isSupportNewFolder()
    {
        return $this->service->adapter()->supportMakeDirectory();
    }

    /**
     * @return bool
     */
    public function isSupportMoveFolder()
    {
        return $this->service->adapter()->supportMoveDirectory();
    }

    public function isEnableBatchActions()
    {
        return (bool)$this->option('enable_batch_actions');
    }

    public function hideHiddenFiles(bool $value = true)
    {
        return $this->option('show_hidden_files', !$value);
    }

    public function showHiddenFiles(bool $value = true)
    {
        return $this->hideHiddenFiles(!$value);
    }

    protected function buildFiles()
    {
        $attributes = $this->service->ls();

        $directories = [];
        $files = [];

        /** @var StorageAttributes $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->isDir()) {
                $directories[] = Directory::make($attribute, $this);
            } else {
                $fileType = $this->detectFileType($attribute->path());
                $files[] = $fileType ? new $fileType($attribute, $this) : File::make($attribute, $this);
            }
        }

        $this->files = $this->files->concat($directories)->concat($files);
    }

    protected function appendDefaultActions()
    {
        $this->files->each(function (File $file) {
            if (!($file instanceof Directory) || $this->isSupportMoveFolder()) {
                $file->appendAction(Move::make());
            }

            $file->appendAction(Delete::make());
            $file->appendAction(Detail::make());
        });
    }

    protected function renderFileSelectors()
    {
        if (!$this->isEnableBatchActions()) {
            return;
        }
        $this->files->each(function (File $file) {
            $file->setCheckbox($this->fileSelector()->renderFileCheckbox($file));
        });
    }

    protected function detectFileType($file)
    {
        $extension = FileFacade::extension($file);

        foreach ($this->fileTypes as $type => $regex) {
            if (preg_match("/^($regex)$/i", $extension) !== 0) {
                return $type;
            }
        }

        return false;
    }

    public function fileSelector()
    {
        return $this->fileSelector ?: ($this->fileSelector = new FileSelector($this));
    }

    public function files()
    {
        return $this->files;
    }

    public function render()
    {
        $this->buildFiles();
        $this->appendDefaultActions();
        $this->renderFileSelectors();

        MoveFileModal::make('.file-move-btn')->render();
        DetailModal::make('.file-detail-btn')->render();

        return Admin::view('jatdung.media-manager::index', [
            'manager' => $this,
        ]);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
