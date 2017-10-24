<?php

namespace Pilsniak\PhpCsFixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Tokenizer\Tokens;

class CodeStyler
{
    public function style(string $code)
    {
        $tokens = Tokens::fromCode($code);

        $oldHash = $tokens->getCodeHash();
        $newHash = $oldHash;
        $new = $code;

        $appliedFixers = [];
        $file = new \SplFileInfo('.cache/file');

        foreach ($this->fixers as $fixer) {
            /** @var AbstractFixer $fixer */
            $fixer->fix($file, $tokens);
            if ($tokens->isChanged()) {
                $tokens->clearEmptyTokens();
                $tokens->clearChanged();
                $appliedFixers[] = $fixer->getName();
            }
        }

        if (!empty($appliedFixers)) {
            $new = $tokens->generateCode();
            $newHash = $tokens->getCodeHash();
        }
        $this->linter->lintSource($new)->check();

        return $new;
    }
}
