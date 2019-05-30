Whois: MediaWiki extension
==========================

_The Whois MediaWiki extension adds a special page to obtain basic information
about an IP address from the WHOIS registries._

[![DOI](https://zenodo.org/badge/DOI/10.5281/zenodo.3072958.svg)](https://doi.org/10.5281/zenodo.3072958)

Installation
-----------

* Download and place the files in a directory called `Whois` in your
  `extensions/` folder.
* Add the following code at the bottom of your `LocalSettings.php`:

      wfLoadExtension( 'Whois' );

* Done – Navigate to `Special:Version` on your wiki to verify that
  the extension is successfully installed.


Usage
-----

The Whois extension adds special page `Special:Whois` to a wiki. At this
special page you are able to obtain basic information about an IP address
from the WHOIS registries (including abuse contact and common geographical
information). However, to access this page you need to be a registered
and logged-in user on the wiki.

Note that this extension is not listed in `Special:Specialpages` and it requires
an IP address as a _subpage_ argument (i.e. `Special:Whois/xxx.xxx.xxx.xxx`).

You can find link to this tool at `Special:Contributions/<ipaddress>` among
the other tools just under the first heading.

You may also want to add a link to this special
page to `MediaWiki:Anontalkpagetext`:

    [[Special:Whois/{{PAGENAMEE}} | Get info about this IP]]
    
Or you may want to add the same link to `MediaWiki:Checkuser-toollinks`
on your wiki.

This extension is under development, so there will be probably more features
in the future (like a basic form, adding a menu item etc.).


User rights
-----------

To prevent abuse of this tool, you need to be a logged-in user on your wiki
to access the `Special:Whois` page.


License
-------

Created by Petr Kajzar, 2019. Released under Creative Commons Zero v1.0
Universal license.

To the extent possible under law, I have dedicated all copyright and related
and neighboring rights to this software to the public domain worldwide.
This software is distributed without any warranty.
