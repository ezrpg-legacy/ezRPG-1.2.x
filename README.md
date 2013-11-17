##nuRPG 0.0.1

####Forked from ezRPG 1.2.1.11

=====

This engine is destined to be part of the ezRPG legacy 1.x series started by Zeggy. For the new, reimagined version implementing the great new offerings of PHP5, a true MVC structure, PSR-0 compliant, and Smarty-Less, check out ezRPG 2.x series when that development is ready for public release!

=====

##Notes about 0.0.1
####Pushed Nov 15 2013
```

- Initial Release
- Modules are autoloaded with spl_autoload
- Support for old modules still exist, but will be removed later
- Modules now are names "MODULENAME.module.php" and are JUST put inside the modules/* directory and nor a subdirectory of that.
- Module classes don't need "Module_" prefixed to it. It's already in a module folder, extending a Base_Module.
- Magic methods are to be created: "__activate", "__deactivate", "__uninstall", and "__update". "__activate" will run both install and activate functions, it'll be by design of the developer to distinguish how these will be to avoid re-installation of DB commands.
- Standard Formatting of PHPDoc comments will be utilized for "Module Name", "Author", "Version" etc.
- An almost complete Plugin Uploader that does away with the xml idea.
- Created an $app super variable instead of using a proper IoC container to limit the number of objects being passed by storing objects inside a single variable.
- Currently we're pretty close to being 100% compatible with 1.2.1.x modules. Some tweaks are needed for hooks and such.
```
=====
A modular game engine written in PHP.

## Contribute
Contributing to the ezRPG project couldn't be easier!

1. Fork the project
2. Make your changes, and push them to your forked repository
3. Send a pull request with your changes
4. We will review your changes, and accept them if they fix the problem without causing any problems

## Support
Need some help? http://www.tagsolutions.tk or PM uaktags at http://www.ezrpgproject.net

Original Credits:
http://www.ezrpgproject.net

## Installation

1. [Download latest version of ezRPG](https://github.com/ezrpg/ezRPG-1.2.x/tags)
2. Give read/write permission to:
  * config.php
  * templates/*
3. Visit yourgame.com/path/to/ezrpg/install in your browser and follow the instructions.

## License

ezRPG 1.2.x is open-sourced software licensed under the GNU GPL v3 license.


