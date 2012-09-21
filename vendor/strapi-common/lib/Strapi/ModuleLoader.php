<?php
/**
 * Strapi: Bootstrap your API
 */
namespace Strapi;

use RuntimeException;

/**
 * Defines functionality for lazy-loading and storing modules
 *
 * The concept of modules is intentionally non-restrictive.  The contents of
 * modules can be traditional array-based callbacks, anonymous functions, or
 * even scalar values.
 *
 * The names of modules appear to be file system paths, but they are actually
 * OS agnostic. "/" characters in the module name are replaced with the
 * appropriate DIRECTORY_SEPARATOR for the current runtime.
 */
trait ModuleLoader
{
    protected $modulePath = '';
    protected $modules = [];

    /**
     * Retrieves the base module path, and optionally sets it
     *
     * If $path is specified, then it is asserted to be a directory, resolved
     * as an absolute path on the file system, and set prior to being returned.
     *
     * @param null|string $path
     * @return string
     * @throws \RuntimeException If the given path is not a valid directory
     */
    public function modulePath($path = null)
    {
        if ($path !== null) {
            $path = realpath($path);
            if ($path === false) {
                throw new RuntimeException("Module path does not exist: $path");
            }
            if (!is_dir($path)) {
                throw new RuntimeException("Module path is not a directory: $path");
            }
            $this->modulePath = $path;
        }
        return $this->modulePath;
    }

    /**
     * Loads the module with the given name
     *
     * If the module has already been loaded, then it is returned verbatim.
     * Otherwise, the module is loaded from the file system.  Modules can be
     * stored relative to the module path or as absolute paths.
     *
     * Modules are executed in the scope of the loading object, so they can
     * load other modules through ```$this->load($name)```.
     *
     * @param string $name
     * @return mixed
     * @throws \RuntimeException If the given module file can not be found
     */
    public function load($name)
    {
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        }

        $file = $name . '.php';
        if ($file[0] !== '/') {
            // not an absolute path, so assume relative to project root
            $file = $this->modulePath() . '/' . $file;
        }

        // use the correct directory separator for the current OS
        if (DIRECTORY_SEPARATOR != '/') {
            $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
        }

        if (!file_exists($file)) {
            throw new RuntimeException("Could not find module $name at $file");
        }

        $module = require $file;
        $this->modules[$name] = $module;
        return $module;
    }
}