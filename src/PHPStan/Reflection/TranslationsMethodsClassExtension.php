<?php

declare(strict_types=1);

namespace App\PHPStan\Reflection;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ObjectType;

class TranslationsMethodsClassExtension implements MethodsClassReflectionExtension
{
    private ObjectType $objectType;

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        try {
            $this->objectType = new ObjectType($classReflection->getName() . 'Translation');

            if ($this->objectType->isCallable()->no()) {
                return false;
            }
        } catch (\Exception) {
            return false;
        }

        return $this->findMethod($methodName) != null;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        $method = $this->findMethod($methodName);
        assert($method != null);

        return $method;
    }

    private function findMethod(string $methodName): ?MethodReflection
    {
        if (!$this->objectType->hasMethod($methodName)->yes()) {
            return null;
        }

        return $this->objectType->getMethod($methodName, new OutOfClassScope());
    }
}
