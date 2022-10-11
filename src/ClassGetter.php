<?php

namespace Bfg\Wood;

class ClassGetter
{
    /**
     * get the class namespace\name form file path using token.
     *
     * @param $filePathName
     * @return string|null
     */
    public function getClassFullNameFromFile($filePathName): ?string
    {
        $result = $this->getClassNamespaceFromFile($filePathName).'\\'.$this->getClassNameFromFile($filePathName);

        return $result !== '\\' ? $result : null;
    }

    /**
     * build and return an object of a class from its file path.
     *
     * @param $filePathName
     * @return  mixed
     */
    public function getClassObjectFromFile($filePathName): mixed
    {
        $classString = $this->getClassFullNameFromFile($filePathName);

        $object = new $classString;

        return $object;
    }

    /**
     * get the class namespace form file path using token.
     *
     * @param $filePathName
     * @return  null|string
     */
    public function getClassNamespaceFromFile($filePathName): ?string
    {
        $src = file_get_contents($filePathName);

        $tokens = token_get_all($src);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
        if (! $namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }

    /**
     * get the class name form file path using token.
     *
     * @param $filePathName
     * @return  mixed
     */
    public function getClassNameFromFile($filePathName): mixed
    {
        $php_code = file_get_contents($filePathName);

        $classes = [];
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING
            ) {
                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }

        return $classes[0] ?? '';
    }
}
