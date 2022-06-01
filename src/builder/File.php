<?php

namespace mgine\builder;

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
    public string $content;

    /**
     * @var string
     */
    public string $directory;

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
     * @param string $rootDirectory
     * @return bool
     */
    public function save(string $rootDirectory): bool
    {
        $this->createDirectory($rootDirectory);
        $filename = $rootDirectory . $this->relativePath;

        if(file_put_contents($filename, $this->content)){
            return true;
        }

        return false;
    }

    /**
     * @param string $rootDirectory
     * @return void
     */
    private function createDirectory(string $rootDirectory): void
    {
        $fileDirname = $rootDirectory . '/' . $this->directory;

        if(!is_dir($fileDirname)){
            mkdir($fileDirname, 0777, true);
        }
    }
}