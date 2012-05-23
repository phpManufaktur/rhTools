### rhTools

A tool library for the Content Management Systems [WebsiteBaker] [1] and [LEPTON CMS] [2] for usage by other add-ons

#### Requirements

* minimum PHP 5.2.x
* using [WebsiteBaker] [1]
* _or_ using [LEPTON CMS] [2]

#### Installation

* download the actual [rhTools_x.xx.zip] [3] installation archive
* in CMS backend select the file from "Add-ons" -> "Modules" -> "Install module"

#### First Steps

You can access *rhTools* from your own addons:

    require_once WB_PATH.'/modules/rhtools/include.php';
    
    $tools = new rhTools();

that's all.

Please have look into the class rhTools to get informations about the supported functions and features of this tool library.  

[1]: http://websitebaker2.org "WebsiteBaker Content Management System"
[2]: http://lepton-cms.org "LEPTON CMS"
[3]: https://github.com/phpManufaktur/rhTools/downloads
