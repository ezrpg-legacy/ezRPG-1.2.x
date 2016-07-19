##ezRPG 1.2.1.5

###Warning this version of ezRPG 1.2.0 has substantial code changes from it's previous version (1.0.x). If you are upgrading, make sure you've checked and tested all modules to make sure they conform to the new code.

=====

This engine is destined to be part of the ezRPG legacy 1.x series started by Zeggy. For the new, reimagined version implementing the great new offerings of PHP5, a true MVC structure, PSR-0 compliant, and Smarty-Less, check out ezRPG 2.x series when that development is ready for public release!

=====

##Notes about 1.2.1.5
####Pushed July 18 2016
```

- Working on fixing the Plugin Manager. Porting over the concept introduced by JesterC in ezRPG 1.0.x with the Module_Info.txt
- Everytime you visit the Plugin Manager, it will force a reload of the Module Cache which does a scandir and a db call, as well as recreates the /cache/module_cache file.
- This module_cache will then be active and usable throughout the game and never get reloaded until visiting the Plugin Manager.
- The idea is that the cache is always good until that 1 event, as that's the only page we should be doing activations and installations of modules, if we never do that after uploading a new module, then it's neither installed nor active yet.

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
Need some help? Check out the [ezRPG Forums](http://www.ezrpgproject.net/)

## Installation

1. [Download latest version of ezRPG](https://github.com/ezrpg/ezRPG-1.2.x/tags)
2. Give read/write permission to:
  * config.php
  * templates/*
3. Visit yourgame.com/path/to/ezrpg/install in your browser and follow the instructions.

## License

ezRPG 1.2.x is open-sourced software licensed under the GNU GPL v3 license.


