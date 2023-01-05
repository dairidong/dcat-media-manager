<?php

namespace Jatdung\MediaManager\Widgets;

use Dcat\Admin\Widgets\Card;
use Jatdung\MediaManager\Tools\Breadcrumb;
use Jatdung\MediaManager\Concerns\HasManager;
use Jatdung\MediaManager\Concerns\HasViews;
use Jatdung\MediaManager\MediaManager;

class Panel extends Card
{
    use HasManager, HasViews;

    protected $elementClass = ['mt-1'];

    /**
     * @var MediaManager
     */
    protected $manager;

    /**
     * @var Breadcrumb
     */
    protected $breadcrumb;

    public function __construct(MediaManager $manager)
    {
        $this->setManager($manager);
        $this->setUpDefaultViews();
        $this->setUpBreadcrumb();

        parent::__construct();
    }

    public function setUpBreadcrumb()
    {
        $this->breadcrumb = Breadcrumb::make()->setManager($this->manager());
    }

    public function renderBreadcrumb()
    {
        return $this->breadcrumb->render();
    }

    public function disableBreadcrumb(bool $disable = true)
    {
        $this->breadcrumb->disable($disable);

        return $this;
    }

    public function content($content)
    {
        return parent::content(view('jatdung.media-manager::_panel', [
            'manager' => $this->manager(),
        ]));
    }
}
