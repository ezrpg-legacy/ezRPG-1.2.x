##ezRPG 1.2.0 RC2

###Warning this version of ezRPG 1.2.0 is still a Release Candidate and not a fully stable version. 
At the present time,ezRPG is slated for two Release Candidates lasting between one to two weeks of testing and possible code changes.

These Release Candidates are open for public development and recommended for only testing purposes. Any and all pull requests will be taken into consideration and pushed as needed. 

####We do NOT recommend this version for current live games (yet)!

=====

This engine is destined to be part of the ezRPG legacy 1.x series started by Zeggy. For the new, reimagined version implementing the great new offerings of PHP5, a true MVC structure, PSR-0 compliant, and Smarty-Less, check out ezRPG 2.x series when that development is ready for public release!

=====

##Notes about 1.2.0 RC2
####ETA on 1.2.0 (FINAL): July 14 2013!

```

Simple Cache system has been added for Settings DB and TemplateDIRs.
Plugin System is near complete reading basic meta information from XML which will include FileLocations, Theme/Lib/Hook locations, URI information, etc
Theme System allows for simple changing of Themes with two supplied during install
Menu system allows modules to add and remove items from menu. Menu returns an array to be used in template headers, nav lists, etc. Must be parsed and styled accordingly.
Hooks are to be added so developers have more native locations to integrate with other than Header/Footer (AdminHeader/AdminFooter).
Simple stopwatch and percentage info added for Debug_mode to see which area takes the longest. Later better debug information should be used


TODO:
BugTest BugTest BugTest
Clean code and format accordingly
Finish Plugin system
Migrate basic popular Modules and Hooks for inspiration
Complete Documentation

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

1. [Download latest version of ezRPG](https://github.com/ezrpg/ezrpg/tags)
2. Give read/write permission to:
  * config.php
  * templates/*
3. Visit yourgame.com/path/to/ezrpg/install in your browser and follow the instructions.

## License

ezRPG is open-sourced software licensed under the GNU GPL v3 license.


