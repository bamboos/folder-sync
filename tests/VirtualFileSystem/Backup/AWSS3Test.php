<?php

namespace App\VirtualFileSystem\AWSS3;

use App\Service\AWS\AWSConnector;
use App\VirtualFileSystem\Backup\AWSS3;
use App\VirtualFileSystem\Directory;
use App\VirtualFileSystem\File;
use App\VirtualFileSystem\FileContent;
use App\VirtualFileSystem\FileSystem;
use Codeception\Stub\Expected;

class AWSS3Test extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    private FileSystem $fileSystem;

    private AWSS3 $backup;

    public function _before()
    {
        $root = new Directory('/');
        $dir1 = new Directory('dir1');
        $dir2 = new Directory('dir2');
        $root->appendFile($dir1);
        $dir1->appendFile($dir2);
        $dir2->appendFile(new File('filename.txt', 'uri'));

        $this->fileSystem = new FileSystem(
            $this->makeEmpty(FileContent::class, [
                'isAccessible' => true,
                'extractName' => 'filename.txt',
                'retrieve' => 'file content'
            ])
        );

        $this->fileSystem->setRoot($root);
    }

    public function _after()
    {

    }

    public function testStoreFileSystem()
    {
        $paths = [
            ['/vfs_backup_.{19}\//', ''],
            ['/vfs_backup_.{19}\/dir1\/dir2\/filename.txt/', 'file content'],
            ['/vfs_backup_.{19}\/dir1\/dir2\//', ''],
            ['/vfs_backup_.{19}\/dir1\//', ''],
        ];

        $this->backup = new AWSS3($this->make(
            AWSConnector::class, [
                'putObject' => Expected::exactly(4, function (
                    string $fileName,
                    string $contents
                ) use ($paths) {
                    static $run = 0;

                    $this->assertMatchesRegularExpression(
                        $paths[$run][0],
                        $fileName
                    );

                    $this->assertEquals($paths[$run][1], $contents);

                    $run++;
                }),
            ]
        ));

        $this->specify('Store file system in AWS S3', function () {
            $this->backup->store($this->fileSystem);
        });
    }
}
