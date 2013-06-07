ezRPG 1.2.x: Development Stage Branch

Warning, this branch is a testing branch to test new ideas before pushing to Master. If you're new to ezRPG,
stay with the Master branch instead. 

=====

This engine is destined to be part of the ezRPG legacy 1.x series started by Zeggy. For the new, reimagined version implementing the great new offerings of PHP5, a true MVC structure, PSR-0 compliant, and Smarty-Less, check out ezRPG 2.x series!.

=====

##Notes about 1.2.x development
####ETA on delivery: June 9th 2013!

```
Base_Module now holds more responsibility like "getTheme and loadView":
getTheme() checks 3 things:
1. Are you in an AdminCP by checking IN_ADMIN and serving up the Admin Theme. (Admin theme is supplied, and currently not looking to be edited)
2. Is there a current theme that's enabled in the DB? (if so, select it)
3. If no current theme is enabled, default back to default theme.
	
getTheme then provides a Smarty Variable: "THEME_DIR" which will the directory for Theme assets. Developers can still load from Static if based off of our default theme.

loadView() is accessed via "$this->loadView('template.tpl', OPTIONAL 'ModuleName')" just like "$this->tpl->display('template.tpl')".
loadView() does 4 things:
1. Grabs the current set theme via $this->theme set by getTheme()
2. If the second param is set like 'MailBox' or 'Bank':
2.1. loadView checks the current theme for .tpl file
2.2. If loadView can't find it, it loads 'MailBox' or 'Bank' theme
2.2.1. These module themes are located templates/modules/*
3. Calls "$this->tpl->display('file:[ NAME OF THEME DIR ] template.tpl')" 
4. If this doesn't work at all, Error404 is redirected
	
Later an Error404 needs to be supplied some where to this process.

Init.php has also taken a role in the Theme Support which needs to be decoupled later:
1. Creates the default TemplateDir in Smarty for Admin and Default theme
2. Checks if both a theme is in the themes folder AND in the DB.
2.1. This happens on every page load which should be decoupled to only when checked or when there's an issue with a theme.
2.2. Checks also for templates/modules and loads that into TemplateDir.
3. HTMLPurifier was removed, but should have an option setting. Find a way to decouple!

Class.Menu is still unstable but provides better integration.
1. 1.0.x class.Menu auto embeded HTML code, 1.2.x class.Menu just returns an Array of menu items.
2. Smarty Templates now much do foreach to setup menus
3. Possible idea is to serialize array'd menu data into db so we can leave the logic to PHP only and use just {Menu_} tag
3.1. This would be that during Admin/MenuManager, when you edit a Menu Group's subMenus, you'll just save the data to the single db row.
	
Smarty Templates now have much more logic to them. Possible No Go for some, but necessary at the current stage.
1. Templates now take on dynamic themes, more ForEach's for menus, could be a bit tempting at first
2. Only the header.tpl or any tpl that NEEDS a menu will have this logic in it. Comments should be provided still though.


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


