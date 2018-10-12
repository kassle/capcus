<?

use PHPUnit\Framework\TestCase;

class CodeGeneratorTest extends TestCase {
    private $codegen;

    public function setup() {
        $this->codegen = new CodeGeneratorImpl();
    }

    public function testGenerateShouldReturnStrignWithPreferedLength() : void {
        $this->assertEquals(
            12,
            strlen($this->codegen->generate(12))
        );
    }

    public function testGenerateShouldReturnRandomString() : void {
        $output1 = $this->codegen->generate(8);
        $output2 = $this->codegen->generate(8);

        $this->assertNotEquals(
            $output1,
            $output2
        );
    }

    public function testGenerateShouldReturnAlphanumericString() : void {
        $this->assertTrue(ctype_alnum($this->codegen->generate(12)));
    }

    public function testGenerateWithZeroLengthShouldReturnEmpty() : void {
        $this->assertEquals(
            '',
            $this->codegen->generate(0)
        );
    }
}