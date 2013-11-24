##nuRPG 0.0.1

####Forked from ezRPG 1.2.1.11

=====

This engine is destined to be part of the ezRPG legacy 1.x series started by Zeggy. For the new, reimagined version implementing the great new offerings of PHP5, a true MVC structure, PSR-0 compliant, and Smarty-Less, check out ezRPG 2.x series when that development is ready for public release!

=====

##Notes about 0.0.2
####Pushed Nov 23 2013
```

- PluginManager.module.php now implements the activate, deactivate, and uninstall functions (update to come later).
- PHPDoc comments are parsed and utilized as part of the installer process.
- KillModuleCache has been added to the PluginManager to address issues found during development.
- Added a 'class' column to <ezrpg>plugins.
- Deleted 'filename', 'installed', and 'secondary_install' from <ezrpg>plugins.
- 'active' now takes the place of any 'installed' information. When a plugin is uploaded, the system checks for __active() method. If exists, it runs and checks for a True/False return. It's on the plugin developers to implement their error tracking features to return correct information. A FALSE return results in Active remaining as 0 (or NOT installed) and TRUE results as 1 (installed and active). If a plugin gets deactivated, then it's still installed, but the tables it installed during it's initial activation should still be intact. The developer must currently do a check to ensure tables aren't recreated. See example.module.php for an example.

```

##ToDo for 0.0.3<
```
- Implement __update function to pluginmanager.
- Fix an issue in the pluginmanager where the Library Autoloader is trying to load the uploaded module and producing an error.
- Fix the update function to work in a similar matter of downloading the update via a .zip, extracting it to a temp file, and then running update operations found in a update.php file before deleting said file.
- Hit milestone 1.0 and decide to remerge back with ezRPG or continue as a separate fork.

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


