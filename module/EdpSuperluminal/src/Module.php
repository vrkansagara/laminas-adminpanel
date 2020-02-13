<?php

declare(strict_types=1);

namespace EdpSuperluminal;

use Laminas\Code\Reflection\ClassReflection;
use Laminas\Code\Scanner\FileScanner;
use Laminas\Console\Request as ConsoleRequest;

/**
 * @usage  http://yourapp/?EDPSUPERLUMINAL_CACHE
 * define('ZF_CLASS_CACHE', 'data/cache/classes.php.cache'); if (file_exists(ZF_CLASS_CACHE)) require_once ZF_CLASS_CACHE;
 */

/**
 * Create a class cache of all classes used.
 *
 * @package EdpSuperluminal
 */
class Module
{
    protected $knownClasses = [];

    /**
     * Attach events
     *
     * @return void
     */
    public function init($e)
    {
        $events = $e->getEventManager()->getSharedManager();
        $events->attach('Laminas\Mvc\Application', 'finish', [$this, 'cache']);
    }

    /**
     * Cache declared interfaces and classes to a single file
     *
     * @param \Laminas\Mvc\MvcEvent $e
     * @return void
     */
    public function cache($e)
    {
        $request = $e->getRequest();
        if (
            $request instanceof ConsoleRequest ||
            $request->getQuery()->get('EDPSUPERLUMINAL_CACHE', null) === null
        ) {
            return;
        }
        if (file_exists(ZF_CLASS_CACHE)) {
            $this->reflectClassCache();
            $code = file_get_contents(ZF_CLASS_CACHE);
        } else {
            $code = "<?php  declare(strict_types=1);\n";
        }

        $classes = array_merge(get_declared_interfaces(), get_declared_classes());


        foreach ($classes as $class) {
            // Skip non-Laminas classes
            if (0 !== strpos($class, 'Laminas')) {
                continue;
            }
            /**
             * @PR :- https://github.com/EvanDotPro/EdpSuperluminal/pull/43
             */
            // Skip non-Interop classes
            if (0 !== strpos($class, 'Interop')) {
                continue;
            }


            // Skip the autoloader factory and this class
            if (in_array($class, ['Laminas\Loader\AutoloaderFactory', __CLASS__])) {
                continue;
            }

            if ($class === 'Laminas\Loader\SplAutoloader') {
                continue;
            }

            // Skip any classes we already know about
            if (in_array($class, $this->knownClasses)) {
                continue;
            }
            $this->knownClasses[] = $class;

            $class = new ClassReflection($class);

            // Skip ZF2-based autoloaders
            if (in_array('Laminas\Loader\SplAutoloader', $class->getInterfaceNames())) {
                continue;
            }

            // Skip internal classes or classes from extensions
            // (this shouldn't happen, as we're only caching Zend classes)
            if (
                $class->isInternal()
                || $class->getExtensionName()
            ) {
                continue;
            }

            $code .= static::getCacheCode($class);
        }

        file_put_contents(ZF_CLASS_CACHE, $code);
        // minify the file
        file_put_contents(ZF_CLASS_CACHE, php_strip_whitespace(ZF_CLASS_CACHE));
    }

    /**
     * Generate code to cache from class reflection.
     *
     * This is a total mess, I know. Just wanted to flesh out the logic.
     * @param ClassReflection $r
     * @return string
     * @todo Refactor into a class, clean up logic, DRY it up, maybe move
     *       some of this into Laminas\Code
     */
    protected static function getCacheCode(ClassReflection $r)
    {
        $useString = '';
        $usesNames = [];
        if (count($uses = $r->getDeclaringFile()->getUses())) {
            foreach ($uses as $use) {
                $usesNames[$use['use']] = $use['as'];

                $useString .= "use {$use['use']}";

                if ($use['as']) {
                    $useString .= " as {$use['as']}";
                }

                $useString .= ";\n";
            }
        }

        $declaration = '';

        if ($r->isAbstract() && ! $r->isInterface()) {
            $declaration .= 'abstract ';
        }

        if ($r->isFinal()) {
            $declaration .= 'final ';
        }

        if ($r->isInterface()) {
            $declaration .= 'interface ';
        }

        if (! $r->isInterface()) {
            $declaration .= 'class ';
        }

        $declaration .= $r->getShortName();

        $parentName = false;
        if (($parent = $r->getParentClass()) && $r->getNamespaceName()) {
            $parentName = array_key_exists($parent->getName(), $usesNames)
                ? ($usesNames[$parent->getName()] ?: $parent->getShortName())
                : ((0 === strpos($parent->getName(), $r->getNamespaceName()))
                    ? substr($parent->getName(), strlen($r->getNamespaceName()) + 1)
                    : '\\' . $parent->getName());
        } elseif ($parent && ! $r->getNamespaceName()) {
            $parentName = '\\' . $parent->getName();
        }

        if ($parentName) {
            $declaration .= " extends {$parentName}";
        }

        $interfaces = array_diff($r->getInterfaceNames(), $parent ? $parent->getInterfaceNames() : []);
        if (count($interfaces)) {
            foreach ($interfaces as $interface) {
                $iReflection = new ClassReflection($interface);
                $interfaces = array_diff($interfaces, $iReflection->getInterfaceNames());
            }
            $declaration .= $r->isInterface() ? ' extends ' : ' implements ';
            $declaration .= implode(', ', array_map(function ($interface) use ($usesNames, $r) {
                $iReflection = new ClassReflection($interface);
                return (array_key_exists($iReflection->getName(), $usesNames)
                    ? ($usesNames[$iReflection->getName()] ?: $iReflection->getShortName())
                    : ((0 === strpos($iReflection->getName(), $r->getNamespaceName()))
                        ? substr($iReflection->getName(), strlen($r->getNamespaceName()) + 1)
                        : '\\' . $iReflection->getName()));
            }, $interfaces));
        }

        $classContents = $r->getContents(false);
        $classFileDir = dirname($r->getFileName());
        $classContents = trim(str_replace('__DIR__', sprintf("'%s'", $classFileDir), $classContents));

        $return = "\nnamespace "
            . $r->getNamespaceName()
            . " {\n"
            . $useString
            . $declaration . "\n"
            . $classContents
            . "\n}\n";

        return $return;
    }

    /**
     * Determine what classes are present in the cache
     *
     * @return void
     */
    protected function reflectClassCache()
    {
        $scanner = new FileScanner(ZF_CLASS_CACHE);
        $this->knownClasses = array_unique($scanner->getClassNames());
    }
}
