<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/bootstrap.min.js');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/bootstrap.min.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/static.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<jdoc:include type="head" />
<script src="<?php echo JUri::root(true); ?>/templates/<?php echo $this->template; ?>/js/modernizr.min.js"></script>
<!--[if lt IE 9]>
<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
<script src="<?php echo JUri::root(true); ?>/templates/<?php echo $this->template; ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="linelabox">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>