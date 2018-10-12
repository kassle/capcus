<?

declare(strict_types=1);

class CodeGeneratorImpl implements CodeGenerator {
    private const CHARLIST = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CHARSIZE = 61;

    public function generate(int $length) : string {
        $output = '';

        for ($index = 0; $index < $length; $index++) {
            $output .= $this->generateRandomChar();
        }

        return $output;
    }

    private function generateRandomChar() : string {
        return substr(CodeGeneratorImpl::CHARLIST, mt_rand(0, CodeGeneratorImpl::CHARSIZE), 1);
    }
}