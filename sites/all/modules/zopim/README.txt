// $Id: README.txt,v 1.1 2010/06/09 01:57:03 nicholasalipaz Exp $

### ABOUT

  This module adds the necessary script to the footer of ones site for prompting users to chat via zopim.

  Current Features:
      * Administration settings to allow setting your account number for the script
      * Setting the pages in which to show the script:
            o From a blacklist of pages
            o From a whitelist of pages
            o By returning a value of true or false from PHP snippet
      * Setting visibility of script by role

### INSTALLING

  1. Extract zopim tarball into your sites/all/modules directory so it looks like sites/all/modules/zopim
  2. Navigate to Administer -> Build -> Modules and enable the module.
  3. Navigate to Administer -> Site Configuration -> Zopim and add your account number as well as any other configuration options you want.

### CREDITS

  Originally developed by Nicholas Alipaz of Stitch Technologies - http://www.stitch-technologies.com/
