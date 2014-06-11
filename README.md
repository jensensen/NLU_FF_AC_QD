#NLU_FF_AC_QD#
v1.8

##Description:##
Whenever you need/want to display the number of articles of any site structure level next to the name of the category, the script does it.

This is the quick and dirty solution! This is the original **NAV_LIST_UL** of *flip-flop* that was incorporated by OG and now has been extended with --> easily add the number of articles within a certain site structure level.

The script works exactly like the original **NAV_LIST_UL**, because it's based on the same function.

**Basics** to read --> http://forum.phpwcms.org/viewtopic.php?t=12165

####For those who are interested why I chose this name:####
* NLU = NAV_LIST_UL
* FF = flip-flop
* AC = article count
* QD = quick and dirty

You can use it as RT = replacement tag  with the same prominent parameters and features as usual, but instead NAV_LIST_UL just type: NLU_FF_AC_QD... Read also <http://phpwcms.org> and Knut's (aka flip-flop's) [HowTo Wiki]:

* ==> http://www.phpwcms-howto.de/wiki/doku.php/deutsch/ersetzer_rts/frontend_render/nav_list_ul-article-count
* ==> http://www.phpwcms-howto.de/wiki/doku.php/english/phpwcms_replacer_rts/frontend_render/nav_list_ul-article-count

and:

==> _RTFM_: http://forum.phpwcms.org/viewtopic.php?f=8&t=17891 and read also: 
==> https://github.com/slackero/phpwcms/issues/13

##New in v1.8##
The original NAV_LIST_UL has been changed by OG.

```
{NLU_FF_AC_QD:B,0,,active|nav navbar-nav,active}
{NAV_LIST_UL:BA,0,,,active,,,,0|100x100x1|teaser:10|outside|<div class="dropdown-menu">[IMAGE]<img src="[%IMAGE%]" alt="[%IMAGE_NAME%]" class="dropdown-image" />[/IMAGE][TEXT]<div class="dropdown-text">[%TEXT%]</div>[/TEXT]</div>}
```
So this script needs an update:

* ==> http://forum.phpwcms.org/viewtopic.php?f=1&t=24328
* ==> https://github.com/slackero/phpwcms/commit/e8b39f83ce103a63b54b9b660a6574cc1dbfbebe

###Quick start###
Clone the repo: `https://github.com/jensensen/NLU_FF_AC_QD.git`.

###Bug tracker###
Have a bug? Please create an issue here on GitHub that conforms with [necolas's guidelines](https://github.com/necolas/issue-guidelines).

Report [issue](https://github.com/jensensen/NLU_FF_AC_QD/issues).

Share with us and me
-------------

Keep up to date on announcements and more by following **phpwcms** on Twitter [@phpwcms](http://twitter.com/phpwcms) or on [Facebook](https://www.facebook.com/pages/phpwcms/162275020999) and jensensen on [Facebook](https://www.facebook.com/jens.bruckner.98).


Author
------

### jensensen ###
* <http://forum.phpwcms.org/viewtopic.php?f=8&t=17891>
* <http://github.com/jensensen/>
* <https://www.facebook.com/jens.bruckner.98>

Copyright and license
---------------------

Copyright 2008–2014 jensensen

This work - **NLU_FF_AC_QD for phpwcms** by jensensen (jbr/LH/DE) - is licensed under a 

[Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)](http://creativecommons.org/licenses/by-sa/4.0/) License. ![License:CC BY-SA 4.0](http://i.creativecommons.org/l/by-sa/4.0/88x31.png)

This program is free software. You can redistribute it and/or
modify it under the terms of the CC License as published and as long as others share alike.

This program is distributed in the hope that it will be useful,
but **WITHOUT ANY WARRANTY** and without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
