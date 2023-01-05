<?php

namespace Jatdung\MediaManager\Concerns;

use Jatdung\MediaManager\MediaManager;

trait HasManager
{
    /**
     * @var MediaManager
     */
    protected $manager;

    /**
     * @param  MediaManager  $manager
     * @return $this
     */
    public function setManager(MediaManager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return MediaManager|null
     *
     * @throws \Exception
     */
    public function manager()
    {
        if (is_null($this->manager)) {
            throw new \Exception('MediaManager is null.');
        }

        return $this->manager;
    }
}
