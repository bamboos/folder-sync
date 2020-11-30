<?php

namespace App\VirtualFileSystem;

class FileSystemTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    private FileSystem $fileSystem;

    private Directory $directory;

    protected function _before()
    {
        $root = new Directory('/');
        $dir1 = new Directory('dir1');
        $dir13 = new Directory('dir13');
        $dir12 = new Directory('dir12');
        $dir11 = new Directory('dir11');
        $dir2 = new Directory('dir2');
        $root->appendFile($dir1);
        $root->appendFile($dir13);
        $root->appendFile($dir12);
        $root->appendFile($dir11);
        $dir1->appendFile($dir2);
        $dir2->appendFile(new File('filename.txt', 'uri'));
        $root->appendFile(new File('filename.txt', 'uri'));
        $root->appendFile(new File('some_file.txt', 'uri'));
        $root->appendFile(new File('wow_file.txt', 'uri'));
        $root->appendFile(new File('new_file.txt', 'uri'));
        $root->appendFile(new File('a_file.txt', 'uri'));

        $this->fileSystem = new FileSystem(
            $this->makeEmpty(FileContent::class, [
                'isAccessible' => true,
                'extractName' => 'filename.txt',
                'retrieve' => 'file content'
            ])
        );

        $this->fileSystem->setRoot($root);
    }

    protected function _after()
    {
        unset($this->fileSystem);
    }

    // tests
    public function testFileContent(): void
    {
        $this->specify('Given file path, return some content', function () {
            $contents = $this->fileSystem->getFileContents('/dir1/dir2/filename.txt');
            $this->assertEquals('file content', $contents);
        });

        $this->specify('No content for a folder', function () {
            $contents = $this->fileSystem->getFileContents('/dir1/dir2');
            $this->assertEmpty($contents);
        });

        $this->specify('No content when wrong path', function () {
            $contents = $this->fileSystem->getFileContents('/dir1/dir/filename.txt');
            $this->assertEmpty($contents);
        });
    }

    public function testChangeDir(): void
    {
        $this->specify('Open specified dir', function () {
            $this->fileSystem->changeDir('dir1');
            $this->assertEquals(
                'dir1',
                $this->fileSystem->getCurrentDir()->getName()
            );
        });

        $this->specify('Open parent dir', function () {
            $this->fileSystem->changeDir('dir1');
            $this->fileSystem->changeDir('dir2');
            $this->fileSystem->changeDir('..');
            $this->assertEquals(
                'dir1',
                $this->fileSystem->getCurrentDir()->getName()
            );
        });
    }

    public function sortedNames()
    {
        $sorted = [
            'dir1',
            'dir11',
            'dir12',
            'dir13',
            'a_file.txt',
            'filename.txt',
            'new_file.txt',
            'some_file.txt',
            'wow_file.txt',
        ];

        return [
            [$sorted, count($sorted)]
        ];
    }

    /**
     * @dataProvider sortedNames
     */
    public function testGetSortedContents(array $sortedNames, int $listCount): void
    {
        $this->specify(
            'Dir contents is sorted by name',
            function () use ($sortedNames, $listCount) {
                $sorted = $this->fileSystem->getSortedContents();
                $this->assertNotEmpty($sorted);

                $equals = 0;

                foreach ($sortedNames as $index => $name) {
                    $equals += $name === $sorted[$index]->getName() ? 1 : 0;
                }

                $this->assertEquals($listCount, $equals);
            }
        );
    }
}
