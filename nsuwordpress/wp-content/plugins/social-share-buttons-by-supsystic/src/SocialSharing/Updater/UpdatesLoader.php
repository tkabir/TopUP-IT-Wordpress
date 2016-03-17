<?php


class SocialSharing_Updater_UpdatesLoader 
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path Path to the module.
     */
    public function __construct($path)
    {
        $this->path = untrailingslashit($path);
    }

    public function has($revision)
    {
        $file = $this->getFilePath($revision);
        return file_exists($file) && is_readable($file);
    }

    public function load($revision)
    {
        if (!$this->has($revision)) {
            return null;
        }

        return file_get_contents($this->getFilePath($revision));
    }

    protected function getFilePath($revision)
    {
        return $this->path.'/Updates/rev'.$revision.'.sql';
    }
}