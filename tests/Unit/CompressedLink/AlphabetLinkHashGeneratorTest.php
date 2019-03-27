<?php

namespace Tests\Unit\CompressedLink;

use App\Links\Exceptions\ErrorGeneratingHash;
use App\Links\Generators\AlphabetLinkHashGenerator;
use Tests\TestCase;

class AlphabetLinkHashGeneratorTest extends TestCase
{
    /**
     * @var AlphabetLinkHashGenerator
     */
    private $linkHashGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->linkHashGenerator = app(AlphabetLinkHashGenerator::class);
    }

    public function testNumberToHash()
    {
        $number = 0;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $assertHash = str_repeat($this->linkHashGenerator::ALPHABET[0], $this->linkHashGenerator::SIZE);
        $this->assertEquals($assertHash, $hash);


        $number = $this->linkHashGenerator::SIZE - 1;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $this->assertEquals('aaaH', $hash);

        $number = $this->linkHashGenerator::SIZE;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $this->assertEquals('aaam', $hash);

        $number = $this->linkHashGenerator::SIZE + 1;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $this->assertEquals('aaaB', $hash);


        $number = $this->linkHashGenerator::SIZE * $this->linkHashGenerator::SIZE - 1;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $this->assertEquals('aaaM', $hash);

        $number = $this->linkHashGenerator::SIZE * $this->linkHashGenerator::SIZE;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $this->assertEquals('aaae', $hash);

        $number = $this->linkHashGenerator::SIZE * $this->linkHashGenerator::SIZE + 1;
        $hash = $this->linkHashGenerator->getHashByNumber($number);
        $this->assertEquals('aaaE', $hash);

        $number = '123145345643562345';
        $this->expectException(ErrorGeneratingHash::class);
        $this->linkHashGenerator->getHashByNumber($number);
    }

    public function testHashToNumber()
    {
        $hash = str_repeat($this->linkHashGenerator::ALPHABET[0], $this->linkHashGenerator::SIZE);
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = 0;
        $this->assertEquals($assertNumber, $number);


        $hash = 'aaaH';
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = $this->linkHashGenerator::SIZE - 1;
        $this->assertEquals($assertNumber, $number);

        $hash = 'aaam';
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = $this->linkHashGenerator::SIZE;
        $this->assertEquals($assertNumber, $number);

        $hash = 'aaaB';
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = $this->linkHashGenerator::SIZE + 1;
        $this->assertEquals($assertNumber, $number);


        $hash = 'aaaM';
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = $this->linkHashGenerator::SIZE * $this->linkHashGenerator::SIZE - 1;
        $this->assertEquals($assertNumber, $number);

        $hash = 'aaae';
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = $this->linkHashGenerator::SIZE * $this->linkHashGenerator::SIZE;
        $this->assertEquals($assertNumber, $number);

        $hash = 'aaaE';
        $number = $this->linkHashGenerator->getNumberByHash($hash);
        $assertNumber = $this->linkHashGenerator::SIZE * $this->linkHashGenerator::SIZE + 1;
        $this->assertEquals($assertNumber, $number);


        $hash = 'asdasdasdasd';
        $this->expectException(ErrorGeneratingHash::class);
        $this->linkHashGenerator->getNumberByHash($hash);
    }

}
