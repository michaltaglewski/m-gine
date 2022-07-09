<?php

declare(strict_types=1);

namespace mgine\log;

use mgine\log\Formatter AS LogFormatter;
use mgine\base\{Formatter,Component};
use mgine\helpers\FileHelper;

/**
 * Stream
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Stream extends Component
{
    public Formatter $formatter;

    public function __construct(public string $path)
    {
        $this->formatter = new LogFormatter();

        parent::__construct();
    }

    /**
     * @param Formatter $formatter
     * @return void
     */
    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param string $type
     * @param string $message
     * @return bool
     */
    public function save(string $type, string $message)
    {
        $this->formatter->setType($type);

        $filename = $this->getFilename();
        $rootPath = dirname($filename);

        FileHelper::makeDirIfNotExists($rootPath, recursive:true);

        return FileHelper::createFile($filename, $this->formatter->getMessage($message), true, FILE_APPEND);
    }

    /**
     * @return bool
     */
    public function purge(): bool
    {
        $filename = $this->getFilename();

        return unlink($filename);
    }

    /**
     * @return string
     */
    protected function getFilename()
    {
        return $this->basePath . $this->path;
    }
}