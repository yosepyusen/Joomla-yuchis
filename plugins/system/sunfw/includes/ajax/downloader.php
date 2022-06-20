<?php
/**
 * @version    $Id$
 * @package    SUN Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import necessary libraries.
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Widget for downloading a remote file.
 *
 * @package  SUN Framework
 * @since    1.0.0
 */
class SunFwAjaxDownloader extends SunFwAjax
{

	public function indexAction($url = null, $file = null, $cookies = array(), $fail_safe = false)
	{
		// Set necessary header.
		header('Content-type: application/json; charset=utf-8');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Pragma: no-cache");

		try
		{
			// Get URL of remote file.
			if (empty($url))
			{
				$url = urldecode($this->input->getString('remote_url'));
			}

			// Verify request variables.
			if (empty($url))
			{
				throw new Exception(JText::_('SUNFW_ERROR_INVALID_REQUEST'));
			}

			// Get name of local file.
			if (empty($file))
			{
				if (isset($_REQUEST['background_request']) && $_REQUEST['background_request'] == 'yes')
				{
					$file = urldecode($this->input->getString('local_file'));
				}
				else
				{
					$file = $this->app->getCfg('tmp_path') . preg_replace('/[^a-zA-Z0-9\-\._]+/', '_', basename($url));
				}
			}

			// Parse remote file URL.
			$parts = parse_url($url);
			$secure = ( $parts['scheme'] == 'https' ) ? true : false;

			// Get requested task.
			$task = $this->input->getString('task', 'download');

			if ($task != 'status' || $fail_safe)
			{
				// Prepare directory to write local file.
				$dir = dirname($file);

				if (!JFolder::exists($dir) && !JFolder::create($dir))
				{
					throw new Exception(JText::_('SUNFW_ERROR_DIRECTORY_NOT_FOUND'));
				}

				// Disable max execution time if possible.
				SunFwHelper::isDisabledFunction('set_time_limit') or set_time_limit(0);

				if (!$fail_safe && function_exists('fsockopen') && ( $fp = fsockopen(( $secure ? 'ssl://' : '' ) . $parts['host'],
					isset($parts['port']) ? $parts['port'] : ( $secure ? 443 : 80 ), $errno, $errstr, 5) ))
				{
					// Generate a token key to authorize background request.
					$token = md5($this->app->getCfg('secret') . __FILE__ . $url . $file);

					// Get target file type.
					$type = $this->input->getString('content_type', 'application/octet-stream');

					if ($this->input->getString('background_request') != 'yes')
					{
						// Create request header.
						$request = "HEAD {$parts['path']}" . ( empty($parts['query']) ? '' : "?{$parts['query']}" ) . " HTTP/1.0\r\n";
						$request .= "Host: {$parts['host']}\r\n";
						$request .= "Connection: Close\r\n\r\n";

						// Send request headers.
						fwrite($fp, $request);

						// Get response.
						$size = 0;

						while (!feof($fp))
						{
							$response = fgets($fp);

							// Check if there is a 'Location' header?
							if (preg_match('/^Location: ([^\r\n]+)/', $response, $match))
							{
								// Close socket connection.
								fclose($fp);

								// Use new URL to download file.
								return $this->indexAction($match[1], $file);
							}

							// Get remote file type from headers.
							if (preg_match('/^Content-Type: ([^\r\n]+)/', $response, $match))
							{
								$type = $match[1];
							}

							// Get remote file size from headers.
							if (preg_match('/^Content-Length: (\d+)/', $response, $match))
							{
								$size = $match[1];
							}
						}

						// Close socket connection.
						fclose($fp);

						// Check if target file exists?
						if (JFile::exists($file))
						{
							// Delete existing local file.
							if (!JFile::delete($file))
							{
								throw new Exception(JText::_('SUNFW_ERROR_CANNOT_REMOVE_EXISTING_FILE'));
							}
						}

						// Check if lock file exists?
						if (JFile::exists("{$file}.lock"))
						{
							// Delete lock file to reset download session.
							if (!JFile::delete("{$file}.lock"))
							{
								throw new Exception(JText::_('SUNFW_ERROR_CANNOT_REMOVE_EXISTING_FILE'));
							}
						}

						// Prepare parameters for sending background request.
						$parts = parse_url(JUri::base() . 'index.php');
						$secure = ( $parts['scheme'] == 'https' ) ? true : false;

						$parts['query'] = http_build_query(
							array(
								'option' => 'com_ajax',
								'plugin' => 'sunfw',
								'format' => 'json',
								'context' => 'downloader',
								'action' => 'index',
								'background_request' => 'yes',
								'content_type' => $type,
								'remote_url' => $url,
								'local_file' => $file,
								'token' => $token,
								JSession::getFormToken() => '1'
							));

						$cookies = $_COOKIE;

						// Recreate resource for sending request.
						$fp = fsockopen(( $secure ? 'ssl://' : '' ) . $parts['host'],
							isset($parts['port']) ? $parts['port'] : ( $secure ? 443 : 80 ), $errno, $errstr, 5);
					}
					elseif ($this->input->getString('token') != $token)
					{
						throw new Exception(JText::_('SUNFW_ERROR_INVALID_TOKEN'));
					}

					// Create request header.
					$request = "GET {$parts['path']}" . ( empty($parts['query']) ? '' : "?{$parts['query']}" ) . " HTTP/1.0\r\n";
					$request .= "Host: {$parts['host']}\r\n";
					$request .= "Content-Type: {$type}\r\n";
					$request .= "Connection: Close\r\n";

					if (is_array($cookies) && count($cookies))
					{
						$cookie_str = '';

						foreach ($cookies as $k => $v)
						{
							$cookie_str .= urlencode($k) . '=' . urlencode($v) . '; ';
						}

						$request .= 'Cookie: ' . substr($cookie_str, 0, -2) . "\r\n";
					}

					$request .= "\r\n";

					// Set 1s timeout for reading/writing data over the socket.
					stream_set_timeout($fp, 1);

					// Send request headers.
					fwrite($fp, $request);

					// Create lock file.
					JFile::write("{$file}.lock", $request);

					if ($this->input->getString('background_request') == 'yes')
					{
						// Get response.
						$end_of_header = false;
						$response = '';

						while (!feof($fp))
						{
							// Read maximum 50KB at once.
							$line = fgets($fp, 50 * 1024);

							if (!$end_of_header)
							{
								// Check if there is a 'Location' header?
								if (preg_match('/^Location: ([^\r\n]+)/', $line, $match))
								{
									// Close socket connection.
									fclose($fp);

									// Remove lock file.
									JFile::delete("{$file}.lock");

									// Use new URL to download file.
									return $this->indexAction($match[1], $file);
								}

								// End of headers?
								$response .= $line;

								if (false !== strpos($response, "\r\n\r\n"))
								{
									$end_of_header = true;
								}
							}
							else
							{
								file_put_contents($file, $line, FILE_APPEND);
							}
						}

						// Remove lock file.
						JFile::delete("{$file}.lock");
					}
					else
					{
						// Get at least one byte response to make sure request has been sent.
						$response = fgets($fp, 2);
					}

					// Close socket connection.
					fclose($fp);
				}
				else
				{
					// Socket connection is not available, download file normally.
					$http = new JHttp();
					$data = $http->get($url);

					// Write sample data to file.
					if (!JFile::write($file, $data->body))
					{
						throw new Exception(JText::_('SUNFW_ERROR_CANNOT_CREATE_LOCAL_FILE'));
					}
				}
			}

			// Get current download status.
			$file_size = $this->input->getInt('size');
			$size = isset($size) ? $size : ( JFile::exists($file) ? filesize($file) : 0 );
			$done = ( $file_size > 0 ? ( $size == $file_size ) : ( $file_size === 0 ? ( $size > 0 ) : !JFile::exists("{$file}.lock") ) );

			// If downloaded file size is 0 for over 5s, try another download method.
			if ($size == 0 && time() - filemtime("{$file}.lock") > 5)
			{
				return $this->indexAction($url, $file, $cookies, true);
			}

			// Send response back.
			echo json_encode(
				array(
					'type' => 'success',
					'data' => array(
						'file' => basename($file),
						'size' => $size,
						'done' => $done
					)
				));

			if ($done)
			{
				// Remove lock file.
				JFile::delete("{$file}.lock");
			}
		}
		catch (Exception $e)
		{
			// Send response back.
			echo json_encode(array(
				'type' => 'error',
				'data' => $e->getMessage()
			));
		}

		exit();
	}
}
