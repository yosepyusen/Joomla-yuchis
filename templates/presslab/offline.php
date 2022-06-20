<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** @var JDocumentHtml $this */

$twofactormethods = JAuthenticationHelper::getTwoFactorMethods();
$app              = JFactory::getApplication();

// Output as HTML5
$this->setHtml5(true);

$fullWidth = 1;

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', 'static.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'bootstrap.min.css', array('version' => 'auto', 'relative' => true));
// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Logo file or site title param
$sitename = $app->get('sitename');
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle')) . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
</head>
<body class="offline_page">
	<div class="container">
			<div class="row main">
				<div class="panel-heading">
	               <div class="panel-title text-center">
	               	<?php if (!empty($logo)) : ?>
					<h1 class="title"><?php echo $logo; ?></h1>
				<?php else : ?>
					<h1 class="title"><?php echo htmlspecialchars($app->get('sitename')); ?></h1>
				<?php endif; ?>
	               		<hr />
				<?php if ($app->get('offline_image') && file_exists($app->get('offline_image'))) : ?>
					<img src="<?php echo $app->get('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->get('sitename')); ?>" />
				<?php endif; ?>
	               	</div>
	            </div> 
				<div class="main-login main-center">
				<jdoc:include type="message" />
					<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) != '') : ?>
					<p class="offline_msg"><?php echo $app->get('offline_message'); ?></p>
				<?php elseif ($app->get('display_offline_message', 1) == 2) : ?>
					<p class="offline_msg"><?php echo JText::_('JOFFLINE_MESSAGE'); ?></p>
				<?php endif; ?>							
				<form class="form-horizontal" action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">				
<div class="form-group">
						<label class="cols-sm-2 control-label" for="username"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
						<input name="username" class="form-control" id="username" type="text" placeholder="<?php echo JText::_('JGLOBAL_USERNAME'); ?>" />
	</div>
 </div>
</div>					
<div class="form-group">		
						<label class="cols-sm-2 control-label" for="password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
								<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
						<input type="password" name="password" class="form-control" id="password" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" />
	</div>
 </div>
</div>		
						<?php if (count($twofactormethods) > 1) : ?>
<div class="form-group">
						<label class="cols-sm-2 control-label" for="secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
						<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-key fa-lg" aria-hidden="true"></i></span>
						<input type="text" name="secretkey" id="secretkey" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" />
	</div>
 </div>
</div>
						<?php endif; ?>					
						<div class="form-group ">
						<input type="submit" name="Submit" class="btn btn-primary btn-lg btn-block login-button" value="<?php echo JText::_('JLOGIN'); ?>" />
						<input type="hidden" name="option" value="com_users" />
						<input type="hidden" name="task" value="user.login" />
						<input type="hidden" name="return" value="<?php echo base64_encode(JUri::base()); ?>" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>
				</div>
			</div>
		</div>
	</body>
</html>