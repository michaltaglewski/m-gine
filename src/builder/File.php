<?php

namespace mgine\builder;

use mgine\base\FileAlreadyExistsException;
use mgine\helpers\FileHelper;

/**
 * Builder File
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class File
{
    /**
     * @var string
     */
    public string $basename;

    /**
     * @var string
     */
    public string $directory;

    /**
     * @var string
     */
    protected string $content;

    /**
     * @var string
     */
    private string $relativePath;

    /**
     * @param string $basename
     * @param string $content
     * @param string $directory
     */
    public function __construct(string $basename, string $content, string $directory)
    {
        $this->basename = $basename;
        $this->content = $content;
        $this->directory = $directory;

        $this->relativePath = '/' . $this->directory . '/' . $this->basename;
    }

    /**
     * @return string
     */
    public function getPathFromCurrentRoot(): string
    {
        return $this->relativePath;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $rootDirectory
     * @return bool
     * @throws FileAlreadyExistsException
     */
    public function save(string $rootDirectory): bool
    {
        $this->createDirectory($rootDirectory);
        $filename = $rootDirectory . $this->relativePath;

        if(is_file($filename)){
            throw new FileAlreadyExistsException('File already exists');
        }

        return FileHelper::createFile($filename, $this->content);
    }

    /**
     * @param string $rootDirectory
     * @return void
     */
    private function createDirectory(string $rootDirectory): void
    {
        FileHelper::makeDirIfNotExists($rootDirectory . '/' . $this->directory, recursive:true);
    }
}