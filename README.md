# NLU_FF_AC_QD #
v1.9.5

## Description: ##
Whenever you need/want to display the number of articles of any site structure level next to the name of the category, the script does it.

This is the quick and dirty solution! This is the original **NAV_LIST_UL** of *flip-flop* that was incorporated by OG and now has been extended with --> easily add the number of articles within a certain site structure level.

The script works exactly like the original **NAV_LIST_UL**, because it's based on the same function.

**Basics** to read --> https://forum.phpwcms.org/viewtopic.php?t=12165

#### For those who are interested why I chose this name: ####
* NLU = NAV_LIST_UL
* FF = flip-flop
* AC = article count
* QD = quick and dirty

You can use it as RT = replacement tag  with the same prominent parameters and features as usual, but instead NAV_LIST_UL just type: NLU_FF_AC_QD... Read also <http://phpwcms.org> and Knut's (aka flip-flop's) [HowTo Wiki]:

* ==> https://wiki.phpwcms.org/doku.php/deutsch/ersetzer_rts/frontend_render/nav_list_ul-article-count
* ==> https://wiki.phpwcms.org/doku.php/english/phpwcms_replacer_rts/frontend_render/nav_list_ul-article-count

and:

==> _RTFM_: https://forum.phpwcms.org/viewtopic.php?f=8&t=17891 and read also:
==> https://github.com/slackero/phpwcms/issues/13

## New in v1.9.5 ##
Works well with almost all modern versions of phpwcms and PHP 7.x

```
<nav class="collapse navbar-collapse" role="navigation">
{NLU_FF_AC_QD:PBA,0,2,active|nav nav-pills,active}
</nav>
{NLU_FF_AC_QD:B,0,,active|nav navbar-nav,active}
{NAV_LIST_UL:BA,0,,,active,,,,0|100x100x1|teaser:10|outside|<div class="dropdown-menu">[IMAGE]<img src="[%IMAGE%]" alt="[%IMAGE_NAME%]" class="dropdown-image" />[/IMAGE][TEXT]<div class="dropdown-text">[%TEXT%]</div>[/TEXT]</div>}
```

### Quick start ###
Clone the repo: `https://github.com/jensensen/NLU_FF_AC_QD.git`.

### Bug tracker ###
Have a bug? Please create an issue here on GitHub that conforms with [necolas's guidelines](https://github.com/necolas/issue-guidelines).

Report [issue](https://github.com/jensensen/NLU_FF_AC_QD/issues).

Share with us and me
-------------

Keep up to date on announcements and more by following **phpwcms** on Twitter [@phpwcms](https://twitter.com/phpwcms) or on [Facebook](https://www.facebook.com/pages/phpwcms/162275020999) and jensensen on [Facebook](https://www.facebook.com/jens.bruckner.98).


Author
------

### jensensen ###
* <https://forum.phpwcms.org/viewtopic.php?f=8&t=17891>
* <https://github.com/jensensen/>
* <https://www.facebook.com/jens.bruckner.98>

Copyright and license
---------------------

Copyright 2008–2019 jensensen

This work - **NLU_FF_AC_QD for phpwcms** by jensensen (jbr/LH/DE) - is
licensed under the GNU General Public License, Version 2 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:

   <https://opensource.org/licenses/GPL-2.0>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the
    Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston,
    MA 02110-1301, USA.

