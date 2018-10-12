<?

declare(strict_types=1);

interface CodeGenerator {
    public function generate(int $length) : string;
}